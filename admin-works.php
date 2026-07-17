<?php
$site = require __DIR__ . '/data/site.php';
require __DIR__ . '/include/functions.php';

$data_file = __DIR__ . '/data/works.php';
$masters_file = __DIR__ . '/data/improvement_masters.php';
$works = file_exists($data_file) ? require $data_file : [];
$masters = file_exists($masters_file) ? require $masters_file : [];
$admin_password = getenv('RISEGATE_ADMIN_PASSWORD') ?: '';
$errors = [];
$message = '';
const ADMIN_GALLERY_LIMIT = 10;
const ADMIN_WORK_TYPES = [
    'website' => 'Webサイト制作',
    'system' => 'システム導入',
];
const ADMIN_WORK_MEMBER_LIMIT = 5;

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
        header('Location: admin-works.php');
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
          <p class="section-label">Rise Gate Admin</p>
          <h1>管理画面</h1>
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

function admin_slugify(string $value): string
{
    $slug = strtolower(trim($value));
    $slug = preg_replace('/[^a-z0-9-]+/', '-', $slug);
    $slug = trim((string) $slug, '-');

    return $slug !== '' ? $slug : 'work-' . date('Ymd-His');
}

function admin_split_tags(string $value): array
{
    $tags = preg_split('/[\r\n,]+/u', $value) ?: [];
    $tags = array_map('trim', $tags);

    return array_values(array_filter($tags, function ($tag) {
        return $tag !== '';
    }));
}

function admin_work_type_label(string $type): string
{
    return ADMIN_WORK_TYPES[$type] ?? ADMIN_WORK_TYPES['website'];
}

function admin_image_max_width(string $field): int
{
    return $field === 'mobile_screenshot' ? 900 : 1600;
}

function admin_create_image_resource(string $path, int $image_type)
{
    return match ($image_type) {
        IMAGETYPE_JPEG => function_exists('imagecreatefromjpeg') ? imagecreatefromjpeg($path) : false,
        IMAGETYPE_PNG => function_exists('imagecreatefrompng') ? imagecreatefrompng($path) : false,
        IMAGETYPE_WEBP => function_exists('imagecreatefromwebp') ? imagecreatefromwebp($path) : false,
        default => false,
    };
}

function admin_save_optimized_work_image(string $tmp_name, string $destination, int $image_type, int $max_width): bool
{
    if (!function_exists('imagewebp')) {
        return false;
    }

    $image_info = getimagesize($tmp_name);
    if ($image_info === false) {
        return false;
    }

    [$width, $height] = $image_info;
    $scale = min(1, $max_width / max(1, $width));
    $new_width = max(1, (int) round($width * $scale));
    $new_height = max(1, (int) round($height * $scale));

    $source_image = admin_create_image_resource($tmp_name, $image_type);
    if (!$source_image) {
        return false;
    }

    imagepalettetotruecolor($source_image);
    imagealphablending($source_image, true);
    imagesavealpha($source_image, true);

    $resized_image = imagecreatetruecolor($new_width, $new_height);
    imagealphablending($resized_image, false);
    imagesavealpha($resized_image, true);

    imagecopyresampled(
        $resized_image,
        $source_image,
        0,
        0,
        0,
        0,
        $new_width,
        $new_height,
        $width,
        $height
    );

    $saved = imagewebp($resized_image, $destination, 82);

    imagedestroy($source_image);
    imagedestroy($resized_image);

    return $saved;
}

function admin_save_works(string $data_file, array $works): bool
{
    $export = var_export(array_values($works), true);
    $content = "<?php\nreturn " . $export . ";\n";

    return file_put_contents($data_file, $content, LOCK_EX) !== false;
}

