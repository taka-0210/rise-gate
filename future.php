<?php
$site = require __DIR__ . '/data/site.php';
$navigation = require __DIR__ . '/data/navigation.php';
$future = require __DIR__ . '/data/future.php';
require __DIR__ . '/include/functions.php';

$current_page = 'future';
$page_title = $future['meta']['title'];
$page_description = $future['meta']['description'];

include __DIR__ . '/include/head.php';
include __DIR__ . '/include/header.php';
?>

<main>
  <section class="page-hero hero-scene hero-scene--future">
    <div class="section-inner section-inner--narrow">
      <p class="section-label"><?php echo e($future['hero']['label']); ?></p>
      <h1><?php echo e($future['hero']['title']); ?></h1>
      <p class="section-lead"><?php echo e($future['hero']['lead']); ?></p>
    </div>
  </section>

  <?php foreach ($future['sections'] as $section) : ?>
    <section class="future-section">
      <div class="section-inner section-inner--narrow">
        <p class="section-label"><?php echo e($section['label']); ?></p>
        <h2><?php echo e($section['title']); ?></h2>
        <?php foreach ($section['body'] as $paragraph) : ?>
          <p><?php echo e($paragraph); ?></p>
        <?php endforeach; ?>
      </div>
    </section>
  <?php endforeach; ?>

  <section class="future-question">
    <div class="section-inner section-inner--narrow">
      <p class="section-label"><?php echo e($future['question']['label']); ?></p>
      <h2><?php echo e($future['question']['title']); ?></h2>
      <?php foreach ($future['question']['body'] as $paragraph) : ?>
        <p><?php echo e($paragraph); ?></p>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="next-cta">
    <div class="section-inner section-inner--narrow">
      <p class="section-label"><?php echo e($future['cta']['label']); ?></p>
      <h2><?php echo e($future['cta']['title']); ?></h2>
      <p><?php echo e($future['cta']['body']); ?></p>
      <div class="button-group">
        <a class="button button--primary" href="<?php echo e($future['cta']['link']['url']); ?>">
          <?php echo e($future['cta']['link']['label']); ?>
        </a>
        <a class="button button--secondary" href="<?php echo e($future['cta']['sub_link']['url']); ?>">
          <?php echo e($future['cta']['sub_link']['label']); ?>
        </a>
      </div>
    </div>
  </section>
</main>

<?php include __DIR__ . '/include/footer.php'; ?>
