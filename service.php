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

  <section class="service-tools-section">
    <div class="section-inner service-tools__layout">
      <div>
        <p class="section-label"><?php echo e($service['services']['label']); ?></p>
        <h2><?php echo responsive_text($service['services'], 'title'); ?></h2>
      </div>
      <div class="service-tools__items">
        <?php foreach ($service['services']['items'] as $item) : ?>
          <article class="service-tool-card">
            <p class="section-label"><?php echo e($item['label']); ?></p>
            <h3><?php echo e($item['title']); ?></h3>
            <p><?php echo e($item['body']); ?></p>
            <ul>
              <?php foreach ($item['examples'] as $example) : ?>
                <li><?php echo e($example); ?></li>
              <?php endforeach; ?>
            </ul>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="service-feature-section">
    <div class="section-inner">
      <div class="section-heading">
        <p class="section-label"><?php echo e($service['features']['label']); ?></p>
        <h2><?php echo responsive_text($service['features'], 'title'); ?></h2>
        <p><?php echo e($service['features']['lead']); ?></p>
      </div>
      <div class="content-grid content-grid--two">
        <?php foreach ($service['features']['items'] as $item) : ?>
          <article class="content-card service-feature-card">
            <p class="section-label"><?php echo e($item['label']); ?></p>
            <h3><?php echo e($item['title']); ?></h3>
            <p><?php echo e($item['body']); ?></p>

            <?php if (!empty($item['points'])) : ?>
              <ul class="service-feature-list">
                <?php foreach ($item['points'] as $point) : ?>
                  <li><?php echo e($point); ?></li>
                <?php endforeach; ?>
              </ul>
            <?php endif; ?>

            <?php if (!empty($item['methods'])) : ?>
              <div class="service-feature-methods">
                <?php foreach ($item['methods'] as $method) : ?>
                  <div>
                    <h4><?php echo e($method['title']); ?></h4>
                    <p><?php echo e($method['body']); ?></p>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>

            <p class="service-feature-note"><?php echo e($item['note']); ?></p>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="service-team-section">
    <div class="section-inner">
      <div class="section-heading">
        <p class="section-label"><?php echo e($service['team']['label']); ?></p>
        <h2><?php echo responsive_text($service['team'], 'title'); ?></h2>
        <p><?php echo e($service['team']['lead']); ?></p>
      </div>

      <div class="service-team-diagram" aria-label="制作と開発を支える担当領域">
        <div class="service-team-diagram__center">
          <span><?php echo e($service['team']['center_label']); ?></span>
          <strong><?php echo responsive_text($service['team'], 'center_title'); ?></strong>
        </div>
        <div class="service-team-diagram__roles">
          <?php foreach ($service['team']['experts'] as $expert) : ?>
            <article class="service-team-card">
              <p class="section-label"><?php echo e($expert['label']); ?></p>
              <h3><?php echo e($expert['title']); ?></h3>
              <p><?php echo e($expert['body']); ?></p>
            </article>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>

  <section class="service-project-section">
    <div class="section-inner">
      <div class="section-heading">
        <p class="section-label"><?php echo e($service['flow']['label']); ?></p>
        <h2><?php echo responsive_text($service['flow'], 'title'); ?></h2>
      </div>
      <div class="service-project-flow">
        <?php foreach ($service['flow']['steps'] as $step) : ?>
          <article class="service-project-step">
            <span><?php echo e($step['number']); ?></span>
            <h3><?php echo e($step['title']); ?></h3>
            <p><?php echo e($step['body']); ?></p>
          </article>
        <?php endforeach; ?>
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
