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

  <section class="service-teaser" id="services">
    <div class="section-inner">
      <div class="section-heading">
        <p class="section-label"><?php echo e($home['service_teaser']['label']); ?></p>
        <h2><?php echo responsive_text($home['service_teaser'], 'title'); ?></h2>
        <p class="service-teaser__subtitle"><?php echo responsive_text($home['service_teaser'], 'subtitle'); ?></p>
        <p><?php echo e($home['service_teaser']['lead']); ?></p>
      </div>
      <div class="content-grid content-grid--three">
        <?php foreach ($home['service_teaser']['items'] as $index => $item) : ?>
          <a class="content-card service-teaser__card-link" href="<?php echo e($item['url']); ?>">
            <span class="service-teaser__number"><?php echo e(str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)); ?></span>
            <h3><?php echo e($item['title']); ?></h3>
            <p class="service-teaser__catch"><?php echo e($item['catch']); ?></p>
            <p><?php echo e($item['body']); ?></p>
            <span class="service-teaser__card-action">詳しく見る</span>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="home-prototype-section">
    <div class="section-inner home-prototype__layout">
      <div class="home-prototype__copy">
        <p class="section-label"><?php echo e($home['prototype']['label']); ?></p>
        <h2><?php echo responsive_text($home['prototype'], 'title'); ?></h2>
        <p><?php echo e($home['prototype']['lead']); ?></p>
        <p class="home-prototype__closing"><?php echo e($home['prototype']['closing']); ?></p>
        <a class="text-link" href="<?php echo e($home['prototype']['link']['url']); ?>"><?php echo e($home['prototype']['link']['label']); ?></a>
      </div>
      <ol class="home-prototype__steps">
        <?php foreach ($home['prototype']['steps'] as $step) : ?>
          <li><span><?php echo e($step['number']); ?></span><div><h3><?php echo e($step['title']); ?></h3><p><?php echo e($step['body']); ?></p></div></li>
        <?php endforeach; ?>
      </ol>
    </div>
  </section>

  <section class="home-consultation-section">
    <div class="section-inner">
      <div class="section-heading">
        <p class="section-label"><?php echo e($home['consultation']['label']); ?></p>
        <h2><?php echo responsive_text($home['consultation'], 'title'); ?></h2>
      </div>
      <div class="content-grid content-grid--two home-consultation-grid">
        <?php foreach ($home['consultation']['items'] as $index => $item) : ?>
          <article class="home-consultation-card">
            <figure class="home-consultation-card__visual">
              <img src="<?php echo e($item['image']); ?>" alt="<?php echo e($item['image_alt']); ?>" loading="lazy">
            </figure>
            <div class="home-consultation-card__content">
              <span class="home-consultation-card__number"><?php echo e(str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)); ?></span>
              <h3><?php echo e($item['title']); ?></h3>
              <p><?php echo e($item['body']); ?></p>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
      <aside class="home-consultation-section__closing">
        <h3><?php echo e($home['consultation']['closing']['title']); ?></h3>
        <?php foreach ($home['consultation']['closing']['body'] as $paragraph) : ?>
          <p><?php echo e($paragraph); ?></p>
        <?php endforeach; ?>
      </aside>
    </div>
  </section>

  <section class="home-company-section">
    <div class="section-inner section-inner--narrow">
      <p class="section-label"><?php echo e($home['company_teaser']['label']); ?></p>
      <h2><?php echo responsive_text($home['company_teaser'], 'title'); ?></h2>
      <p class="section-lead"><?php echo e($home['company_teaser']['lead']); ?></p>
      <p><?php echo e($home['company_teaser']['body']); ?></p>
      <a class="text-link" href="<?php echo e($home['company_teaser']['link']['url']); ?>"><?php echo e($home['company_teaser']['link']['label']); ?></a>
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
