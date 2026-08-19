<?php
$site = require __DIR__ . '/data/site.php';
require __DIR__ . '/include/functions.php';

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

$admin_password = risegate_admin_password();
if ($admin_password === '' || empty($_SESSION['risegate_admin'])) {
    header('Location: admin.php');
    exit;
}

$definitions = hero_cms_definitions();
$settings_file = __DIR__ . '/data/hero-settings.php';
$saved_settings = hero_cms_settings();
$errors = [];
$message = ($_GET['message'] ?? '') === 'saved' ? 'ヒーロー表示設定を保存しました。' : '';

$defaults = [
    'home' => [
        'desktop' => ['x' => 50, 'y' => 56, 'overlay' => 0],
        'tablet' => ['x' => 64, 'y' => 54, 'overlay' => 0],
        'mobile' => ['x' => 90, 'y' => 52, 'overlay' => 0],
    ],
    'system' => [
        'desktop' => ['x' => 76, 'y' => 76, 'overlay' => 0],
        'tablet' => ['x' => 76, 'y' => 76, 'overlay' => 0],
        'mobile' => ['x' => 76, 'y' => 76, 'overlay' => 0],
    ],
    'website' => [
        'desktop' => ['x' => 82, 'y' => 50, 'overlay' => 0],
        'tablet' => ['x' => 88, 'y' => 50, 'overlay' => 0],
        'mobile' => ['x' => 92, 'y' => 50, 'overlay' => 0],
    ],
    'ai' => [
        'desktop' => ['x' => 72, 'y' => 50, 'overlay' => 0],
        'tablet' => ['x' => 72, 'y' => 50, 'overlay' => 0],
        'mobile' => ['x' => 72, 'y' => 50, 'overlay' => 0],
    ],
    'works' => [
        'desktop' => ['x' => 100, 'y' => 50, 'overlay' => 0],
        'tablet' => ['x' => 100, 'y' => 50, 'overlay' => 0],
        'mobile' => ['x' => 86, 'y' => 50, 'overlay' => 0],
    ],
    'company' => [
        'desktop' => ['x' => 100, 'y' => 50, 'overlay' => 0],
        'tablet' => ['x' => 100, 'y' => 50, 'overlay' => 0],
        'mobile' => ['x' => 68, 'y' => 50, 'overlay' => 0],
    ],
    'contact' => [
        'desktop' => ['x' => 62, 'y' => 50, 'overlay' => 0],
        'tablet' => ['x' => 62, 'y' => 50, 'overlay' => 0],
        'mobile' => ['x' => 62, 'y' => 50, 'overlay' => 0],
    ],
];

$form_settings = [];
foreach ($definitions as $key => $definition) {
    foreach (['desktop', 'tablet', 'mobile'] as $device) {
        $form_settings[$key][$device] = array_merge(
            $defaults[$key][$device],
            is_array($saved_settings[$key][$device] ?? null) ? $saved_settings[$key][$device] : []
        );
    }
}

if (empty($_SESSION['hero_settings_token'])) {
    $_SESSION['hero_settings_token'] = bin2hex(random_bytes(32));
}

if (($_POST['action'] ?? '') === 'save') {
    $posted_token = (string) ($_POST['hero_settings_token'] ?? '');
    if (!hash_equals((string) $_SESSION['hero_settings_token'], $posted_token)) {
        $errors[] = '送信内容を確認できませんでした。ページを再読み込みしてください。';
    } else {
        $posted_settings = is_array($_POST['settings'] ?? null) ? $_POST['settings'] : [];
        $clean_settings = [];

        foreach ($definitions as $key => $definition) {
            foreach (['desktop', 'tablet', 'mobile'] as $device) {
                $posted_device = is_array($posted_settings[$key][$device] ?? null)
                    ? $posted_settings[$key][$device]
                    : [];
                $clean_settings[$key][$device] = [
                    'x' => hero_cms_number($posted_device['x'] ?? $defaults[$key][$device]['x']),
                    'y' => hero_cms_number($posted_device['y'] ?? $defaults[$key][$device]['y']),
                    'overlay' => hero_cms_number($posted_device['overlay'] ?? 0),
                ];
            }
        }

        $content = "<?php\nreturn " . var_export($clean_settings, true) . ";\n";
        $temporary_file = $settings_file . '.tmp';
        if (file_put_contents($temporary_file, $content, LOCK_EX) === false || !rename($temporary_file, $settings_file)) {
            @unlink($temporary_file);
            $errors[] = '設定を保存できませんでした。dataディレクトリの書き込み権限を確認してください。';
        } else {
            $_SESSION['hero_settings_token'] = bin2hex(random_bytes(32));
            header('Location: admin-hero.php?message=saved');
            exit;
        }
    }
}

