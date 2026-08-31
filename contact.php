<?php
if (PHP_SAPI === 'cli') {
    session_save_path(dirname(__DIR__) . '/tmp/sessions');
}
session_start();

$site = require __DIR__ . '/data/site.php';
$navigation = require __DIR__ . '/data/navigation.php';
$contact = require __DIR__ . '/data/contact.php';
$contact_submissions_file = __DIR__ . '/data/contact_submissions.php';
require __DIR__ . '/include/functions.php';
require __DIR__ . '/include/contact-security.php';

$current_page = 'contact';
$page_title = $contact['meta']['title'];
$page_description = $contact['meta']['description'];

$form_fields = [
    'name' => '',
    'company' => '',
    'prefecture' => '',
    'email' => '',
    'tel' => '',
    'type' => '',
    'message' => '',
    'privacy' => '',
    'website' => '',
];
$form = $form_fields;
$requested_type = trim((string) ($_GET['type'] ?? ''));
if ($requested_type !== '' && in_array($requested_type, $contact['form']['options'], true)) {
    $form['type'] = $requested_type;
}
$errors = [];
$is_sent = isset($_GET['sent']) && $_GET['sent'] === '1';
$prefecture_options = [
    '北海道',
    '青森県',
    '岩手県',
    '宮城県',
    '秋田県',
    '山形県',
    '福島県',
    '茨城県',
    '栃木県',
    '群馬県',
    '埼玉県',
    '千葉県',
    '東京都',
    '神奈川県',
    '新潟県',
    '富山県',
    '石川県',
    '福井県',
    '山梨県',
    '長野県',
    '岐阜県',
    '静岡県',
    '愛知県',
    '三重県',
    '滋賀県',
    '京都府',
    '大阪府',
    '兵庫県',
    '奈良県',
    '和歌山県',
    '鳥取県',
    '島根県',
    '岡山県',
    '広島県',
    '山口県',
    '徳島県',
    '香川県',
    '愛媛県',
    '高知県',
    '福岡県',
    '佐賀県',
    '長崎県',
    '熊本県',
    '大分県',
    '宮崎県',
    '鹿児島県',
    '沖縄県',
];

if (empty($_SESSION['contact_token'])) {
    $_SESSION['contact_token'] = bin2hex(random_bytes(32));
}
if (empty($_SESSION['contact_form_started_at'])) {
    $_SESSION['contact_form_started_at'] = time();
}

$recaptcha_site_key = contact_env('RISEGATE_RECAPTCHA_SITE_KEY');
$recaptcha_is_configured = $recaptcha_site_key !== '' && contact_env('RISEGATE_RECAPTCHA_SECRET_KEY') !== '';

function contact_one_line(string $value): string
{
    return trim(str_replace(["\r", "\n"], '', $value));
}

function contact_build_body(array $form): string
{
    return implode("\n", [
        'ライズゲートのWebサイトからお問い合わせがありました。',
        '',
        '━━━━━━━━━━━━━━━━━━━━',
        'お問い合わせ内容',
        '━━━━━━━━━━━━━━━━━━━━',
        'お名前: ' . $form['name'],
        '会社名: ' . ($form['company'] !== '' ? $form['company'] : '未入力'),
        '都道府県: ' . $form['prefecture'],
        'メールアドレス: ' . $form['email'],
        '電話番号: ' . ($form['tel'] !== '' ? $form['tel'] : '未入力'),
        '相談内容: ' . $form['type'],
        '',
        'お問い合わせ内容:',
        $form['message'],
        '',
        '━━━━━━━━━━━━━━━━━━━━',
        '送信情報',
        '━━━━━━━━━━━━━━━━━━━━',
        '送信日時: ' . date('Y-m-d H:i:s'),
        '送信元IP: ' . ($_SERVER['REMOTE_ADDR'] ?? ''),
        'User-Agent: ' . ($_SERVER['HTTP_USER_AGENT'] ?? ''),
    ]);
}

function contact_mail_headers(string $from_email, string $from_name, string $reply_to = ''): string
{
    $headers = [
        'From: ' . mb_encode_mimeheader($from_name, 'UTF-8') . ' <' . $from_email . '>',
    ];

    if ($reply_to !== '') {
        $headers[] = 'Reply-To: ' . $reply_to;
    }

    return implode("\r\n", $headers);
}

