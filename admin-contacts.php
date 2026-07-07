<?php
$site = require __DIR__ . '/data/site.php';
require __DIR__ . '/include/functions.php';

$contact_submissions_file = __DIR__ . '/data/contact_submissions.php';
$contact_submissions = file_exists($contact_submissions_file) ? require $contact_submissions_file : [];
$admin_password = getenv('RISEGATE_ADMIN_PASSWORD') ?: '';
$errors = [];

if (PHP_SAPI === 'cli') {
    session_save_path(dirname(__DIR__) . '/tmp/sessions');
}

session_start();

if (isset($_POST['admin_logout'])) {
    $_SESSION = [];
    session_destroy();
    header('Location: admin.php');
    exit;
}

if ($admin_password === '') {
    unset($_SESSION['risegate_admin']);
    $errors[] = 'Admin password is not configured. Set RISEGATE_ADMIN_PASSWORD on the server.';
} elseif (isset($_POST['admin_password'])) {
    if (hash_equals($admin_password, (string) $_POST['admin_password'])) {
        $_SESSION['risegate_admin'] = true;
        header('Location: admin-contacts.php');
        exit;
    }

    $errors[] = 'パスワードが違います。';
}

if ($admin_password === '' || empty($_SESSION['risegate_admin'])) {
    $page_title = '問い合わせ管理';
    $page_description = 'ライズゲート問い合わせ管理';
    include __DIR__ . '/include/head.php';
    ?>
    <body class="admin-body">
      <main class="admin-shell admin-shell--login">
        <section class="admin-panel">
          <p class="section-label">Rise Gate Admin</p>
          <h1>問い合わせ管理</h1>
          <?php foreach ($errors as $error) : ?>
            <p class="admin-alert admin-alert--error"><?php echo e($error); ?></p>
          <?php endforeach; ?>
          <?php if ($admin_password !== '') : ?>
            <form method="post" class="admin-form">
              <label>
                <span>パスワード</span>
                <input type="password" name="admin_password" required>
              </label>
              <button class="button button--primary" type="submit">ログイン</button>
            </form>
          <?php endif; ?>
        </section>
      </main>
    </body>
    </html>
    <?php
    exit;
}

function admin_contact_find_submission(array $submissions, string $id): ?array
{
    foreach ($submissions as $submission) {
        if (($submission['id'] ?? '') === $id) {
            return $submission;
        }
    }

    return null;
}

function admin_contact_recipient_label(string $recipient): string
{
    return match ($recipient) {
        'master' => '改善マスター',
        'headquarters' => '本部',
        default => $recipient !== '' ? $recipient : '-',
    };
}

function admin_contact_mail_status_label($value): string
{
    if ($value === true) {
        return '成功';
    }

    if ($value === false) {
        return '失敗';
    }

    return '-';
}

if (!is_array($contact_submissions)) {
    $contact_submissions = [];
}

usort($contact_submissions, function ($a, $b) {
    return strcmp($b['created_at'] ?? '', $a['created_at'] ?? '');
});

$selected_submission_id = trim((string) ($_GET['inquiry'] ?? ''));
$selected_submission = $selected_submission_id !== '' ? admin_contact_find_submission($contact_submissions, $selected_submission_id) : null;

