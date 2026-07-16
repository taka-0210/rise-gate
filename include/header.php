<?php
$site = $site ?? require __DIR__ . '/../data/site.php';
$navigation = $navigation ?? require __DIR__ . '/../data/navigation.php';
$current_page = $current_page ?? '';
$logo_path = __DIR__ . '/../image/logo/risegate-logo.png';
$logo_version = file_exists($logo_path) ? (string) filemtime($logo_path) : '1';
?>
<body>
  <header class="site-header">
    <div class="site-header__inner">
      <a class="site-logo" href="index.php" aria-label="<?php echo htmlspecialchars($site['short_name'], ENT_QUOTES, 'UTF-8'); ?> トップページ">
        <img src="image/logo/risegate-logo.png?v=<?php echo htmlspecialchars($logo_version, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($site['short_name'], ENT_QUOTES, 'UTF-8'); ?>">
      </a>

      <button class="nav-toggle" type="button" aria-label="メニューを開閉する" aria-expanded="false" aria-controls="global-nav">
        <span></span>
        <span></span>
      </button>

      <nav id="global-nav" class="global-nav" aria-label="グローバルナビゲーション">
        <?php foreach ($navigation['main'] as $item) : ?>
          <?php $is_current = $current_page === $item['key']; ?>
          <a href="<?php echo htmlspecialchars($item['url'], ENT_QUOTES, 'UTF-8'); ?>"<?php echo $is_current ? ' aria-current="page"' : ''; ?>>
            <?php echo htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8'); ?>
          </a>
        <?php endforeach; ?>
        <?php $cta = $navigation['primary_cta']; ?>
        <a class="global-nav__cta" href="<?php echo htmlspecialchars($cta['url'], ENT_QUOTES, 'UTF-8'); ?>"<?php echo $current_page === $cta['key'] ? ' aria-current="page"' : ''; ?>>
          <?php echo htmlspecialchars($cta['label'], ENT_QUOTES, 'UTF-8'); ?>
        </a>
      </nav>
    </div>
  </header>
