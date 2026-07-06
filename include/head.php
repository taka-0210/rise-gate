<?php
$site = $site ?? require __DIR__ . '/../data/site.php';
$page_title = $page_title ?? $site['name'];
$page_description = $page_description ?? $site['description'];
$page_type = $page_type ?? 'website';
$page_url = $page_url ?? '';
$og_image = $og_image ?? $site['default_og_image'];

$full_title = $page_title === $site['name']
    ? $page_title
    : $page_title . ' | ' . $site['name'];

$base_url = rtrim($site['base_url'], '/');
$canonical_url = $page_url;
if ($canonical_url === '' && $base_url !== '') {
    $current_path = $_SERVER['SCRIPT_NAME'] ?? '';
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
  <link rel="stylesheet" href="css/style.css?v=<?php echo htmlspecialchars($stylesheet_version, ENT_QUOTES, 'UTF-8'); ?>">
</head>
