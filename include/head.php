<?php
$site = $site ?? require __DIR__ . '/../data/site.php';
$page_title = $page_title ?? $site['name'];
$page_description = $page_description ?? $site['description'];
$page_type = $page_type ?? 'website';
$page_url = $page_url ?? '';
$page_allow_canonical = $page_allow_canonical ?? true;
$og_image = $og_image ?? $site['default_og_image'];

$request_method = strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET'));
$request_host = strtolower((string) ($_SERVER['HTTP_HOST'] ?? ''));
$request_host = preg_replace('/:\d+$/', '', $request_host) ?? $request_host;
$request_uri = (string) ($_SERVER['REQUEST_URI'] ?? '/');
$request_path = parse_url($request_uri, PHP_URL_PATH);
$request_path = is_string($request_path) && $request_path !== '' ? $request_path : '/';
$production_hosts = ['rise-gate.com', 'www.rise-gate.com'];

if (in_array($request_method, ['GET', 'HEAD'], true) && in_array($request_host, $production_hosts, true)) {
    $canonical_path = preg_replace('#/index\.php$#', '/', $request_path) ?? $request_path;
    $needs_redirect = $request_host !== 'rise-gate.com' || $canonical_path !== $request_path;

    if ($needs_redirect && !headers_sent()) {
        $query = parse_url($request_uri, PHP_URL_QUERY);
        $redirect_url = 'https://rise-gate.com' . $canonical_path;
        if (is_string($query) && $query !== '') {
            $redirect_url .= '?' . $query;
        }

        header('Location: ' . $redirect_url, true, 301);
        exit;
    }
}

$full_title = $page_title === $site['name']
    ? $page_title
    : $page_title . ' | ' . $site['name'];

$base_url = rtrim($site['base_url'], '/');
$canonical_url = $page_url;
$script_name = str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? ''));
$is_admin_page = str_starts_with(basename($script_name), 'admin');
if ($canonical_url === '' && $base_url !== '' && !$is_admin_page && $page_allow_canonical) {
    $current_path = preg_replace('#/index\.php$#', '/', $script_name) ?? $script_name;
    $canonical_url = $base_url . $current_path;
}

$og_image_url = $og_image;
if ($base_url !== '' && $og_image !== '' && !preg_match('/^https?:\/\//', $og_image)) {
    $og_image_url = $base_url . '/' . ltrim($og_image, '/');
}

$stylesheet_path = __DIR__ . '/../css/style.css';
$stylesheet_version = file_exists($stylesheet_path) ? (string) filemtime($stylesheet_path) : '1';
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="<?php echo htmlspecialchars($page_description, ENT_QUOTES, 'UTF-8'); ?>">
  <?php if ($canonical_url !== '') : ?>
    <link rel="canonical" href="<?php echo htmlspecialchars($canonical_url, ENT_QUOTES, 'UTF-8'); ?>">
  <?php endif; ?>
  <meta property="og:type" content="<?php echo htmlspecialchars($page_type, ENT_QUOTES, 'UTF-8'); ?>">
  <meta property="og:site_name" content="<?php echo htmlspecialchars($site['name'], ENT_QUOTES, 'UTF-8'); ?>">
  <meta property="og:title" content="<?php echo htmlspecialchars($full_title, ENT_QUOTES, 'UTF-8'); ?>">
  <meta property="og:description" content="<?php echo htmlspecialchars($page_description, ENT_QUOTES, 'UTF-8'); ?>">
  <?php if ($canonical_url !== '') : ?>
    <meta property="og:url" content="<?php echo htmlspecialchars($canonical_url, ENT_QUOTES, 'UTF-8'); ?>">
  <?php endif; ?>
  <?php if ($og_image_url !== '') : ?>
    <meta property="og:image" content="<?php echo htmlspecialchars($og_image_url, ENT_QUOTES, 'UTF-8'); ?>">
  <?php endif; ?>
  <meta name="twitter:card" content="summary_large_image">
  <title><?php echo htmlspecialchars($full_title, ENT_QUOTES, 'UTF-8'); ?></title>
  <link rel="icon" href="/favicon.svg" type="image/svg+xml" sizes="any">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@700;800;900&family=Noto+Sans+JP:wght@400;500;700&display=swap">
  <link rel="stylesheet" href="css/style.css?v=<?php echo htmlspecialchars($stylesheet_version, ENT_QUOTES, 'UTF-8'); ?>">
  <?php $hero_cms_style = function_exists('hero_cms_css') ? hero_cms_css() : ''; ?>
  <?php if ($hero_cms_style !== '') : ?>
    <style id="hero-cms-style"><?php echo $hero_cms_style; ?></style>
  <?php endif; ?>
</head>
