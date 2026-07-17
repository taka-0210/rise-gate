<?php
$site = require __DIR__ . '/data/site.php';
$navigation = require __DIR__ . '/data/navigation.php';
$master_page = require __DIR__ . '/data/master.php';
require __DIR__ . '/include/functions.php';

$current_page = 'master';
$page_title = $master_page['meta']['title'];
$page_description = $master_page['meta']['description'];

include __DIR__ . '/include/head.php';
include __DIR__ . '/include/header.php';
?>

<main>
  <section class="page-hero hero-scene hero-scene--master">
    <div class="section-inner section-inner--narrow">
      <p class="section-label"><?php echo e($master_page['hero']['label']); ?></p>
      <h1><?php echo responsive_text($master_page['hero'], 'title'); ?></h1>
      <p class="section-lead"><?php echo responsive_text($master_page['hero'], 'lead'); ?></p>
    </div>
  </section>

  <section class="master-program-intro">
    <div class="section-inner section-inner--narrow">
      <p class="section-label"><?php echo e($master_page['intro']['label']); ?></p>
      <h2><?php echo responsive_text($master_page['intro'], 'title'); ?></h2>
      <?php foreach ($master_page['intro']['body'] as $paragraph) : ?>
        <p><?php echo e($paragraph); ?></p>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="master-program-roles">
    <div class="section-inner">
      <div class="section-heading">
        <p class="section-label"><?php echo e($master_page['roles']['label']); ?></p>
        <h2><?php echo responsive_text($master_page['roles'], 'title'); ?></h2>
      </div>

      <div class="content-grid content-grid--four">
        <?php foreach ($master_page['roles']['items'] as $item) : ?>
          <article class="content-card">
            <h3><?php echo e($item['title']); ?></h3>
            <p><?php echo e($item['body']); ?></p>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="master-program-flow">
    <div class="section-inner">
      <div class="master-program-flow__copy">
        <p class="section-label"><?php echo e($master_page['flow']['label']); ?></p>
        <h2><?php echo responsive_text($master_page['flow'], 'title'); ?></h2>
        <p><?php echo e($master_page['flow']['body']); ?></p>
      </div>

      <ol class="master-flow-list">
        <?php foreach ($master_page['flow']['steps'] as $index => $step) : ?>
          <li>
            <span><?php echo e(str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)); ?></span>
            <?php echo e($step); ?>
          </li>
        <?php endforeach; ?>
      </ol>
    </div>
  </section>

  <section class="master-os-support">
    <div class="section-inner">
      <div class="master-os-support__heading">
        <p class="section-label"><?php echo e($master_page['os_support']['label']); ?></p>
        <h2><?php echo responsive_text($master_page['os_support'], 'title'); ?></h2>
        <p><?php echo e($master_page['os_support']['lead']); ?></p>
        <a class="text-link" href="<?php echo e($master_page['os_support']['link']['url']); ?>">
          <?php echo e($master_page['os_support']['link']['label']); ?>
        </a>
      </div>

      <div class="master-os-support__items">
        <?php foreach ($master_page['os_support']['items'] as $item) : ?>
          <article class="master-os-support__item">
            <h3><?php echo e($item['title']); ?></h3>
            <p><?php echo e($item['body']); ?></p>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="next-cta">
    <div class="section-inner section-inner--narrow">
      <p class="section-label"><?php echo e($master_page['cta']['label']); ?></p>
      <h2><?php echo responsive_text($master_page['cta'], 'title'); ?></h2>
      <p><?php echo e($master_page['cta']['body']); ?></p>
      <a class="button button--primary" href="<?php echo e($master_page['cta']['link']['url']); ?>">
        <?php echo e($master_page['cta']['link']['label']); ?>
      </a>
    </div>
  </section>
</main>

<?php include __DIR__ . '/include/footer.php'; ?>
