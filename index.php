<?php
$site = require __DIR__ . '/data/site.php';
$navigation = require __DIR__ . '/data/navigation.php';
$home = require __DIR__ . '/data/home.php';
require __DIR__ . '/include/functions.php';

$current_page = 'home';
$page_title = $site['name'];
$page_description = $site['description'];

include __DIR__ . '/include/head.php';
include __DIR__ . '/include/header.php';
?>

<main>
  <section class="home-hero hero-scene hero-scene--home">
    <div class="section-inner hero-layout">
      <div class="hero-copy">
        <p class="section-label"><?php echo e($home['hero']['label']); ?></p>
        <h1><?php echo responsive_text($home['hero'], 'title'); ?></h1>
        <p class="hero-subcopy"><?php echo responsive_text($home['hero'], 'subtitle'); ?></p>
        <p class="section-lead"><?php echo responsive_text($home['hero'], 'lead'); ?></p>
        <div class="button-group">
          <a class="button button--primary" href="<?php echo e($home['hero']['primary_cta']['url']); ?>">
            <?php echo e($home['hero']['primary_cta']['label']); ?>
          </a>
          <a class="button button--secondary" href="<?php echo e($home['hero']['secondary_cta']['url']); ?>">
            <?php echo e($home['hero']['secondary_cta']['label']); ?>
          </a>
        </div>
      </div>
    </div>
  </section>

  <section class="service-teaser">
    <div class="section-inner">
      <div class="section-heading">
        <p class="section-label"><?php echo e($home['service_teaser']['label']); ?></p>
        <h2><?php echo responsive_text($home['service_teaser'], 'title'); ?></h2>
        <p class="service-teaser__subtitle"><?php echo responsive_text($home['service_teaser'], 'subtitle'); ?></p>
        <p><?php echo e($home['service_teaser']['lead']); ?></p>
      </div>
      <div class="content-grid content-grid--two">
        <?php foreach ($home['service_teaser']['items'] as $index => $item) : ?>
          <article class="content-card">
            <span class="service-teaser__number"><?php echo e(str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)); ?></span>
            <h3><?php echo e($item['title']); ?></h3>
            <p class="service-teaser__catch"><?php echo e($item['catch']); ?></p>
            <p><?php echo e($item['body']); ?></p>
          </article>
        <?php endforeach; ?>
      </div>
      <div class="text-link-group">
        <?php foreach ($home['service_teaser']['links'] as $link) : ?>
          <a class="text-link" href="<?php echo e($link['url']); ?>">
            <?php echo e($link['label']); ?>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="next-cta">
    <div class="section-inner section-inner--narrow">
      <p class="section-label"><?php echo e($home['cta']['label']); ?></p>
      <h2><?php echo responsive_text($home['cta'], 'title'); ?></h2>
      <p><?php echo e($home['cta']['body']); ?></p>
      <a class="button button--primary" href="<?php echo e($home['cta']['link']['url']); ?>">
        <?php echo e($home['cta']['link']['label']); ?>
      </a>
    </div>
  </section>
</main>

<?php include __DIR__ . '/include/footer.php'; ?>