$page_title = '問い合わせ管理';
$page_description = 'ライズゲート問い合わせ管理';
include __DIR__ . '/include/head.php';
?>
<body class="admin-body">
  <main class="admin-shell">
    <header class="admin-header">
      <div>
        <p class="section-label">Rise Gate Admin</p>
        <h1>問い合わせ管理</h1>
        <p>問い合わせフォームから届いた内容を確認します。</p>
      </div>
      <div class="admin-header__links">
        <a class="button button--secondary" href="admin.php">管理トップ</a>
        <a class="button button--secondary" href="contact.php">問い合わせページ</a>
        <form method="post">
          <button class="button button--secondary" type="submit" name="admin_logout" value="1">ログアウト</button>
        </form>
      </div>
    </header>

    <?php foreach ($errors as $error) : ?>
      <p class="admin-alert admin-alert--error"><?php echo e($error); ?></p>
    <?php endforeach; ?>

    <section class="admin-panel admin-contact-panel" id="contact-submissions">
      <div class="admin-panel__head">
        <div>
          <p class="section-label">Contact Inbox</p>
          <h2>問い合わせ一覧</h2>
        </div>
        <span class="admin-count-badge"><?php echo e((string) count($contact_submissions)); ?>件</span>
      </div>

      <?php if (empty($contact_submissions)) : ?>
        <p>問い合わせはまだありません。</p>
      <?php else : ?>
        <div class="admin-contact-table-wrap">
          <table class="admin-contact-table">
            <thead>
              <tr>
                <th>日付時間</th>
                <th>都道府県</th>
                <th>相談選択肢</th>
                <th>宛先</th>
                <th>改善マスター名</th>
                <th>詳細</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($contact_submissions as $submission) : ?>
                <tr>
                  <td class="admin-contact-table__date"><?php echo e($submission['created_at'] ?? ''); ?></td>
                  <td><?php echo e(($submission['prefecture'] ?? '') !== '' ? $submission['prefecture'] : '-'); ?></td>
                  <td class="admin-contact-table__type"><?php echo e($submission['consultation_type'] ?? ''); ?></td>
                  <td><span class="admin-contact-pill"><?php echo e(admin_contact_recipient_label((string) ($submission['recipient'] ?? ''))); ?></span></td>
                  <td><?php echo e(($submission['master_name'] ?? '') !== '' ? $submission['master_name'] : '-'); ?></td>
                  <td class="admin-contact-table__action">
                    <a class="admin-action-button admin-action-button--compact" href="admin-contacts.php?inquiry=<?php echo e(rawurlencode((string) ($submission['id'] ?? ''))); ?>#contact-submissions">詳細</a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>

      <?php if ($selected_submission) : ?>
        <article class="admin-contact-detail">
          <div class="admin-panel__head">
            <h3>問い合わせ詳細</h3>
            <a class="text-link" href="admin-contacts.php#contact-submissions">閉じる</a>
          </div>
          <dl class="profile-list">
            <div>
              <dt>日付時間</dt>
              <dd><?php echo e($selected_submission['created_at'] ?? ''); ?></dd>
            </div>
            <div>
              <dt>宛先</dt>
              <dd><?php echo e(admin_contact_recipient_label((string) ($selected_submission['recipient'] ?? ''))); ?></dd>
            </div>
            <div>
              <dt>都道府県</dt>
              <dd><?php echo e(($selected_submission['prefecture'] ?? '') !== '' ? $selected_submission['prefecture'] : '-'); ?></dd>
            </div>
            <div>
              <dt>改善マスター名</dt>
              <dd><?php echo e(($selected_submission['master_name'] ?? '') !== '' ? $selected_submission['master_name'] : '-'); ?></dd>
            </div>
            <div>
              <dt>相談選択肢</dt>
              <dd><?php echo e($selected_submission['consultation_type'] ?? ''); ?></dd>
            </div>
            <div>
              <dt>名前</dt>
              <dd><?php echo e($selected_submission['name'] ?? ''); ?></dd>
            </div>
            <div>
              <dt>会社名</dt>
              <dd><?php echo e(($selected_submission['company'] ?? '') !== '' ? $selected_submission['company'] : '-'); ?></dd>
            </div>
            <div>
              <dt>メールアドレス</dt>
              <dd><?php echo e($selected_submission['email'] ?? ''); ?></dd>
            </div>
            <div>
              <dt>電話番号</dt>
              <dd><?php echo e(($selected_submission['tel'] ?? '') !== '' ? $selected_submission['tel'] : '-'); ?></dd>
            </div>
            <div>
              <dt>管理者メール</dt>
              <dd><?php echo e(admin_contact_mail_status_label($selected_submission['admin_mail_sent'] ?? null)); ?></dd>
            </div>
            <div>
              <dt>自動返信メール</dt>
              <dd><?php echo e(admin_contact_mail_status_label($selected_submission['reply_mail_sent'] ?? null)); ?></dd>
            </div>
            <div>
              <dt>内容</dt>
              <dd><pre><?php echo e($selected_submission['message'] ?? ''); ?></pre></dd>
            </div>
          </dl>
        </article>
      <?php elseif ($selected_submission_id !== '') : ?>
        <p class="admin-alert admin-alert--error">指定された問い合わせが見つかりませんでした。</p>
      <?php endif; ?>
    </section>
  </main>
</body>
</html>
