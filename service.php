<?php
$site = require __DIR__ . '/data/site.php';
$navigation = require __DIR__ . '/data/navigation.php';
$service = require __DIR__ . '/data/service.php';
require __DIR__ . '/include/functions.php';

$current_page = 'service';
$page_title = $service['meta']['title'];
$page_description = $service['meta']['description'];

include __DIR__ . '/include/head.php';
include __DIR__ . '/include/header.php';
?>

<main>
  <section class="page-hero hero-scene hero-scene--service">
    <div class="section-inner section-inner--narrow">
      <p class="section-label"><?php echo e($service['hero']['label']); ?></p>
      <h1><?php echo responsive_text($service['hero'], 'title'); ?></h1>
      <p class="section-lead"><?php echo responsive_text($service['hero'], 'lead'); ?></p>
    </div>
  </section>

  <section class="service-overview-section">
    <div class="section-inner">
      <div class="section-heading">
        <p class="section-label"><?php echo e($service['services']['label']); ?></p>
        <h2><?php echo responsive_text($service['services'], 'title'); ?></h2>
        <p>ご相談内容に合うサービスをお選びください。それぞれの特徴や進め方を、専用ページでご案内します。</p>
      </div>

      <div class="service-overview-grid">
        <?php foreach ($service['services']['items'] as $index => $item) : ?>
          <a class="service-overview-card" href="<?php echo e($item['url']); ?>">
            <span class="service-overview-card__number"><?php echo str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT); ?></span>
            <p class="section-label"><?php echo e($item['label']); ?></p>
            <h3><?php echo e($item['title']); ?></h3>
            <?php if (!empty($item['purpose'])) : ?><p class="service-overview-card__purpose"><?php echo e($item['purpose']); ?></p><?php endif; ?>
            <p><?php echo e($item['body']); ?></p>
            <ul>
              <?php foreach ($item['examples'] as $example) : ?>
                <li><?php echo e($example); ?></li>
              <?php endforeach; ?>
            </ul>
            <span class="text-link"><?php echo $item['url'] === 'prototype-development.php' ? '開発の進め方を見る' : '詳しく見る'; ?></span>
          </a>
        <?php endforeach; ?>
      </div>
      <div class="service-overview__closing">
        <h3><?php echo nl2br(e($service['services']['closing']['title'])); ?></h3>
        <p><?php echo e($service['services']['closing']['body']); ?></p>
      </div>
    </div>
  </section>

  <section class="next-cta">
    <div class="section-inner section-inner--narrow">
      <p class="section-label"><?php echo e($service['cta']['label']); ?></p>
      <h2><?php echo responsive_text($service['cta'], 'title'); ?></h2>
      <p><?php echo e($service['cta']['body']); ?></p>
      <a class="button button--primary" href="<?php echo e($service['cta']['link']['url']); ?>">
        <?php echo e($service['cta']['link']['label']); ?>
      </a>
    </div>
  </section>
</main>

<?php include __DIR__ . '/include/footer.php'; ?>
