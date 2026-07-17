<?php
if (PHP_SAPI === 'cli') {
    session_save_path(dirname(__DIR__) . '/tmp/sessions');
}
session_start();

$site = require __DIR__ . '/data/site.php';
$navigation = require __DIR__ . '/data/navigation.php';
$contact = require __DIR__ . '/data/contact.php';
$improvement_masters_file = __DIR__ . '/data/improvement_masters.php';
$improvement_masters = file_exists($improvement_masters_file) ? require $improvement_masters_file : [];
$contact_submissions_file = __DIR__ . '/data/contact_submissions.php';
require __DIR__ . '/include/functions.php';

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
    'master_slug' => '',
];
$form = $form_fields;
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

function contact_build_reply_body(array $form): string
{
    return implode("\n", [
        $form['name'] . ' 様',
        '',
        'このたびは、株式会社ライズゲートへお問い合わせいただきありがとうございます。',
        '以下の内容でお問い合わせを受け付けました。',
        '内容を確認し、折り返しご連絡いたします。',
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
        '株式会社ライズゲート',
        'https://rise-gate.com/',
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

function contact_find_master(array $masters, string $slug): ?array
{
    foreach ($masters as $master) {
        if (($master['slug'] ?? '') === $slug) {
            return $master;
        }
    }

    return null;
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

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    $form['master_slug'] = trim((string) ($_GET['master'] ?? ''));
    $initial_master = contact_find_master($improvement_masters, $form['master_slug']);
    if ($initial_master && $form['prefecture'] === '') {
        $form['prefecture'] = (string) ($initial_master['prefecture'] ?? '');
    }
}

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    foreach (array_keys($form_fields) as $key) {
        $form[$key] = trim((string) ($_POST[$key] ?? ''));
    }

    $posted_token = (string) ($_POST['contact_token'] ?? '');
    if (!hash_equals((string) ($_SESSION['contact_token'] ?? ''), $posted_token)) {
        $errors['form'] = '送信内容の確認に失敗しました。時間をおいてもう一度お試しください。';
    }

    if ($form['website'] !== '') {
        $is_sent = true;
    }

    if (!$is_sent) {
        if ($form['name'] === '') {
            $errors['name'] = 'お名前を入力してください。';
        }

        if ($form['email'] === '') {
            $errors['email'] = 'メールアドレスを入力してください。';
        } elseif (!filter_var($form['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'メールアドレスの形式を確認してください。';
        }

        if ($form['prefecture'] === '' || !in_array($form['prefecture'], $prefecture_options, true)) {
            $errors['prefecture'] = '都道府県を選択してください。';
        }

        if ($form['type'] === '' || !in_array($form['type'], $contact['form']['options'], true)) {
            $errors['type'] = '相談内容を選択してください。';
        }

        if ($form['message'] === '') {
            $errors['message'] = 'お問い合わせ内容を入力してください。';
        }

        if ($form['privacy'] !== '1') {
            $errors['privacy'] = '個人情報の取り扱いに同意してください。';
        }
    }

    if (!$is_sent && $errors === []) {
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
        $reply_subject = '【ライズゲート】お問い合わせありがとうございます';

        $admin_sent = mb_send_mail(
            $admin_email,
            $subject,
            contact_build_body($form),
            contact_mail_headers($from_email, '株式会社ライズゲート', $user_email)
        );

        $reply_sent = false;
        if ($admin_sent) {
            $reply_sent = mb_send_mail(
                $user_email,
                $reply_subject,
                contact_build_reply_body($form),
                contact_mail_headers($from_email, '株式会社ライズゲート')
            );
        }

        $selected_master = contact_find_master($improvement_masters, $form['master_slug']);
        $submission = [
            'id' => date('YmdHis') . '-' . bin2hex(random_bytes(4)),
            'created_at' => date('Y-m-d H:i:s'),
            'status' => 'unread',
            'prefecture' => $form['prefecture'],
            'consultation_type' => $form['type'],
            'recipient' => $selected_master ? 'master' : 'headquarters',
            'master_slug' => (string) ($selected_master['slug'] ?? ''),
            'master_name' => (string) ($selected_master['name'] ?? ''),
            'name' => $form['name'],
            'company' => $form['company'],
            'email' => $form['email'],
            'tel' => $form['tel'],
            'message' => $form['message'],
            'admin_mail_sent' => $admin_sent,
            'reply_mail_sent' => $reply_sent,
            'remote_addr' => (string) ($_SERVER['REMOTE_ADDR'] ?? ''),
            'user_agent' => (string) ($_SERVER['HTTP_USER_AGENT'] ?? ''),
        ];

        if (!contact_save_submission($contact_submissions_file, $submission)) {
            $errors['form'] = 'お問い合わせ内容を管理画面へ保存できませんでした。時間をおいてもう一度お試しください。';
        } else {
            $form = $form_fields;
            $_SESSION['contact_token'] = bin2hex(random_bytes(32));
            header('Location: contact.php?sent=1#contact-form');
            exit;
        }
    }
}

$contact_token = (string) ($_SESSION['contact_token'] ?? '');
$selected_master = contact_find_master($improvement_masters, $form['master_slug']);

include __DIR__ . '/include/head.php';
include __DIR__ . '/include/header.php';
?>

<main>
  <?php if ($is_sent) : ?>
    <div class="contact-sent-banner" role="status" aria-live="polite">
      <div class="section-inner">
        <strong>送信が完了しました。</strong>
        <span>お問い合わせありがとうございます。控えのメールも送信しています。</span>
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

  <section class="contact-stance">
    <div class="section-inner section-inner--narrow">
      <p class="section-label"><?php echo e($contact['stance']['label']); ?></p>
      <h2><?php echo responsive_text($contact['stance'], 'title'); ?></h2>
      <?php foreach ($contact['stance']['body'] as $paragraph) : ?>
        <p><?php echo e($paragraph); ?></p>
      <?php endforeach; ?>
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
          <p>控えのメールが届かない場合でも、問い合わせ内容は受付済みです。</p>
        </div>
      <?php else : ?>
        <?php if (isset($errors['form'])) : ?>
          <div class="contact-form-message contact-form-message--error">
            <p><?php echo e($errors['form']); ?></p>
          </div>
        <?php endif; ?>

        <form class="contact-form" method="post" action="contact.php#contact-form" id="contact-form" novalidate>
          <input type="hidden" name="contact_token" value="<?php echo e($contact_token); ?>">
          <input type="hidden" name="master_slug" value="<?php echo e($form['master_slug']); ?>">
          <div class="contact-form__trap" aria-hidden="true">
            <label>
              Webサイト
              <input type="text" name="website" value="<?php echo e($form['website']); ?>" tabindex="-1" autocomplete="off">
            </label>
          </div>

          <div class="contact-form__grid">
            <label>
              <span>お名前 <b>必須</b></span>
              <input type="text" name="name" value="<?php echo e($form['name']); ?>" autocomplete="name" required>
              <?php if (isset($errors['name'])) : ?><em><?php echo e($errors['name']); ?></em><?php endif; ?>
            </label>

            <label>
              <span>会社名</span>
              <input type="text" name="company" value="<?php echo e($form['company']); ?>" autocomplete="organization">
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
              <input type="email" name="email" value="<?php echo e($form['email']); ?>" autocomplete="email" required>
              <?php if (isset($errors['email'])) : ?><em><?php echo e($errors['email']); ?></em><?php endif; ?>
            </label>

            <label>
              <span>電話番号</span>
              <input type="tel" name="tel" value="<?php echo e($form['tel']); ?>" autocomplete="tel">
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
            <textarea name="message" rows="8" required><?php echo e($form['message']); ?></textarea>
            <?php if (isset($errors['message'])) : ?><em><?php echo e($errors['message']); ?></em><?php endif; ?>
          </label>

          <label class="contact-form__privacy">
            <input type="checkbox" name="privacy" value="1"<?php echo $form['privacy'] === '1' ? ' checked' : ''; ?> required>
            <span>送信内容を確認し、問い合わせ対応のために入力情報を利用することに同意します。 <b>必須</b></span>
          </label>
          <?php if (isset($errors['privacy'])) : ?><em class="contact-form__privacy-error"><?php echo e($errors['privacy']); ?></em><?php endif; ?>

          <button class="button button--primary" type="submit">送信する</button>
        </form>
      <?php endif; ?>
    </div>
  </section>

  <section class="company-link">
    <div class="section-inner section-inner--narrow">
      <p class="section-label"><?php echo e($contact['company_link']['label']); ?></p>
      <h2><?php echo responsive_text($contact['company_link'], 'title'); ?></h2>
      <p><?php echo e($contact['company_link']['body']); ?></p>
      <a class="text-link" href="<?php echo e($contact['company_link']['link']['url']); ?>">
        <?php echo e($contact['company_link']['link']['label']); ?>
      </a>
    </div>
  </section>
</main>

<?php include __DIR__ . '/include/footer.php'; ?>
