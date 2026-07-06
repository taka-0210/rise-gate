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
      <h1><?php echo e($service['hero']['title']); ?></h1>
      <p class="section-lead"><?php echo e($service['hero']['lead']); ?></p>
    </div>
  </section>

  <section class="service-intro">
    <div class="section-inner section-inner--narrow">
      <p class="section-label"><?php echo e($service['intro']['label']); ?></p>
      <h2><?php echo e($service['intro']['title']); ?></h2>
      <?php foreach ($service['intro']['body'] as $paragraph) : ?>
        <p><?php echo e($paragraph); ?></p>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="service-fields">
    <div class="section-inner">
      <div class="section-heading">
        <p class="section-label"><?php echo e($service['pillars_heading']['label']); ?></p>
        <h2><?php echo e($service['pillars_heading']['title']); ?></h2>
        <p><?php echo e($service['pillars_heading']['lead']); ?></p>
      </div>

      <div class="service-field-list">
        <?php foreach ($service['pillars'] as $pillar) : ?>
          <article class="service-field service-field--pillar">
            <div class="service-field__head">
              <span class="service-field__number" aria-hidden="true"><?php echo e($pillar['number']); ?></span>
              <div>
                <p class="section-label"><?php echo e($pillar['label']); ?></p>
                <h3><?php echo e($pillar['title']); ?></h3>
              </div>
            </div>
            <div class="service-field__body">
              <p class="service-pillar-lead"><?php echo e($pillar['lead']); ?></p>
              <div class="service-pillar-grid">
                <div>
                  <h4>よく起きていること</h4>
                  <ul class="simple-list">
                    <?php foreach ($pillar['problem'] as $item) : ?>
                      <li><?php echo e($item); ?></li>
                    <?php endforeach; ?>
                  </ul>
                </div>
                <div>
                  <h4>ライズゲートが整えること</h4>
                  <ul class="simple-list">
                    <?php foreach ($pillar['solution'] as $item) : ?>
                      <li><?php echo e($item); ?></li>
                    <?php endforeach; ?>
                  </ul>
                </div>
              </div>
              <div class="service-question">
                <h4><?php echo e($pillar['question']['title']); ?></h4>
                <p><?php echo e($pillar['question']['answer']); ?></p>
              </div>
              <div class="service-pillar-visuals">
                <?php foreach ($pillar['images'] as $image) : ?>
                  <figure>
                    <img src="<?php echo e($image['src']); ?>" alt="<?php echo e($image['alt']); ?>" loading="lazy">
                    <figcaption><?php echo e($image['caption']); ?></figcaption>
                  </figure>
                <?php endforeach; ?>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="service-choice">
    <div class="section-inner section-inner--narrow">
      <p class="section-label"><?php echo e($service['common']['label']); ?></p>
      <h2><?php echo e($service['common']['title']); ?></h2>
      <?php foreach ($service['common']['body'] as $paragraph) : ?>
        <p><?php echo e($paragraph); ?></p>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="next-cta">
    <div class="section-inner section-inner--narrow">
      <p class="section-label"><?php echo e($service['cta']['label']); ?></p>
      <h2><?php echo e($service['cta']['title']); ?></h2>
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
