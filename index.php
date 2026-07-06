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

  <section class="sme-focus-section">
    <div class="section-inner sme-focus-layout">
      <div class="sme-focus-copy">
        <p class="section-label"><?php echo e($home['sme_focus']['label']); ?></p>
        <h2><?php echo e($home['sme_focus']['title']); ?></h2>
        <p class="section-lead"><?php echo e($home['sme_focus']['lead']); ?></p>
        <?php foreach ($home['sme_focus']['body'] as $paragraph) : ?>
          <p><?php echo e($paragraph); ?></p>
        <?php endforeach; ?>
      </div>

      <div class="sme-focus-stats" aria-label="中小企業に関する統計">
        <?php foreach ($home['sme_focus']['stats'] as $stat) : ?>
          <div class="sme-focus-stat">
            <strong><?php echo e($stat['number']); ?></strong>
            <span><?php echo e($stat['label']); ?></span>
          </div>
        <?php endforeach; ?>
        <p><?php echo e($home['sme_focus']['note']); ?></p>
      </div>
    </div>
  </section>

  <section class="service-teaser">
    <div class="section-inner">
      <div class="section-heading">
        <p class="section-label"><?php echo e($home['service_teaser']['label']); ?></p>
        <h2><?php echo e($home['service_teaser']['title']); ?></h2>
        <p><?php echo e($home['service_teaser']['lead']); ?></p>
      </div>
      <div class="content-grid content-grid--two">
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

  <section class="environment-section">
    <div class="section-inner">
      <div class="section-heading">
        <p class="section-label"><?php echo e($home['environment']['label']); ?></p>
        <h2><?php echo e($home['environment']['title']); ?></h2>
        <p><?php echo e($home['environment']['lead']); ?></p>
      </div>
      <div class="content-grid content-grid--three">
        <?php foreach ($home['environment']['items'] as $item) : ?>
          <article class="content-card environment-card">
            <h3><?php echo e($item['title']); ?></h3>
            <p><?php echo e($item['body']); ?></p>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="ai-partner-section">
    <div class="section-inner">
      <div class="section-heading">
        <p class="section-label"><?php echo e($home['ai_partner']['label']); ?></p>
        <h2><?php echo e($home['ai_partner']['title']); ?></h2>
        <p><?php echo e($home['ai_partner']['lead']); ?></p>
      </div>
      <div class="section-inner--narrow ai-partner-copy">
        <?php foreach ($home['ai_partner']['body'] as $paragraph) : ?>
          <p><?php echo e($paragraph); ?></p>
        <?php endforeach; ?>
      </div>
      <div class="content-grid content-grid--three">
        <?php foreach ($home['ai_partner']['items'] as $item) : ?>
          <article class="content-card ai-partner-card">
            <h3><?php echo e($item['title']); ?></h3>
            <p><?php echo e($item['body']); ?></p>
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

  <section class="home-question-section">
    <div class="section-inner">
      <div class="section-heading">
        <p class="section-label"><?php echo e($home['questions']['label']); ?></p>
        <h2><?php echo e($home['questions']['title']); ?></h2>
        <p><?php echo e($home['questions']['lead']); ?></p>
      </div>
      <div class="content-grid content-grid--two">
        <?php foreach ($home['questions']['items'] as $item) : ?>
          <article class="content-card home-question-card">
            <h3><?php echo e($item['title']); ?></h3>
            <p><?php echo e($item['body']); ?></p>
          </article>
        <?php endforeach; ?>
      </div>
      <a class="text-link" href="<?php echo e($home['questions']['link']['url']); ?>">
        <?php echo e($home['questions']['link']['label']); ?>
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