function admin_handle_work_image(string $field, string $slug, string $label, array &$errors): string
{
    if (!isset($_FILES[$field]) || ($_FILES[$field]['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return '';
    }

    if ($_FILES[$field]['error'] !== UPLOAD_ERR_OK) {
        $errors[] = $label . 'のアップロードに失敗しました。';
        return '';
    }

    $tmp_name = (string) $_FILES[$field]['tmp_name'];
    $original_name = (string) $_FILES[$field]['name'];
    $extension = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp'];

    if (!in_array($extension, $allowed_extensions, true)) {
        $errors[] = $label . 'は jpg / png / webp のいずれかで登録してください。';
        return '';
    }

    $image_info = @getimagesize($tmp_name);
    if ($image_info === false) {
        $errors[] = $label . 'は画像ファイルとして確認できませんでした。';
        return '';
    }

    $image_type = (int) ($image_info[2] ?? 0);
    $can_optimize = in_array($image_type, [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_WEBP], true)
        && function_exists('imagecreatetruecolor')
        && function_exists('imagecopyresampled');

    $upload_subdir = $can_optimize && function_exists('imagewebp') ? 'image/works/optimized' : 'image/works';
    $upload_dir = __DIR__ . '/' . $upload_subdir;
    if (!is_dir($upload_dir) && !mkdir($upload_dir, 0775, true)) {
        $errors[] = '画像保存用ディレクトリを作成できませんでした。';
        return '';
    }

    $filename_base = $slug . '-' . $field . '-' . date('YmdHis');
    $filename = $filename_base . '.' . ($upload_subdir === 'image/works/optimized' ? 'webp' : $extension);
    $destination = $upload_dir . '/' . $filename;

    $saved = false;
    if ($upload_subdir === 'image/works/optimized') {
        $saved = admin_save_optimized_work_image($tmp_name, $destination, $image_type, admin_image_max_width($field));
    }

    if (!$saved) {
        $upload_subdir = 'image/works';
        $upload_dir = __DIR__ . '/' . $upload_subdir;
        if (!is_dir($upload_dir) && !mkdir($upload_dir, 0775, true)) {
            $errors[] = '画像保存用ディレクトリを作成できませんでした。';
            return '';
        }

        $filename = $filename_base . '.' . $extension;
        $destination = $upload_dir . '/' . $filename;
        if (!move_uploaded_file($tmp_name, $destination)) {
            $errors[] = $label . 'を保存できませんでした。';
            return '';
        }
    }

    return $upload_subdir . '/' . $filename;
}

function admin_find_work(array $works, string $slug): ?array
{
    foreach ($works as $work) {
        if (($work['slug'] ?? '') === $slug) {
            return $work;
        }
    }

    return null;
}

function admin_find_master(array $masters, string $slug): ?array
{
    foreach ($masters as $master) {
        if (($master['slug'] ?? '') === $slug) {
            return $master;
        }
    }

    return null;
}

function admin_normalize_work_members(array $work, array $masters): array
{
    $members = [];
    $source_members = is_array($work['members'] ?? null) ? $work['members'] : [];

    foreach ($source_members as $member) {
        if (!is_array($member)) {
            continue;
        }

        $master_slug = trim((string) ($member['master_slug'] ?? ''));
        $master = $master_slug !== '' ? admin_find_master($masters, $master_slug) : null;
        $name = trim((string) ($master['name'] ?? $member['name'] ?? ''));
        $role = trim((string) ($member['role'] ?? ''));
        $note = trim((string) ($member['note'] ?? ''));

        if ($master_slug === '' && $name === '' && $role === '' && $note === '') {
            continue;
        }

        $members[] = [
            'master_slug' => $master_slug,
            'name' => $name,
            'role' => $role,
            'note' => $note,
        ];
    }

    if (empty($members) && trim((string) ($work['master_slug'] ?? '')) !== '') {
        $master_slug = trim((string) ($work['master_slug'] ?? ''));
        $master = admin_find_master($masters, $master_slug);
        $members[] = [
            'master_slug' => $master_slug,
            'name' => trim((string) ($master['name'] ?? $work['master_name'] ?? '')),
            'role' => '',
            'note' => '',
        ];
    }

    return array_slice(array_values($members), 0, ADMIN_WORK_MEMBER_LIMIT);
}

function admin_collect_work_members(array $post, array $masters): array
{
    $members = [];
    $master_slugs = (array) ($post['member_master_slug'] ?? []);
    $roles = (array) ($post['member_role'] ?? []);
    $notes = (array) ($post['member_note'] ?? []);

    for ($index = 0; $index < ADMIN_WORK_MEMBER_LIMIT; $index++) {
        $master_slug = trim((string) ($master_slugs[$index] ?? ''));
        $role = trim((string) ($roles[$index] ?? ''));
        $note = trim((string) ($notes[$index] ?? ''));

        if ($master_slug === '' && $role === '' && $note === '') {
            continue;
        }

        $master = $master_slug !== '' ? admin_find_master($masters, $master_slug) : null;
        $members[] = [
            'master_slug' => $master ? (string) ($master['slug'] ?? '') : '',
            'name' => $master ? (string) ($master['name'] ?? '') : '',
            'role' => $role,
            'note' => $note,
        ];
    }

    return $members;
}

function admin_default_gallery(): array
{
    return array_fill(0, ADMIN_GALLERY_LIMIT, [
        'title' => '',
        'description' => '',
        'image' => '',
    ]);
}

function admin_normalize_gallery(array $gallery): array
{
    $normalized = admin_default_gallery();

    foreach (array_values($gallery) as $index => $item) {
        if ($index >= ADMIN_GALLERY_LIMIT) {
            break;
        }

        $normalized[$index] = array_merge($normalized[$index], [
            'title' => (string) ($item['title'] ?? ''),
            'description' => (string) ($item['description'] ?? ''),
            'image' => (string) ($item['image'] ?? ''),
        ]);
    }

    return $normalized;
}

$default_work = [
    'status' => 'draft',
    'type' => 'website',
    'type_label' => 'サイト制作',
    'slug' => '',
    'title' => '',
    'client_name' => '',
    'os_project_id' => '',
    'members' => [],
    'published_at' => date('Y-m-d'),
    'summary' => '',
    'challenge' => '',
    'challenge_excerpt' => '',
    'improvement' => '',
    'improvement_excerpt' => '',
    'result' => '',
    'role' => '',
    'site_url' => '',
    'screenshots' => [
        'desktop' => '',
        'mobile' => '',
    ],
    'gallery' => admin_default_gallery(),
    'tags' => [],
];

if (($_POST['action'] ?? '') === 'save') {
    $original_slug = trim((string) ($_POST['original_slug'] ?? ''));
    $slug = admin_slugify((string) ($_POST['slug'] ?? ''));
    $existing_work = $original_slug !== '' ? admin_find_work($works, $original_slug) : null;
    $existing_screenshots = array_merge(
        ['desktop' => '', 'mobile' => ''],
        $existing_work['screenshots'] ?? []
    );
    $existing_gallery = admin_normalize_gallery($existing_work['gallery'] ?? []);
    $work = [
        'status' => in_array($_POST['status'] ?? 'draft', ['draft', 'published'], true) ? $_POST['status'] : 'draft',
        'type' => array_key_exists($_POST['type'] ?? 'website', ADMIN_WORK_TYPES) ? (string) $_POST['type'] : 'website',
        'type_label' => admin_work_type_label((string) ($_POST['type'] ?? 'website')),
        'slug' => $slug,
        'title' => trim((string) ($_POST['title'] ?? '')),
        'client_name' => trim((string) ($_POST['client_name'] ?? '')),
        'os_project_id' => trim((string) ($_POST['os_project_id'] ?? '')),
        'members' => admin_collect_work_members($_POST, $masters),
        'published_at' => preg_match('/^\d{4}-\d{2}-\d{2}$/', $_POST['published_at'] ?? '') ? $_POST['published_at'] : date('Y-m-d'),
        'summary' => trim((string) ($_POST['summary'] ?? '')),
        'challenge' => trim((string) ($_POST['challenge'] ?? '')),
        'challenge_excerpt' => trim((string) ($_POST['challenge_excerpt'] ?? '')),
        'improvement' => trim((string) ($_POST['improvement'] ?? '')),
        'improvement_excerpt' => trim((string) ($_POST['improvement_excerpt'] ?? '')),
        'result' => trim((string) ($_POST['result'] ?? '')),
        'role' => trim((string) ($_POST['role'] ?? '')),
        'site_url' => trim((string) ($_POST['site_url'] ?? '')),
        'screenshots' => [
            'desktop' => isset($_POST['delete_desktop_screenshot']) ? '' : $existing_screenshots['desktop'],
            'mobile' => isset($_POST['delete_mobile_screenshot']) ? '' : $existing_screenshots['mobile'],
        ],
        'gallery' => [],
        'tags' => admin_split_tags((string) ($_POST['tags'] ?? '')),
    ];

    foreach (['title' => 'タイトル', 'summary' => '概要', 'challenge' => '課題', 'improvement' => '改善'] as $key => $label) {
        if ($work[$key] === '') {
            $errors[] = $label . 'を入力してください。';
        }
    }

    foreach ($works as $existing) {
        if (($existing['slug'] ?? '') === $slug && ($existing['slug'] ?? '') !== $original_slug) {
            $errors[] = '同じスラッグの実績がすでにあります。';
            break;
        }
    }

    $desktop_screenshot = admin_handle_work_image('desktop_screenshot', $slug, 'パソコン画面', $errors);
    if ($desktop_screenshot !== '') {
        $work['screenshots']['desktop'] = $desktop_screenshot;
    }

    $mobile_screenshot = admin_handle_work_image('mobile_screenshot', $slug, 'スマホ画面', $errors);
    if ($mobile_screenshot !== '') {
        $work['screenshots']['mobile'] = $mobile_screenshot;
    }

    $gallery_order = (array) ($_POST['gallery_order'] ?? range(0, ADMIN_GALLERY_LIMIT - 1));
    $used_gallery_indexes = [];

    foreach ($gallery_order as $gallery_index) {
        $index = (int) $gallery_index;
        if ($index < 0 || $index >= ADMIN_GALLERY_LIMIT || in_array($index, $used_gallery_indexes, true)) {
            continue;
        }
        $used_gallery_indexes[] = $index;

        $gallery_item = [
            'title' => trim((string) ($_POST['gallery_title'][$index] ?? '')),
            'description' => trim((string) ($_POST['gallery_description'][$index] ?? '')),
            'image' => isset($_POST['delete_gallery_image'][$index]) ? '' : $existing_gallery[$index]['image'],
        ];

        $gallery_image = admin_handle_work_image('gallery_image_' . $index, $slug, '画面紹介画像' . ($index + 1), $errors);
        if ($gallery_image !== '') {
            $gallery_item['image'] = $gallery_image;
        }

        if ($gallery_item['title'] !== '' || $gallery_item['description'] !== '' || $gallery_item['image'] !== '') {
            $work['gallery'][] = $gallery_item;
        }
    }

    if (empty($errors)) {
        $saved = false;
        foreach ($works as $index => $existing) {
            if (($existing['slug'] ?? '') === $original_slug) {
                $works[$index] = $work;
                $saved = true;
                break;
            }
        }

        if (!$saved) {
            $works[] = $work;
        }

        if (admin_save_works($data_file, $works)) {
            header('Location: admin-works.php?message=saved&edit=' . rawurlencode($slug));
            exit;
        }

        $errors[] = '保存できませんでした。data/works.php の書き込み権限を確認してください。';
    }
}

if (($_POST['action'] ?? '') === 'delete') {
    $delete_slug = trim((string) ($_POST['delete_slug'] ?? ''));
    $works = array_values(array_filter($works, function ($work) use ($delete_slug) {
        return ($work['slug'] ?? '') !== $delete_slug;
    }));

    if (admin_save_works($data_file, $works)) {
        header('Location: admin-works.php?message=deleted');
        exit;
    }

    $errors[] = '削除できませんでした。';
}

if (($_GET['message'] ?? '') === 'saved') {
    $message = '実績を保存しました。';
} elseif (($_GET['message'] ?? '') === 'deleted') {
    $message = '実績を削除しました。';
}

$edit_slug = trim((string) ($_GET['edit'] ?? ''));
$editing_work = $edit_slug !== '' ? admin_find_work($works, $edit_slug) : null;
$form_work = array_merge($default_work, $editing_work ?? []);
$form_work['members'] = admin_normalize_work_members($form_work, $masters);
$form_members = array_pad($form_work['members'], ADMIN_WORK_MEMBER_LIMIT, [
    'master_slug' => '',
    'name' => '',
    'role' => '',
    'note' => '',
]);
$form_gallery = admin_normalize_gallery($form_work['gallery'] ?? []);

usort($masters, function ($a, $b) {
    return strcmp($a['name'] ?? '', $b['name'] ?? '');
});

usort($works, function ($a, $b) {
    return strcmp($b['published_at'] ?? '', $a['published_at'] ?? '');
});

$page_title = '実績管理';
$page_description = 'ライズゲート実績管理';
include __DIR__ . '/include/head.php';
?>
<body class="admin-body">
  <main class="admin-shell">
    <header class="admin-header">
      <div>
        <p class="section-label">Rise Gate Admin</p>
        <h1>実績管理</h1>
        <p>Webサイト制作とシステム導入の改善実績を登録します。実績は「課題」と「改善」が伝わる形で公開されます。</p>
      </div>
      <div class="admin-header__links">
        <a class="button button--secondary" href="admin.php">管理トップ</a>
        <a class="button button--secondary" href="works.php">実績ページ</a>
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
          <h2><?php echo $editing_work ? '実績を編集' : '実績を登録'; ?></h2>
          <?php if ($editing_work) : ?>
            <a class="text-link" href="admin-works.php">新規登録に戻る</a>
          <?php endif; ?>
        </div>

        <form method="post" class="admin-form" enctype="multipart/form-data">
          <input type="hidden" name="action" value="save">
          <input type="hidden" name="original_slug" value="<?php echo e($editing_work['slug'] ?? ''); ?>">

          <div class="admin-form__grid">
            <label>
              <span>公開状態</span>
              <select name="status">
                <option value="draft"<?php echo $form_work['status'] === 'draft' ? ' selected' : ''; ?>>下書き</option>
                <option value="published"<?php echo $form_work['status'] === 'published' ? ' selected' : ''; ?>>公開</option>
              </select>
            </label>

            <label>
              <span>公開日</span>
              <input type="date" name="published_at" value="<?php echo e($form_work['published_at']); ?>">
            </label>
          </div>

          <label>
            <span>実績種別</span>
            <select name="type">
              <?php foreach (ADMIN_WORK_TYPES as $type_value => $type_label) : ?>
                <option value="<?php echo e($type_value); ?>"<?php echo ($form_work['type'] ?? 'website') === $type_value ? ' selected' : ''; ?>><?php echo e($type_label); ?></option>
              <?php endforeach; ?>
            </select>
          </label>

          <label>
            <span>タイトル</span>
            <input type="text" name="title" value="<?php echo e($form_work['title']); ?>" required>
          </label>

          <div class="admin-form__grid">
            <label>
              <span>スラッグ</span>
              <input type="text" name="slug" value="<?php echo e($form_work['slug']); ?>" placeholder="sample-website-renewal">
            </label>

            <label>
              <span>会社名・案件名</span>
              <input type="text" name="client_name" value="<?php echo e($form_work['client_name']); ?>">
            </label>
          </div>

          <label>
            <span>RISE GATE OS プロジェクトID</span>
            <input type="text" name="os_project_id" value="<?php echo e($form_work['os_project_id'] ?? ''); ?>" placeholder="project-xxxx">
          </label>

          <section class="admin-member-fields">
            <div class="admin-subheading">
              <h3>担当メンバー</h3>
              <p>改善マスターごとに、この実績での役割を登録します。最大<?php echo e((string) ADMIN_WORK_MEMBER_LIMIT); ?>名まで設定できます。</p>
            </div>

            <?php foreach ($form_members as $index => $member) : ?>
              <div class="admin-member-field">
                <p class="admin-gallery-field__number">担当 <?php echo e((string) ($index + 1)); ?></p>
                <label>
                  <span>改善マスター</span>
                  <select name="member_master_slug[<?php echo e((string) $index); ?>]">
                    <option value="">指定なし</option>
                    <?php foreach ($masters as $master) : ?>
                      <?php
                      $master_slug = (string) ($master['slug'] ?? '');
                      $master_label = trim((string) ($master['name'] ?? '') . (($master['company_name'] ?? '') !== '' ? ' / ' . (string) $master['company_name'] : ''));
                      ?>
                      <option value="<?php echo e($master_slug); ?>"<?php echo ($member['master_slug'] ?? '') === $master_slug ? ' selected' : ''; ?>>
                        <?php echo e($master_label !== '' ? $master_label : $master_slug); ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </label>
                <label>
                  <span>役割</span>
                  <input type="text" name="member_role[<?php echo e((string) $index); ?>]" value="<?php echo e($member['role'] ?? ''); ?>" placeholder="改善設計 / 実装 / 取材 / デザイン">
                </label>
                <label>
                  <span>補足</span>
                  <textarea name="member_note[<?php echo e((string) $index); ?>]" rows="2" placeholder="この実績で担当したことを短く書きます。"><?php echo e($member['note'] ?? ''); ?></textarea>
                </label>
              </div>
            <?php endforeach; ?>
          </section>

          <label>
            <span>概要</span>
            <textarea name="summary" rows="3" required><?php echo e($form_work['summary']); ?></textarea>
          </label>

          <label>
            <span>課題：何を変えたかったか</span>
            <textarea name="challenge" rows="5" required><?php echo e($form_work['challenge']); ?></textarea>
          </label>

          <label>
            <span>一覧用の課題</span>
            <textarea name="challenge_excerpt" rows="2" placeholder="一覧カードではここまで見せたい、という短い課題文"><?php echo e($form_work['challenge_excerpt'] ?? ''); ?></textarea>
          </label>

          <label>
            <span>改善：どのような視点で計画したか</span>
            <textarea name="improvement" rows="5" required><?php echo e($form_work['improvement']); ?></textarea>
          </label>

          <label>
            <span>一覧用の改善</span>
            <textarea name="improvement_excerpt" rows="2" placeholder="一覧カードではここまで見せたい、という短い改善文"><?php echo e($form_work['improvement_excerpt'] ?? ''); ?></textarea>
          </label>

          <label>
            <span>改善の結果</span>
            <textarea name="result" rows="4"><?php echo e($form_work['result']); ?></textarea>
          </label>

          <label>
            <span>担当したこと</span>
            <textarea name="role" rows="3"><?php echo e($form_work['role']); ?></textarea>
          </label>

          <div class="admin-screenshot-fields">
            <div class="admin-screenshot-field">
              <label>
                <span>パソコン画面のスクショ</span>
                <input type="file" name="desktop_screenshot" accept="image/jpeg,image/png,image/webp" data-crop-aspect="1.777777778" data-crop-label="16:9">
              </label>
              <?php if (($form_work['screenshots']['desktop'] ?? '') !== '') : ?>
                <figure>
                  <img src="<?php echo e($form_work['screenshots']['desktop']); ?>" alt="">
                  <figcaption>
                    登録済み
                    <label>
                      <input type="checkbox" name="delete_desktop_screenshot" value="1">
                      削除する
                    </label>
                  </figcaption>
                </figure>
              <?php endif; ?>
            </div>

            <div class="admin-screenshot-field">
              <label>
                <span>スマホ画面のスクショ</span>
                <input type="file" name="mobile_screenshot" accept="image/jpeg,image/png,image/webp" data-crop-aspect="0.462085308" data-crop-label="1170:2532">
              </label>
              <?php if (($form_work['screenshots']['mobile'] ?? '') !== '') : ?>
                <figure>
                  <img src="<?php echo e($form_work['screenshots']['mobile']); ?>" alt="">
                  <figcaption>
                    登録済み
                    <label>
                      <input type="checkbox" name="delete_mobile_screenshot" value="1">
                      削除する
                    </label>
                  </figcaption>
                </figure>
              <?php endif; ?>
            </div>
          </div>

          <section class="admin-gallery-fields">
            <div class="admin-subheading">
              <h3>画面紹介</h3>
              <p>実績詳細で横並び表示する画像です。公開サイト、管理画面、会員専用画面、業務画面など最大10枚まで登録できます。</p>
            </div>

            <?php foreach ($form_gallery as $index => $gallery_item) : ?>
              <div class="admin-gallery-field">
                <input type="hidden" name="gallery_order[]" value="<?php echo e((string) $index); ?>">
                <div class="admin-gallery-field__head">
                  <p class="admin-gallery-field__number">画面 <?php echo e((string) ($index + 1)); ?></p>
                  <div class="admin-gallery-field__actions">
                    <button type="button" class="admin-mini-button" data-gallery-move="up">上へ</button>
                    <button type="button" class="admin-mini-button" data-gallery-move="down">下へ</button>
                  </div>
                </div>
                <label>
                  <span>画像</span>
                  <input type="file" name="gallery_image_<?php echo e((string) $index); ?>" accept="image/jpeg,image/png,image/webp" data-crop-aspect="1.6" data-crop-label="16:10">
                </label>

                <?php if (($gallery_item['image'] ?? '') !== '') : ?>
                  <figure>
                    <img src="<?php echo e($gallery_item['image']); ?>" alt="">
                    <figcaption>
                      登録済み
                      <label>
                        <input type="checkbox" name="delete_gallery_image[<?php echo e((string) $index); ?>]" value="1">
                        削除する
                      </label>
                    </figcaption>
                  </figure>
                <?php endif; ?>

                <label>
                  <span>画面名</span>
                  <input type="text" name="gallery_title[<?php echo e((string) $index); ?>]" value="<?php echo e($gallery_item['title'] ?? ''); ?>" placeholder="公開サイト / 管理画面 / 会員専用画面 / 業務画面">
                </label>

                <label>
                  <span>説明</span>
                  <textarea name="gallery_description[<?php echo e((string) $index); ?>]" rows="3" placeholder="この画面で何ができるか、何を見せたいかを短く記載します。"><?php echo e($gallery_item['description'] ?? ''); ?></textarea>
                </label>
              </div>
            <?php endforeach; ?>
          </section>

          <div class="admin-form__grid">
            <label>
              <span>関連URL</span>
              <input type="url" name="site_url" value="<?php echo e($form_work['site_url']); ?>">
            </label>

            <label>
              <span>タグ</span>
              <input type="text" name="tags" value="<?php echo e(implode(', ', $form_work['tags'])); ?>" placeholder="採用, 更新しやすさ">
            </label>
          </div>

          <button class="button button--primary" type="submit">保存する</button>
        </form>
      </section>

      <aside class="admin-panel">
        <div class="admin-panel__head">
          <h2>登録済み実績</h2>
          <span><?php echo e((string) count($works)); ?>件</span>
        </div>

        <div class="admin-work-list">
          <?php if (empty($works)) : ?>
            <p>登録済みの実績はまだありません。</p>
          <?php endif; ?>

          <?php foreach ($works as $work) : ?>
            <article class="admin-work-item">
              <div>
                <p class="admin-work-item__meta"><?php echo e($work['status'] === 'published' ? '公開' : '下書き'); ?> / <?php echo e($work['published_at']); ?> / <?php echo e($work['type_label'] ?? admin_work_type_label((string) ($work['type'] ?? 'website'))); ?></p>
                <h3><?php echo e($work['title']); ?></h3>
                <?php $work_members = admin_normalize_work_members($work, $masters); ?>
                <?php if (!empty($work_members)) : ?>
                  <p class="admin-work-item__meta">
                    担当：<?php echo e(implode(' / ', array_map(function ($member) {
                        $name = (string) ($member['name'] ?? '');
                        $role = (string) ($member['role'] ?? '');
                        return $role !== '' ? $name . '（' . $role . '）' : $name;
                    }, $work_members))); ?>
                  </p>
                <?php endif; ?>
                <p><?php echo e($work['summary']); ?></p>
              </div>
              <div class="admin-work-item__actions">
                <a class="admin-action-button" href="admin-works.php?edit=<?php echo e($work['slug']); ?>">編集</a>
                <?php if ($work['status'] === 'published') : ?>
                  <a class="admin-action-button admin-action-button--view" href="work-detail.php?slug=<?php echo e($work['slug']); ?>" target="_blank" rel="noopener">表示</a>
                <?php endif; ?>
                <form method="post" onsubmit="return confirm('この実績を削除しますか？');">
                  <input type="hidden" name="action" value="delete">
                  <input type="hidden" name="delete_slug" value="<?php echo e($work['slug']); ?>">
                  <button class="admin-action-button admin-action-button--delete" type="submit">削除</button>
                </form>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      </aside>
    </div>
  </main>
  <script>
    (() => {
      const cropInputs = document.querySelectorAll('input[type="file"][data-crop-aspect]');

      const createPreview = (input, src, label) => {
        const field = input.closest('.admin-screenshot-field, .admin-gallery-field');
        if (!field) {
          return;
        }

        field.querySelector('.admin-crop-preview')?.remove();

        const figure = document.createElement('figure');
        figure.className = 'admin-crop-preview';
        figure.innerHTML = `
          <img src="${src}" alt="">
          <figcaption>
            <span>選択中の画像を ${label} にトリミングしました</span>
          </figcaption>
        `;

        input.closest('label')?.insertAdjacentElement('afterend', figure);
      };

      const cropFile = (input, file) => {
        const aspect = Number(input.dataset.cropAspect);
        if (!aspect || !file.type.startsWith('image/')) {
          return;
        }

        const reader = new FileReader();
        reader.onload = () => {
          const image = new Image();
          image.onload = () => {
            const sourceAspect = image.naturalWidth / image.naturalHeight;
            let cropWidth = image.naturalWidth;
            let cropHeight = image.naturalHeight;
            let sourceX = 0;
            let sourceY = 0;

            if (sourceAspect > aspect) {
              cropWidth = Math.round(image.naturalHeight * aspect);
              sourceX = Math.round((image.naturalWidth - cropWidth) / 2);
            } else if (sourceAspect < aspect) {
              cropHeight = Math.round(image.naturalWidth / aspect);
              sourceY = 0;
            }

            const canvas = document.createElement('canvas');
            canvas.width = cropWidth;
            canvas.height = cropHeight;

            const context = canvas.getContext('2d');
            context.drawImage(image, sourceX, sourceY, cropWidth, cropHeight, 0, 0, cropWidth, cropHeight);

            canvas.toBlob((blob) => {
              if (!blob) {
                return;
              }

              const extension = file.type === 'image/png' ? 'png' : file.type === 'image/webp' ? 'webp' : 'jpg';
              const croppedFile = new File([blob], file.name.replace(/\.[^.]+$/, '') + '-cropped.' + extension, {
                type: blob.type,
                lastModified: Date.now(),
              });

              if (window.DataTransfer) {
                const transfer = new DataTransfer();
                transfer.items.add(croppedFile);
                input.files = transfer.files;
              }

              createPreview(input, URL.createObjectURL(blob), input.dataset.cropLabel || '指定比率');
            }, file.type === 'image/png' || file.type === 'image/webp' ? file.type : 'image/jpeg', 0.92);
          };
          image.src = String(reader.result);
        };
        reader.readAsDataURL(file);
      };

      cropInputs.forEach((input) => {
        input.addEventListener('change', () => {
          const file = input.files?.[0];
          if (file) {
            cropFile(input, file);
          }
        });
      });
    })();

    (() => {
      const gallery = document.querySelector('.admin-gallery-fields');
      if (!gallery) {
        return;
      }

      const updateNumbers = () => {
        gallery.querySelectorAll('.admin-gallery-field').forEach((field, index) => {
          const number = field.querySelector('.admin-gallery-field__number');
          if (number) {
            number.textContent = `画面 ${index + 1}`;
          }
        });
      };

      gallery.addEventListener('click', (event) => {
        const button = event.target.closest('[data-gallery-move]');
        if (!button) {
          return;
        }

        const field = button.closest('.admin-gallery-field');
        if (!field) {
          return;
        }

        if (button.dataset.galleryMove === 'up' && field.previousElementSibling?.classList.contains('admin-gallery-field')) {
          gallery.insertBefore(field, field.previousElementSibling);
        }

        if (button.dataset.galleryMove === 'down' && field.nextElementSibling) {
          gallery.insertBefore(field.nextElementSibling, field);
        }

        updateNumbers();
      });
    })();
  </script>
</body>
</html>

