<?php
$site = require __DIR__ . '/data/site.php';
$navigation = require __DIR__ . '/data/navigation.php';
$home = require __DIR__ . '/data/home.php';
require __DIR__ . '/include/functions.php';

$current_page = 'home';
$page_title = $site['name'];
$page_description = $site['description'];
$journey_patterns = ['pattern-a', 'pattern-b', 'pattern-c', 'pattern-d'];
$journey_pattern = $journey_patterns[array_rand($journey_patterns)];

include __DIR__ . '/include/head.php';
include __DIR__ . '/include/header.php';
?>

<main>
  <section class="home-hero hero-scene hero-scene--home">
    <div class="section-inner hero-layout">
      <div class="hero-copy">
        <p class="section-label"><?php echo e($home['hero']['label']); ?></p>
        <h1><?php echo e($home['hero']['title']); ?></h1>
        <p class="hero-subcopy"><?php echo e($home['hero']['subtitle']); ?></p>
        <p class="section-lead"><?php echo e($home['hero']['lead']); ?></p>
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

  <section class="home-system-section" aria-label="改善が続く仕組みのイメージ">
    <div class="section-inner">
      <figure class="home-system-visual">
        <figcaption class="home-system-visual__caption">
          <span>Designing Systems</span>
          <span>Where Improvement Continues</span>
        </figcaption>
        <img src="image/scene/home-system-flow.png" alt="">
      </figure>
    </div>
  </section>

  <section class="brand-map-section">
    <div class="section-inner">
      <div class="section-heading">
        <p class="section-label"><?php echo e($home['brand_map']['label']); ?></p>
        <h2><?php echo e($home['brand_map']['title']); ?></h2>
        <p><?php echo e($home['brand_map']['lead']); ?></p>
      </div>

      <div class="brand-journey brand-journey--<?php echo e($journey_pattern); ?>" aria-label="ブランド体験の導線">
        <?php foreach ($home['brand_map']['items'] as $index => $item) : ?>
          <article class="brand-map-card">
            <a href="<?php echo e($item['url']); ?>">
              <span class="brand-map-card__number"><?php echo e(str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)); ?></span>
              <span class="section-label"><?php echo e($item['label']); ?></span>
              <h3><?php echo e($item['title']); ?></h3>
              <p><?php echo e($item['body']); ?></p>
              <span class="text-link"><?php echo e($item['link_label']); ?></span>
            </a>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="brand-message">
    <div class="section-inner section-inner--narrow">
      <p class="section-label"><?php echo e($home['brand_message']['label']); ?></p>
      <h2><?php echo e($home['brand_message']['title']); ?></h2>
      <?php foreach ($home['brand_message']['body'] as $paragraph) : ?>
        <p><?php echo e($paragraph); ?></p>
      <?php endforeach; ?>
      <a class="text-link" href="<?php echo e($home['brand_message']['link']['url']); ?>">
        <?php echo e($home['brand_message']['link']['label']); ?>
      </a>
    </div>
  </section>

  <section class="problem-section">
    <div class="section-inner">
      <div class="section-heading">
        <p class="section-label"><?php echo e($home['problems']['label']); ?></p>
        <h2><?php echo e($home['problems']['title']); ?></h2>
        <p><?php echo e($home['problems']['lead']); ?></p>
      </div>
      <div class="content-grid content-grid--four">
        <?php foreach ($home['problems']['items'] as $item) : ?>
          <article class="content-card">
            <h3><?php echo e($item['title']); ?></h3>
            <p><?php echo e($item['body']); ?></p>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="approach-section">
    <div class="section-inner">
      <div class="section-heading">
        <p class="section-label"><?php echo e($home['approach']['label']); ?></p>
        <h2><?php echo e($home['approach']['title']); ?></h2>
        <p><?php echo e($home['approach']['lead']); ?></p>
      </div>
      <ol class="step-list">
        <?php foreach ($home['approach']['steps'] as $index => $step) : ?>
          <li>
            <span><?php echo e(str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)); ?></span>
            <?php echo e($step); ?>
          </li>
        <?php endforeach; ?>
      </ol>
      <a class="text-link" href="<?php echo e($home['approach']['link']['url']); ?>">
        <?php echo e($home['approach']['link']['label']); ?>
      </a>
    </div>
  </section>

  <section class="service-teaser">
    <div class="section-inner">
      <div class="section-heading">
        <p class="section-label"><?php echo e($home['service_teaser']['label']); ?></p>
        <h2><?php echo e($home['service_teaser']['title']); ?></h2>
        <p><?php echo e($home['service_teaser']['lead']); ?></p>
      </div>
      <div class="content-grid content-grid--four">
        <?php foreach ($home['service_teaser']['items'] as $item) : ?>
          <article class="content-card">
            <h3><?php echo e($item['title']); ?></h3>
            <p><?php echo e($item['body']); ?></p>
          </article>
        <?php endforeach; ?>
      </div>
      <a class="text-link" href="<?php echo e($home['service_teaser']['link']['url']); ?>">
        <?php echo e($home['service_teaser']['link']['label']); ?>
      </a>
    </div>
  </section>

  <section class="log-teaser">
    <div class="section-inner section-inner--narrow">
      <p class="section-label"><?php echo e($home['log_teaser']['label']); ?></p>
      <h2><?php echo e($home['log_teaser']['title']); ?></h2>
      <?php foreach ($home['log_teaser']['body'] as $paragraph) : ?>
        <p><?php echo e($paragraph); ?></p>
      <?php endforeach; ?>
      <a class="text-link" href="<?php echo e($home['log_teaser']['link']['url']); ?>">
        <?php echo e($home['log_teaser']['link']['label']); ?>
      </a>
    </div>
  </section>

  <section class="next-cta">
    <div class="section-inner section-inner--narrow">
      <p class="section-label"><?php echo e($home['cta']['label']); ?></p>
      <h2><?php echo e($home['cta']['title']); ?></h2>
      <p><?php echo e($home['cta']['body']); ?></p>
      <a class="button button--primary" href="<?php echo e($home['cta']['link']['url']); ?>">
        <?php echo e($home['cta']['link']['label']); ?>
      </a>
    </div>
  </section>
</main>

<?php include __DIR__ . '/include/footer.php'; ?>