function contact_save_submission(string $data_file, array $submission): bool
{
    $submissions = file_exists($data_file) ? require $data_file : [];
    if (!is_array($submissions)) {
        $submissions = [];
    }

    $submissions[] = $submission;
    $export = var_export(array_values($submissions), true);
    $content = "<?php\nreturn " . $export . ";\n";

    return file_put_contents($data_file, $content, LOCK_EX) !== false;
}

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $client_ip = contact_client_ip();
    foreach (array_keys($form_fields) as $key) {
        $form[$key] = trim((string) ($_POST[$key] ?? ''));
    }

    $content_length = (int) ($_SERVER['CONTENT_LENGTH'] ?? 0);
    if ($content_length <= 0 || $content_length > 32768) {
        $errors['form'] = '送信内容を確認できませんでした。入力内容を短くして、もう一度お試しください。';
    }

    $posted_token = (string) ($_POST['contact_token'] ?? '');
    if (!hash_equals((string) ($_SESSION['contact_token'] ?? ''), $posted_token)) {
        $errors['form'] = '送信内容の確認に失敗しました。時間をおいてもう一度お試しください。';
    }

    if ($form['website'] !== '') {
        contact_security_log('blocked', 'honeypot', $client_ip);
        header('Location: contact.php?sent=1#contact-form');
        exit;
    }

    $form_started_at = (int) ($_SESSION['contact_form_started_at'] ?? 0);
    if ($form_started_at <= 0 || time() - $form_started_at < 3 || time() - $form_started_at > 7200) {
        $errors['form'] = '送信内容を確認できませんでした。ページを再読み込みして、もう一度お試しください。';
    }

    if ($errors === []) {
        if ($form['name'] === '') {
            $errors['name'] = 'お名前を入力してください。';
        } elseif (contact_text_length($form['name']) > 100) {
            $errors['name'] = 'お名前は100文字以内で入力してください。';
        }

        if ($form['email'] === '') {
            $errors['email'] = 'メールアドレスを入力してください。';
        } elseif (contact_text_length($form['email']) > 254 || strpbrk($form['email'], "\r\n") !== false || !filter_var($form['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'メールアドレスの形式を確認してください。';
        }

        if (contact_text_length($form['company']) > 150) {
            $errors['company'] = '会社名は150文字以内で入力してください。';
        }

        if (contact_text_length($form['tel']) > 30 || ($form['tel'] !== '' && !preg_match('/\A[0-9+()\-\s]+\z/u', $form['tel']))) {
            $errors['tel'] = '電話番号の形式を確認してください。';
        }

        if ($form['prefecture'] === '' || !in_array($form['prefecture'], $prefecture_options, true)) {
            $errors['prefecture'] = '都道府県を選択してください。';
        }

        if ($form['type'] === '' || !in_array($form['type'], $contact['form']['options'], true)) {
            $errors['type'] = '相談内容を選択してください。';
        }

        if ($form['message'] === '') {
            $errors['message'] = 'お問い合わせ内容を入力してください。';
        } elseif (contact_text_length($form['message']) > 5000) {
            $errors['message'] = 'お問い合わせ内容は5000文字以内で入力してください。';
        }

        if ($form['privacy'] !== '1') {
            $errors['privacy'] = '個人情報の取り扱いに同意してください。';
        }
    }

    if ($errors === []) {
        $captcha = contact_verify_recaptcha((string) ($_POST['g-recaptcha-response'] ?? ''), $client_ip);
        if (!$captcha['success']) {
            contact_security_log('blocked', (string) $captcha['reason'], $client_ip);
            $errors['form'] = $captcha['reason'] === 'recaptcha-not-configured'
                ? '現在、フォームのセキュリティ設定中です。恐れ入りますが、時間をおいてお試しください。'
                : 'ロボットではないことを確認できませんでした。チェックを入れ直して、もう一度お試しください。';
        }
    }

    if ($errors === []) {
        $rate_limit = contact_rate_limit($client_ip);
        if (!$rate_limit['allowed']) {
            contact_security_log('blocked', (string) $rate_limit['reason'], $client_ip);
            $errors['form'] = '短時間に複数回の送信が確認されました。時間をおいて、もう一度お試しください。';
        }
    }

    if ($errors === []) {
        if (function_exists('mb_language')) {
            mb_language('Japanese');
        }
        if (function_exists('mb_internal_encoding')) {
            mb_internal_encoding('UTF-8');
        }

        $from_email = contact_one_line($contact['form']['from_email']);
        $admin_email = contact_one_line($contact['form']['mail_to']);
        $user_email = contact_one_line($form['email']);
        $subject = '【ライズゲート】お問い合わせがありました';

        $admin_sent = false;
        if (contact_mail_enabled()) {
            $admin_sent = mb_send_mail(
                $admin_email,
                $subject,
                contact_build_body($form),
                contact_mail_headers($from_email, '株式会社ライズゲート', $user_email)
            );
        }

        $submission = [
            'id' => date('YmdHis') . '-' . bin2hex(random_bytes(4)),
            'created_at' => date('Y-m-d H:i:s'),
            'status' => 'unread',
            'prefecture' => $form['prefecture'],
            'consultation_type' => $form['type'],
            'recipient' => 'headquarters',
            'name' => $form['name'],
            'company' => $form['company'],
            'email' => $form['email'],
            'tel' => $form['tel'],
            'message' => $form['message'],
            'admin_mail_sent' => $admin_sent,
            'reply_mail_sent' => false,
            'remote_addr' => $client_ip,
            'user_agent' => (string) ($_SERVER['HTTP_USER_AGENT'] ?? ''),
        ];

        if (!contact_save_submission($contact_submissions_file, $submission)) {
            $errors['form'] = 'お問い合わせ内容を管理画面へ保存できませんでした。時間をおいてもう一度お試しください。';
        } else {
            contact_security_log('accepted', $admin_sent ? 'admin-mail-sent' : 'mail-disabled-or-failed', $client_ip);
            $form = $form_fields;
            $_SESSION['contact_token'] = bin2hex(random_bytes(32));
            $_SESSION['contact_form_started_at'] = time();
            header('Location: contact.php?sent=1#contact-form');
            exit;
        }
    }
}

$contact_token = (string) ($_SESSION['contact_token'] ?? '');
include __DIR__ . '/include/head.php';
include __DIR__ . '/include/header.php';
?>

<main>
  <?php if ($is_sent) : ?>
    <div class="contact-sent-banner" role="status" aria-live="polite">
      <div class="section-inner">
        <strong>送信が完了しました。</strong>
        <span>お問い合わせありがとうございます。内容を確認し、折り返しご連絡いたします。</span>
      </div>
    </div>
  <?php endif; ?>

  <section class="page-hero hero-scene hero-scene--contact">
    <div class="section-inner section-inner--narrow">
      <p class="section-label"><?php echo e($contact['hero']['label']); ?></p>
      <h1><?php echo responsive_text($contact['hero'], 'title'); ?></h1>
      <p class="section-lead"><?php echo responsive_text($contact['hero'], 'lead'); ?></p>
    </div>
  </section>

  <section class="contact-examples">
    <div class="section-inner">
      <div class="section-heading">
        <p class="section-label"><?php echo e($contact['examples']['label']); ?></p>
        <h2><?php echo responsive_text($contact['examples'], 'title'); ?></h2>
      </div>
      <div class="content-grid content-grid--three">
        <?php foreach ($contact['examples']['items'] as $item) : ?>
          <article class="content-card">
            <h3><?php echo e($item); ?></h3>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="contact-pricing">
    <div class="section-inner section-inner--narrow">
      <p class="section-label"><?php echo e($contact['pricing']['label']); ?></p>
      <h2><?php echo responsive_text($contact['pricing'], 'title'); ?></h2>
      <?php foreach ($contact['pricing']['body'] as $paragraph) : ?>
        <p><?php echo e($paragraph); ?></p>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="contact-form-section" id="contact-form">
    <div class="section-inner section-inner--narrow">
      <p class="section-label"><?php echo e($contact['form']['label']); ?></p>
      <h2><?php echo responsive_text($contact['form'], 'title'); ?></h2>
      <p><?php echo e($contact['form']['body']); ?></p>

      <?php if ($is_sent) : ?>
        <div class="contact-form-message contact-form-message--success">
          <h3>送信が完了しました。</h3>
          <p>お問い合わせありがとうございます。内容を確認し、折り返しご連絡いたします。</p>
        </div>
      <?php else : ?>
        <?php if (isset($errors['form'])) : ?>
          <div class="contact-form-message contact-form-message--error">
            <p><?php echo e($errors['form']); ?></p>
          </div>
        <?php endif; ?>

        <form class="contact-form" method="post" action="contact.php#contact-form" id="contact-form" novalidate>
          <input type="hidden" name="contact_token" value="<?php echo e($contact_token); ?>">
          <div class="contact-form__trap" aria-hidden="true">
            <label>
              Webサイト
              <input type="text" name="website" value="<?php echo e($form['website']); ?>" tabindex="-1" autocomplete="off">
            </label>
          </div>

          <div class="contact-form__grid">
            <label>
              <span>お名前 <b>必須</b></span>
              <input type="text" name="name" value="<?php echo e($form['name']); ?>" autocomplete="name" maxlength="100" required>
              <?php if (isset($errors['name'])) : ?><em><?php echo e($errors['name']); ?></em><?php endif; ?>
            </label>

            <label>
              <span>会社名</span>
              <input type="text" name="company" value="<?php echo e($form['company']); ?>" autocomplete="organization" maxlength="150">
              <?php if (isset($errors['company'])) : ?><em><?php echo e($errors['company']); ?></em><?php endif; ?>
            </label>

            <label>
              <span>都道府県 <b>必須</b></span>
              <select name="prefecture" autocomplete="address-level1" required>
                <option value="">選択してください</option>
                <?php foreach ($prefecture_options as $prefecture_option) : ?>
                  <option value="<?php echo e($prefecture_option); ?>"<?php echo $form['prefecture'] === $prefecture_option ? ' selected' : ''; ?>><?php echo e($prefecture_option); ?></option>
                <?php endforeach; ?>
              </select>
              <?php if (isset($errors['prefecture'])) : ?><em><?php echo e($errors['prefecture']); ?></em><?php endif; ?>
            </label>

            <label>
              <span>メールアドレス <b>必須</b></span>
              <input type="email" name="email" value="<?php echo e($form['email']); ?>" autocomplete="email" maxlength="254" required>
              <?php if (isset($errors['email'])) : ?><em><?php echo e($errors['email']); ?></em><?php endif; ?>
            </label>

            <label>
              <span>電話番号</span>
              <input type="tel" name="tel" value="<?php echo e($form['tel']); ?>" autocomplete="tel" maxlength="30">
              <?php if (isset($errors['tel'])) : ?><em><?php echo e($errors['tel']); ?></em><?php endif; ?>
            </label>
          </div>

          <label>
            <span>相談内容 <b>必須</b></span>
            <select name="type" required>
              <option value="">選択してください</option>
              <?php foreach ($contact['form']['options'] as $option) : ?>
                <option value="<?php echo e($option); ?>"<?php echo $form['type'] === $option ? ' selected' : ''; ?>><?php echo e($option); ?></option>
              <?php endforeach; ?>
            </select>
            <?php if (isset($errors['type'])) : ?><em><?php echo e($errors['type']); ?></em><?php endif; ?>
          </label>

          <label>
            <span>お問い合わせ内容 <b>必須</b></span>
            <textarea name="message" rows="8" maxlength="5000" required><?php echo e($form['message']); ?></textarea>
            <?php if (isset($errors['message'])) : ?><em><?php echo e($errors['message']); ?></em><?php endif; ?>
          </label>

          <label class="contact-form__privacy">
            <input type="checkbox" name="privacy" value="1"<?php echo $form['privacy'] === '1' ? ' checked' : ''; ?> required>
            <span>送信内容を確認し、問い合わせ対応のために入力情報を利用することに同意します。 <b>必須</b></span>
          </label>
          <?php if (isset($errors['privacy'])) : ?><em class="contact-form__privacy-error"><?php echo e($errors['privacy']); ?></em><?php endif; ?>

          <?php if ($recaptcha_is_configured) : ?>
            <div class="g-recaptcha" data-sitekey="<?php echo e($recaptcha_site_key); ?>"></div>
          <?php else : ?>
            <div class="contact-form-message contact-form-message--error">
              <p>現在、フォームのセキュリティ設定中です。恐れ入りますが、時間をおいてお試しください。</p>
            </div>
          <?php endif; ?>

          <button class="button button--primary" type="submit"<?php echo $recaptcha_is_configured ? '' : ' disabled'; ?>>送信する</button>
        </form>
      <?php endif; ?>
    </div>
  </section>

</main>

<?php if ($recaptcha_is_configured) : ?>
  <script src="https://www.google.com/recaptcha/api.js?hl=ja" async defer></script>
<?php endif; ?>
<?php include __DIR__ . '/include/footer.php'; ?>
