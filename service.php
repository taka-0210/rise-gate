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

  <section class="service-declaration">
    <div class="section-inner service-declaration__layout">
      <div class="service-declaration__copy">
        <p class="section-label"><?php echo e($service['declaration']['label']); ?></p>
        <h2><?php echo responsive_text($service['declaration'], 'title'); ?></h2>
      </div>
      <div class="service-declaration__body">
        <?php foreach ($service['declaration']['body'] as $paragraph) : ?>
          <p><?php echo e($paragraph); ?></p>
        <?php endforeach; ?>
        <p class="service-declaration__strong"><?php echo e($service['declaration']['strong']); ?></p>
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

      <div class="service-team-diagram" aria-label="改善プロジェクトを支える専門家チーム">
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

  <section class="service-involvement">
    <div class="section-inner service-involvement__layout">
      <div>
        <p class="section-label"><?php echo e($service['involvement']['label']); ?></p>
        <h2><?php echo responsive_text($service['involvement'], 'title'); ?></h2>
      </div>
      <div class="service-involvement__body">
        <?php foreach ($service['involvement']['body'] as $paragraph) : ?>
          <p><?php echo e($paragraph); ?></p>
        <?php endforeach; ?>
        <p class="service-declaration__strong"><?php echo e($service['involvement']['strong']); ?></p>
      </div>
    </div>
  </section>

  <section class="service-project-section">
    <div class="section-inner">
      <div class="section-heading">
        <p class="section-label"><?php echo e($service['project']['label']); ?></p>
        <h2><?php echo responsive_text($service['project'], 'title'); ?></h2>
      </div>
      <div class="service-project-flow">
        <?php foreach ($service['project']['steps'] as $step) : ?>
          <article class="service-project-step">
            <span><?php echo e($step['number']); ?></span>
            <h3><?php echo e($step['title']); ?></h3>
            <p><?php echo e($step['body']); ?></p>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <?php foreach (['website_value', 'system_value'] as $value_key) : ?>
    <?php
    $value_section = $service[$value_key];
    $section_class = 'website-value-section';
    if ($value_key === 'system_value') {
      $section_class .= ' website-value-section--system';
    }
    ?>
    <section class="<?php echo e($section_class); ?>">
      <div class="section-inner website-value-layout">
        <div class="website-value-copy">
          <p class="section-label"><?php echo e($value_section['label']); ?></p>
          <h2><?php echo responsive_text($value_section, 'title'); ?></h2>
          <p class="section-lead"><?php echo e($value_section['lead']); ?></p>
          <?php foreach ($value_section['body'] as $paragraph) : ?>
            <p><?php echo e($paragraph); ?></p>
          <?php endforeach; ?>
        </div>

        <div class="website-value-points">
          <?php foreach ($value_section['points'] as $point) : ?>
            <article class="website-value-point">
              <strong><?php echo e($point['number']); ?></strong>
              <h3><?php echo e($point['label']); ?></h3>
              <p><?php echo e($point['body']); ?></p>
            </article>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
  <?php endforeach; ?>

  <section class="service-tools-section">
    <div class="section-inner service-tools__layout">
      <div>
        <p class="section-label"><?php echo e($service['tools']['label']); ?></p>
        <h2><?php echo responsive_text($service['tools'], 'title'); ?></h2>
      </div>
      <div class="service-tools__items">
        <?php foreach ($service['tools']['items'] as $tool) : ?>
          <article class="service-tool-card">
            <p class="section-label"><?php echo e($tool['label']); ?></p>
            <h3><?php echo e($tool['title']); ?></h3>
            <p><?php echo e($tool['body']); ?></p>
            <?php if (!empty($tool['link'])) : ?>
              <a class="text-link" href="<?php echo e($tool['link']['url']); ?>"><?php echo e($tool['link']['label']); ?></a>
            <?php endif; ?>
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
      <div class="button-group">
        <a class="button button--primary" href="<?php echo e($service['cta']['link']['url']); ?>">
          <?php echo e($service['cta']['link']['label']); ?>
        </a>
        <a class="button button--secondary" href="<?php echo e($service['cta']['sub_link']['url']); ?>">
          <?php echo e($service['cta']['sub_link']['label']); ?>
        </a>
      </div>
    </div>
  </section>
</main>

<?php include __DIR__ . '/include/footer.php'; ?>
