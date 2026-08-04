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

  <?php foreach ($service['services']['items'] as $index => $item) : ?>
    <?php
    $feature = $service['features']['items'][$index] ?? [];
    $section_id = $index === 0 ? 'website' : 'business-system';
    $section_number = str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT);
    ?>
    <section class="service-offering-section service-offering-section--<?php echo e($section_id); ?>" id="<?php echo e($section_id); ?>">
      <div class="section-inner service-offering__layout">
        <div class="service-offering__summary">
          <p class="section-label"><?php echo e($section_number); ?> / <?php echo e($item['label']); ?></p>
          <h2><?php echo e($item['title']); ?></h2>
          <p class="service-offering__lead"><?php echo e($item['body']); ?></p>
          <ul class="service-offering__examples">
            <?php foreach ($item['examples'] as $example) : ?>
              <li><?php echo e($example); ?></li>
            <?php endforeach; ?>
          </ul>
        </div>

        <article class="service-feature-card service-offering__feature">
          <p class="section-label"><?php echo e($feature['label'] ?? ''); ?></p>
          <h3><?php echo e($feature['title'] ?? ''); ?></h3>
          <p><?php echo e($feature['body'] ?? ''); ?></p>

          <?php if (!empty($feature['points'])) : ?>
            <ul class="service-feature-list">
              <?php foreach ($feature['points'] as $point) : ?>
                <li><?php echo e($point); ?></li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>

          <?php if (!empty($feature['methods'])) : ?>
            <div class="service-feature-methods">
              <?php foreach ($feature['methods'] as $method) : ?>
                <div>
                  <h4><?php echo e($method['title']); ?></h4>
                  <p><?php echo e($method['body']); ?></p>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

          <p class="service-feature-note"><?php echo e($feature['note'] ?? ''); ?></p>
        </article>
      </div>

      <?php if (!empty($feature['agile'])) : ?>
        <?php $agile = $feature['agile']; ?>
        <div class="section-inner service-agile">
          <div class="service-agile__heading">
            <p class="section-label"><?php echo e($agile['label']); ?></p>
            <h3><?php echo e($agile['title']); ?></h3>
            <p class="service-agile__experience"><?php echo e($agile['experience']); ?></p>
            <p><?php echo e($agile['body']); ?></p>
          </div>

          <div class="service-agile__comparison">
            <?php foreach ($agile['comparison'] as $method) : ?>
              <article class="service-agile-method<?php echo !empty($method['recommended']) ? ' service-agile-method--recommended' : ''; ?>">
                <p class="section-label"><?php echo e($method['label']); ?></p>
                <h4><?php echo e($method['title']); ?></h4>
                <p><?php echo e($method['lead']); ?></p>
                <ol class="service-agile-method__flow">
                  <?php foreach ($method['flow'] as $step) : ?>
                    <li><?php echo e($step); ?></li>
                  <?php endforeach; ?>
                </ol>
                <p class="service-agile-method__note"><?php echo e($method['note']); ?></p>
              </article>
            <?php endforeach; ?>
          </div>

          <div class="service-agile__cycle-wrap">
            <p class="section-label">Continuous Development Cycle</p>
            <h4>月額開発で、現場と開発のPDCAを回す。</h4>
            <ol class="service-agile-cycle">
              <?php foreach ($agile['cycle'] as $step) : ?>
                <li>
                  <span><?php echo e($step['number']); ?></span>
                  <strong><?php echo e($step['title']); ?></strong>
                  <p><?php echo e($step['body']); ?></p>
                </li>
              <?php endforeach; ?>
            </ol>
            <p class="service-agile__caption">※ <?php echo e($agile['caption']); ?></p>
          </div>
        </div>
      <?php endif; ?>
    </section>
  <?php endforeach; ?>

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