$device_labels = ['desktop' => 'PC', 'tablet' => 'タブレット', 'mobile' => 'スマホ'];
$page_title = 'ヒーロー表示設定';
$page_description = 'ヒーロー画像の表示位置とオーバーレイを調整します。';
include __DIR__ . '/include/head.php';
?>
<body class="admin-body">
  <main class="admin-shell admin-shell--hero">
    <header class="admin-header">
      <div>
        <p class="section-label">Rise Gate Admin</p>
        <h1>ヒーロー表示設定</h1>
        <p>端末ごとの写真位置と白オーバーレイをプレビューしながら調整します。</p>
      </div>
      <div class="admin-header__links">
        <a class="button button--secondary" href="admin.php">管理トップ</a>
        <a class="button button--secondary" href="index.php">サイトを見る</a>
        <form method="post"><button class="button button--secondary" type="submit" name="admin_logout" value="1">ログアウト</button></form>
      </div>
    </header>

    <?php if ($message !== '') : ?><p class="admin-alert admin-alert--success"><?php echo e($message); ?></p><?php endif; ?>
    <?php foreach ($errors as $error) : ?><p class="admin-alert admin-alert--error"><?php echo e($error); ?></p><?php endforeach; ?>

    <form class="admin-hero-form" method="post">
      <input type="hidden" name="action" value="save">
      <input type="hidden" name="hero_settings_token" value="<?php echo e((string) $_SESSION['hero_settings_token']); ?>">

      <?php foreach ($definitions as $key => $definition) : ?>
        <details class="admin-panel admin-hero-card"<?php echo $key === 'home' ? ' open' : ''; ?> data-hero-card>
          <summary>
            <span><small><?php echo e(strtoupper($key)); ?></small><strong><?php echo e($definition['label']); ?></strong></span>
            <span>表示を調整</span>
          </summary>

          <div class="admin-hero-device-grid">
            <?php foreach ($device_labels as $device => $device_label) :
              $values = $form_settings[$key][$device];
            ?>
              <section class="admin-hero-device" data-hero-device>
                <h3><?php echo e($device_label); ?></h3>
                <p class="admin-hero-image-name"><span>登録写真</span><?php echo e(basename($definition['image'])); ?></p>
                <div class="admin-hero-preview admin-hero-preview--<?php echo e($device); ?>"
                     data-hero-preview
                     style="--preview-x:<?php echo e((string) $values['x']); ?>%;--preview-y:<?php echo e((string) $values['y']); ?>%;--preview-overlay:<?php echo e((string) ($values['overlay'] / 100)); ?>;--preview-base-overlay:<?php echo e($definition['preview_overlay']); ?>;--preview-size:<?php echo e($definition['preview_size']); ?>;background-image:url('<?php echo e($definition['image']); ?>')">
                  <span>現在の表示</span>
                </div>

                <label class="admin-hero-range">
                  <span>横位置 <output data-range-output><?php echo e((string) $values['x']); ?></output>%</span>
                  <input type="range" min="0" max="100" step="1" name="settings[<?php echo e($key); ?>][<?php echo e($device); ?>][x]" value="<?php echo e((string) $values['x']); ?>" data-hero-control="x">
                </label>
                <label class="admin-hero-range">
                  <span>縦位置 <output data-range-output><?php echo e((string) $values['y']); ?></output>%</span>
                  <input type="range" min="0" max="100" step="1" name="settings[<?php echo e($key); ?>][<?php echo e($device); ?>][y]" value="<?php echo e((string) $values['y']); ?>" data-hero-control="y">
                </label>
                <label class="admin-hero-range">
                  <span>白オーバーレイを追加 <output data-range-output><?php echo e((string) $values['overlay']); ?></output>%</span>
                  <input type="range" min="0" max="80" step="1" name="settings[<?php echo e($key); ?>][<?php echo e($device); ?>][overlay]" value="<?php echo e((string) $values['overlay']); ?>" data-hero-control="overlay">
                </label>
              </section>
            <?php endforeach; ?>
          </div>
        </details>
      <?php endforeach; ?>

      <div class="admin-hero-savebar">
        <p>保存後、各ページへすぐ反映されます。</p>
        <button class="button button--primary" type="submit">ヒーロー設定を保存</button>
      </div>
    </form>
  </main>

  <script>
    document.querySelectorAll('[data-hero-device]').forEach((device) => {
      const preview = device.querySelector('[data-hero-preview]');
      device.querySelectorAll('[data-hero-control]').forEach((control) => {
        const update = () => {
          const value = Number(control.value);
          const output = control.closest('label').querySelector('[data-range-output]');
          output.value = String(value);
          if (control.dataset.heroControl === 'x') preview.style.setProperty('--preview-x', value + '%');
          if (control.dataset.heroControl === 'y') preview.style.setProperty('--preview-y', value + '%');
          if (control.dataset.heroControl === 'overlay') preview.style.setProperty('--preview-overlay', String(value / 100));
        };
        control.addEventListener('input', update);
      });
    });
  </script>
</body>
</html>
