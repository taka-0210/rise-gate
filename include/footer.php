<?php
$site = $site ?? require __DIR__ . '/../data/site.php';
$navigation = $navigation ?? require __DIR__ . '/../data/navigation.php';
$logo_path = __DIR__ . '/../image/logo/risegate-logo.png';
$logo_version = file_exists($logo_path) ? (string) filemtime($logo_path) : '1';
?>
  <footer class="site-footer">
    <div class="site-footer__inner">
      <div class="site-footer__brand">
        <a class="site-footer__logo" href="index.php">
          <img src="image/logo/risegate-logo.png?v=<?php echo htmlspecialchars($logo_version, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($site['short_name'], ENT_QUOTES, 'UTF-8'); ?>">
        </a>
        <p><?php echo htmlspecialchars($site['brand_statement'], ENT_QUOTES, 'UTF-8'); ?></p>
        <p><?php echo htmlspecialchars($site['tagline'], ENT_QUOTES, 'UTF-8'); ?></p>
      </div>

      <nav class="site-footer__nav" aria-label="フッターナビゲーション">
        <?php foreach ($navigation['footer'] as $item) : ?>
          <a href="<?php echo htmlspecialchars($item['url'], ENT_QUOTES, 'UTF-8'); ?>">
            <?php echo htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8'); ?>
          </a>
        <?php endforeach; ?>
      </nav>
    </div>
    <p class="site-footer__copyright">&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($site['copyright_name'], ENT_QUOTES, 'UTF-8'); ?></p>
  </footer>

  <script src="js/script.js"></script>
</body>
</html>
