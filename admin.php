<?php
$site = require __DIR__ . '/data/site.php';
require __DIR__ . '/include/functions.php';

$works = file_exists(__DIR__ . '/data/works.php') ? require __DIR__ . '/data/works.php' : [];
$masters = file_exists(__DIR__ . '/data/improvement_masters.php') ? require __DIR__ . '/data/improvement_masters.php' : [];
$contact_submissions = file_exists(__DIR__ . '/data/contact_submissions.php') ? require __DIR__ . '/data/contact_submissions.php' : [];
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
        header('Location: admin.php');
        exit;
    }

    $errors[] = 'パスワードが違います。';
}

if ($admin_password === '' || empty($_SESSION['risegate_admin'])) {
    $page_title = '管理画面';
    $page_description = 'ライズゲート管理画面';
    include __DIR__ . '/include/head.php';
    ?>
    <body class="admin-body">
      <main class="admin-shell admin-shell--login">
        <section class="admin-panel">
          <div class="admin-login__brand" aria-hidden="true">RG</div>
          <p class="section-label">Rise Gate Admin</p>
          <h1>おかえりなさい</h1>
          <p class="admin-login__lead">管理画面にログインしてください。</p>
          <?php foreach ($errors as $error) : ?>
            <p class="admin-alert admin-alert--error"><?php echo e($error); ?></p>
          <?php endforeach; ?>
          <?php if ($admin_password !== '') : ?>
            <form method="post" class="admin-form">
              <label>
                <span>パスワード</span>
                <input type="password" name="admin_password" autocomplete="current-password" placeholder="パスワードを入力" required autofocus>
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

if (!is_array($works)) {
    $works = [];
}

if (!is_array($masters)) {
    $masters = [];
}

if (!is_array($contact_submissions)) {
    $contact_submissions = [];
}

$published_works_count = count(array_filter($works, function ($work) {
    return ($work['status'] ?? '') === 'published';
}));

$published_masters_count = count(array_filter($masters, function ($master) {
    return ($master['status'] ?? '') === 'published';
}));

usort($contact_submissions, function ($a, $b) {
    return strcmp($b['created_at'] ?? '', $a['created_at'] ?? '');
});
$active_contact_submissions = array_values(array_filter($contact_submissions, function ($submission) {
    return ($submission['status'] ?? 'unread') !== 'spam';
}));
$unread_contact_count = count(array_filter($active_contact_submissions, function ($submission) {
    return ($submission['status'] ?? 'unread') === 'unread';
}));
$latest_contact = $active_contact_submissions[0]['created_at'] ?? '';

$page_title = '管理画面';
$page_description = 'ライズゲート管理画面';
include __DIR__ . '/include/head.php';
?>
<body class="admin-body">
  <main class="admin-shell">
    <header class="admin-header">
      <div>
        <p class="section-label">Rise Gate Admin</p>
        <h1>管理画面</h1>
        <p>更新する内容を選んでください。各機能は専用の管理画面で編集できます。</p>
      </div>
      <div class="admin-header__links">
        <a class="button button--secondary" href="index.php">サイトへ戻る</a>
        <form method="post">
          <button class="button button--secondary" type="submit" name="admin_logout" value="1">ログアウト</button>
        </form>
      </div>
    </header>

    <?php foreach ($errors as $error) : ?>
      <p class="admin-alert admin-alert--error"><?php echo e($error); ?></p>
    <?php endforeach; ?>

    <section class="admin-dashboard-grid" aria-label="管理メニュー">
      <a class="admin-dashboard-card" href="admin-contacts.php">
        <span class="section-label">Contact Inbox</span>
        <strong>問い合わせ管理</strong>
        <span><?php echo e((string) $unread_contact_count); ?>件未確認 / 全<?php echo e((string) count($active_contact_submissions)); ?>件<?php echo $latest_contact !== '' ? ' / 最新 ' . e($latest_contact) : ''; ?></span>
      </a>

      <a class="admin-dashboard-card" href="admin-works.php">
        <span class="section-label">Works</span>
        <strong>実績管理</strong>
        <span><?php echo e((string) count($works)); ?>件 / 公開 <?php echo e((string) $published_works_count); ?>件</span>
      </a>

      <a class="admin-dashboard-card" href="admin-masters.php">
        <span class="section-label">Improvement Masters</span>
        <strong>改善マスター管理</strong>
        <span><?php echo e((string) count($masters)); ?>人 / 公開 <?php echo e((string) $published_masters_count); ?>人</span>
      </a>
    </section>
  </main>
</body>
</html>
