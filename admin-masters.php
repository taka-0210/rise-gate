<?php
$site = require __DIR__ . '/data/site.php';
require __DIR__ . '/include/functions.php';

$data_file = __DIR__ . '/data/improvement_masters.php';
$masters = file_exists($data_file) ? require $data_file : [];
$admin_password = getenv('RISEGATE_ADMIN_PASSWORD') ?: '';
$errors = [];
$message = '';

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
        header('Location: admin-masters.php');
        exit;
    }

    $errors[] = 'パスワードが違います。';
}

if ($admin_password === '' || empty($_SESSION['risegate_admin'])) {
    $page_title = '改善マスター管理';
    $page_description = 'ライズゲート改善マスター管理';
    include __DIR__ . '/include/head.php';
    ?>
    <body class="admin-body">
      <main class="admin-shell admin-shell--login">
        <section class="admin-panel">
          <p class="section-label">Rise Gate Admin</p>
          <h1>改善マスター管理</h1>
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

function master_slugify(string $value): string
{
    $slug = strtolower(trim($value));
    $slug = preg_replace('/[^a-z0-9-]+/', '-', $slug);
    $slug = trim((string) $slug, '-');

    return $slug !== '' ? $slug : 'master-' . date('Ymd-His');
}

function save_masters(string $data_file, array $masters): bool
{
    $export = var_export(array_values($masters), true);
    $content = "<?php\nreturn " . $export . ";\n";

    return file_put_contents($data_file, $content, LOCK_EX) !== false;
}

function find_master(array $masters, string $slug): ?array
{
    foreach ($masters as $master) {
        if (($master['slug'] ?? '') === $slug) {
            return $master;
        }
    }

    return null;
}

function master_create_image_resource(string $path, int $image_type)
{
    return match ($image_type) {
        IMAGETYPE_JPEG => function_exists('imagecreatefromjpeg') ? imagecreatefromjpeg($path) : false,
        IMAGETYPE_PNG => function_exists('imagecreatefrompng') ? imagecreatefrompng($path) : false,
        IMAGETYPE_WEBP => function_exists('imagecreatefromwebp') ? imagecreatefromwebp($path) : false,
        default => false,
    };
}

function master_save_profile_image(string $field, string $slug, array &$errors): string
{
    if (!isset($_FILES[$field]) || ($_FILES[$field]['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return '';
    }

    if ($_FILES[$field]['error'] !== UPLOAD_ERR_OK) {
        $errors[] = '写真のアップロードに失敗しました。';
        return '';
    }

    $tmp_name = (string) $_FILES[$field]['tmp_name'];
    $original_name = (string) $_FILES[$field]['name'];
    $extension = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));

    if (!in_array($extension, ['jpg', 'jpeg', 'png', 'webp'], true)) {
        $errors[] = '写真は jpg / png / webp のいずれかで登録してください。';
        return '';
    }

    $image_info = @getimagesize($tmp_name);
    if ($image_info === false) {
        $errors[] = '写真は画像ファイルとして確認できませんでした。';
        return '';
    }

    $image_type = (int) ($image_info[2] ?? 0);
    $can_optimize = in_array($image_type, [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_WEBP], true)
        && function_exists('imagecreatetruecolor')
        && function_exists('imagecopyresampled')
        && function_exists('imagewebp');

    $upload_subdir = $can_optimize ? 'image/masters/optimized' : 'image/masters';
    $upload_dir = __DIR__ . '/' . $upload_subdir;
    if (!is_dir($upload_dir) && !mkdir($upload_dir, 0775, true)) {
        $errors[] = '写真保存用ディレクトリを作成できませんでした。';
        return '';
    }

    $filename_base = $slug . '-profile-' . date('YmdHis');

    if (!$can_optimize) {
        $filename = $filename_base . '.' . $extension;
        $destination = $upload_dir . '/' . $filename;
        if (!move_uploaded_file($tmp_name, $destination)) {
            $errors[] = '写真を保存できませんでした。';
            return '';
        }

        return $upload_subdir . '/' . $filename;
    }

    $source_image = master_create_image_resource($tmp_name, $image_type);
    if (!$source_image) {
        $errors[] = '写真を処理できませんでした。';
        return '';
    }

    [$width, $height] = $image_info;
    $crop_size = min($width, $height);
    $source_x = (int) floor(($width - $crop_size) / 2);
    $source_y = (int) floor(($height - $crop_size) / 2);
    $target_size = min(720, $crop_size);

    imagepalettetotruecolor($source_image);
    imagealphablending($source_image, true);
    imagesavealpha($source_image, true);

    $resized_image = imagecreatetruecolor($target_size, $target_size);
    imagealphablending($resized_image, false);
    imagesavealpha($resized_image, true);

    imagecopyresampled($resized_image, $source_image, 0, 0, $source_x, $source_y, $target_size, $target_size, $crop_size, $crop_size);
    imagefilter($resized_image, IMG_FILTER_GRAYSCALE);

    $filename = $filename_base . '.webp';
    $destination = $upload_dir . '/' . $filename;
    $saved = imagewebp($resized_image, $destination, 82);

    imagedestroy($source_image);
    imagedestroy($resized_image);

    if (!$saved) {
        $errors[] = '写真を保存できませんでした。';
        return '';
    }

    return $upload_subdir . '/' . $filename;
}

$default_master = [
    'status' => 'draft',
    'slug' => '',
    'name' => '',
    'company_name' => '',
    'prefecture' => '',
    'city' => '',
    'map_x' => '',
    'map_y' => '',
    'certified_at' => date('Y-m-d'),
    'focus' => '',
    'profile' => '',
    'profile_image' => '',
    'link_url' => '',
];

if (($_POST['action'] ?? '') === 'save_master') {
    $original_slug = trim((string) ($_POST['original_slug'] ?? ''));
    $slug = master_slugify((string) ($_POST['slug'] ?? ''));
    $master = [
        'status' => in_array($_POST['status'] ?? 'draft', ['draft', 'published'], true) ? $_POST['status'] : 'draft',
        'slug' => $slug,
        'name' => trim((string) ($_POST['name'] ?? '')),
        'company_name' => trim((string) ($_POST['company_name'] ?? '')),
        'prefecture' => trim((string) ($_POST['prefecture'] ?? '')),
        'city' => trim((string) ($_POST['city'] ?? '')),
        'map_x' => trim((string) ($_POST['map_x'] ?? '')),
        'map_y' => trim((string) ($_POST['map_y'] ?? '')),
        'certified_at' => preg_match('/^\d{4}-\d{2}-\d{2}$/', $_POST['certified_at'] ?? '') ? $_POST['certified_at'] : date('Y-m-d'),
        'focus' => trim((string) ($_POST['focus'] ?? '')),
        'profile' => trim((string) ($_POST['profile'] ?? '')),
        'profile_image' => '',
        'link_url' => trim((string) ($_POST['link_url'] ?? '')),
    ];

    $existing_master = $original_slug !== '' ? find_master($masters, $original_slug) : null;
    $master['profile_image'] = isset($_POST['delete_profile_image']) ? '' : (string) ($existing_master['profile_image'] ?? '');
    $master['latitude'] = $master['map_x'];
    $master['longitude'] = $master['map_y'];

    foreach (['name' => '名前', 'prefecture' => '都道府県', 'latitude' => 'X座標', 'longitude' => 'Y座標'] as $key => $label) {
        if ($master[$key] === '') {
            $errors[] = $label . 'を入力してください。';
        }
    }

    if ($master['latitude'] !== '' && !is_numeric($master['latitude'])) {
        $errors[] = 'X座標は数値で入力してください。';
    }
    if ($master['longitude'] !== '' && !is_numeric($master['longitude'])) {
        $errors[] = 'Y座標は数値で入力してください。';
    }
    if (is_numeric($master['latitude']) && ((float) $master['latitude'] < 0 || (float) $master['latitude'] > 100)) {
        $errors[] = 'X座標は0〜100の範囲で入力してください。';
    }
    if (is_numeric($master['longitude']) && ((float) $master['longitude'] < 0 || (float) $master['longitude'] > 100)) {
        $errors[] = 'Y座標は0〜100の範囲で入力してください。';
    }

    unset($master['latitude'], $master['longitude']);

    foreach ($masters as $existing) {
        if (($existing['slug'] ?? '') === $slug && ($existing['slug'] ?? '') !== $original_slug) {
            $errors[] = '同じスラッグの改善マスターがすでにあります。';
            break;
        }
    }

    $profile_image = master_save_profile_image('profile_image', $slug, $errors);
    if ($profile_image !== '') {
        $master['profile_image'] = $profile_image;
    }

    if (empty($errors)) {
        $saved = false;
        foreach ($masters as $index => $existing) {
            if (($existing['slug'] ?? '') === $original_slug) {
                $masters[$index] = $master;
                $saved = true;
                break;
            }
        }

        if (!$saved) {
            $masters[] = $master;
        }

        if (save_masters($data_file, $masters)) {
            header('Location: admin-masters.php?message=saved&edit=' . rawurlencode($slug));
            exit;
        }

        $errors[] = '保存できませんでした。data/improvement_masters.php の書き込み権限を確認してください。';
    }
}

if (($_POST['action'] ?? '') === 'delete_master') {
    $delete_slug = trim((string) ($_POST['delete_slug'] ?? ''));
    $masters = array_values(array_filter($masters, function ($master) use ($delete_slug) {
        return ($master['slug'] ?? '') !== $delete_slug;
    }));

    if (save_masters($data_file, $masters)) {
        header('Location: admin-masters.php?message=deleted');
        exit;
    }

    $errors[] = '削除できませんでした。';
}

if (($_GET['message'] ?? '') === 'saved') {
    $message = '改善マスターを保存しました。';
} elseif (($_GET['message'] ?? '') === 'deleted') {
    $message = '改善マスターを削除しました。';
}

$edit_slug = trim((string) ($_GET['edit'] ?? ''));
$editing_master = $edit_slug !== '' ? find_master($masters, $edit_slug) : null;
$form_master = array_merge($default_master, $editing_master ?? []);

usort($masters, function ($a, $b) {
    return strcmp($b['certified_at'] ?? '', $a['certified_at'] ?? '');
});

$page_title = '改善マスター管理';
$page_description = 'ライズゲート改善マスター管理';
include __DIR__ . '/include/head.php';
?>
<body class="admin-body">
  <main class="admin-shell">
    <header class="admin-header">
      <div>
        <p class="section-label">Rise Gate Admin</p>
        <h1>改善マスター管理</h1>
        <p>ライズゲートのプログラムを受講し、各地域で改善を進める人たちを登録します。</p>
      </div>
      <div class="admin-header__links">
        <a class="button button--secondary" href="admin.php">管理トップ</a>
        <a class="button button--secondary" href="future.php">Futureページ</a>
        <form method="post">
          <button class="button button--secondary" type="submit" name="admin_logout" value="1">ログアウト</button>
        </form>
      </div>
    </header>

    <?php if ($message !== '') : ?>
      <p class="admin-alert admin-alert--success"><?php echo e($message); ?></p>
    <?php endif; ?>
    <?php foreach ($errors as $error) : ?>
      <p class="admin-alert admin-alert--error"><?php echo e($error); ?></p>
    <?php endforeach; ?>

    <div class="admin-layout">
      <section class="admin-panel">
        <div class="admin-panel__head">
          <h2><?php echo $editing_master ? '改善マスターを編集' : '改善マスターを登録'; ?></h2>
          <?php if ($editing_master) : ?>
            <a class="text-link" href="admin-masters.php">新規登録に戻る</a>
          <?php endif; ?>
        </div>

        <form method="post" class="admin-form" enctype="multipart/form-data">
          <input type="hidden" name="action" value="save_master">
          <input type="hidden" name="original_slug" value="<?php echo e($editing_master['slug'] ?? ''); ?>">

          <div class="admin-form__grid">
            <label>
              <span>公開状態</span>
              <select name="status">
                <option value="draft"<?php echo $form_master['status'] === 'draft' ? ' selected' : ''; ?>>下書き</option>
                <option value="published"<?php echo $form_master['status'] === 'published' ? ' selected' : ''; ?>>公開</option>
              </select>
            </label>

            <label>
              <span>認定日</span>
              <input type="date" name="certified_at" value="<?php echo e($form_master['certified_at']); ?>">
            </label>
          </div>

          <label>
            <span>名前</span>
            <input type="text" name="name" value="<?php echo e($form_master['name']); ?>" required>
          </label>

          <label>
            <span>会社名</span>
            <input type="text" name="company_name" value="<?php echo e($form_master['company_name']); ?>" placeholder="株式会社ライズゲート">
          </label>

          <label>
            <span>写真</span>
            <input type="file" name="profile_image" accept="image/jpeg,image/png,image/webp">
          </label>

          <?php if (($form_master['profile_image'] ?? '') !== '') : ?>
            <figure class="admin-profile-image-preview">
              <img src="<?php echo e($form_master['profile_image']); ?>" alt="">
              <figcaption>
                登録済み
                <label>
                  <input type="checkbox" name="delete_profile_image" value="1">
                  削除する
                </label>
              </figcaption>
            </figure>
          <?php endif; ?>

          <div class="admin-form__grid">
            <label>
              <span>スラッグ</span>
              <input type="text" name="slug" value="<?php echo e($form_master['slug']); ?>" placeholder="osaka-yamada">
            </label>

            <label>
              <span>得意な改善領域</span>
              <input type="text" name="focus" value="<?php echo e($form_master['focus']); ?>" placeholder="現場改善 / 情報発信 / 業務整理">
            </label>
          </div>

          <div class="admin-form__grid">
            <label>
              <span>都道府県</span>
              <input type="text" name="prefecture" value="<?php echo e($form_master['prefecture']); ?>" placeholder="大阪府" required>
            </label>

            <label>
              <span>市区町村</span>
              <input type="text" name="city" value="<?php echo e($form_master['city']); ?>" placeholder="大阪市">
            </label>
          </div>

          <div class="admin-form__grid">
            <label>
              <span>X座標（0〜100）</span>
              <input type="number" name="map_x" value="<?php echo e((string) ($form_master['map_x'] ?? $form_master['latitude'] ?? '')); ?>" min="0" max="100" step="0.1" placeholder="42" required>
            </label>

            <label>
              <span>Y座標（0〜100）</span>
              <input type="number" name="map_y" value="<?php echo e((string) ($form_master['map_y'] ?? $form_master['longitude'] ?? '')); ?>" min="0" max="100" step="0.1" placeholder="58" required>
            </label>
          </div>

          <label>
            <span>紹介文</span>
            <textarea name="profile" rows="4" placeholder="地域でどんな改善に取り組む人かを書きます。"><?php echo e($form_master['profile']); ?></textarea>
          </label>

          <label>
            <span>関連URL</span>
            <input type="url" name="link_url" value="<?php echo e($form_master['link_url']); ?>" placeholder="https://">
          </label>

          <button class="button button--primary" type="submit">保存する</button>
        </form>
      </section>

      <aside class="admin-panel">
        <div class="admin-panel__head">
          <h2>登録済み改善マスター</h2>
          <span><?php echo e((string) count($masters)); ?>人</span>
        </div>

        <div class="admin-work-list">
          <?php if (empty($masters)) : ?>
            <p>登録済みの改善マスターはまだありません。</p>
          <?php endif; ?>

          <?php foreach ($masters as $master) : ?>
            <article class="admin-work-item">
              <?php if (($master['profile_image'] ?? '') !== '') : ?>
                <img class="admin-master-thumb" src="<?php echo e($master['profile_image']); ?>" alt="">
              <?php endif; ?>
              <div>
                <p class="admin-work-item__meta"><?php echo e(($master['status'] ?? '') === 'published' ? '公開' : '下書き'); ?> / <?php echo e($master['certified_at'] ?? ''); ?></p>
                <h3><?php echo e($master['name'] ?? ''); ?></h3>
                <?php if (($master['company_name'] ?? '') !== '') : ?>
                  <p><?php echo e($master['company_name']); ?></p>
                <?php endif; ?>
                <p><?php echo e(trim(($master['prefecture'] ?? '') . ' ' . ($master['city'] ?? ''))); ?></p>
              </div>
              <div class="admin-work-item__actions">
                <a class="admin-action-button" href="admin-masters.php?edit=<?php echo e($master['slug']); ?>">編集</a>
                <form method="post" onsubmit="return confirm('この改善マスターを削除しますか？');">
                  <input type="hidden" name="action" value="delete_master">
                  <input type="hidden" name="delete_slug" value="<?php echo e($master['slug']); ?>">
                  <button class="admin-action-button admin-action-button--delete" type="submit">削除</button>
                </form>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      </aside>
    </div>
  </main>
</body>
</html>
