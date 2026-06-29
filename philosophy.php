<?php
$site = require __DIR__ . '/data/site.php';
$navigation = require __DIR__ . '/data/navigation.php';
$philosophy = require __DIR__ . '/data/philosophy.php';
require __DIR__ . '/include/functions.php';

$current_page = 'philosophy';
$page_title = $philosophy['meta']['title'];
$page_description = $philosophy['meta']['description'];

include __DIR__ . '/include/head.php';
include __DIR__ . '/include/header.php';
?>

<main>
  <section class="page-hero hero-scene hero-scene--philosophy">
    <div class="section-inner section-inner--narrow">
      <p class="section-label"><?php echo e($philosophy['hero']['label']); ?></p>
      <h1><?php echo e($philosophy['hero']['title']); ?></h1>
      <p class="hero-subcopy"><?php echo e($philosophy['hero']['subtitle']); ?></p>
      <p class="section-lead"><?php echo e($philosophy['hero']['lead']); ?></p>
    </div>
  </section>

  <?php foreach ($philosophy['definitions'] as $definition) : ?>
    <section class="definition-section definition-section--<?php echo e($definition['id']); ?>">
      <div class="section-inner section-inner--narrow">
        <p class="section-label"><?php echo e($definition['label']); ?></p>
        <h2><?php echo e($definition['title']); ?></h2>
        <?php foreach ($definition['body'] as $paragraph) : ?>
          <p><?php echo e($paragraph); ?></p>
        <?php endforeach; ?>
      </div>
    </section>
  <?php endforeach; ?>

  <section class="means-section">
    <div class="section-inner section-inner--narrow">
      <p class="section-label"><?php echo e($philosophy['means']['label']); ?></p>
      <h2><?php echo e($philosophy['means']['title']); ?></h2>
      <?php foreach ($philosophy['means']['body'] as $paragraph) : ?>
        <p><?php echo e($paragraph); ?></p>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="values-section">
    <div class="section-inner">
      <div class="section-heading">
        <p class="section-label"><?php echo e($philosophy['values']['label']); ?></p>
        <h2><?php echo e($philosophy['values']['title']); ?></h2>
      </div>
      <div class="content-grid content-grid--values">
        <?php foreach ($philosophy['values']['items'] as $item) : ?>
          <article class="content-card">
            <h3><?php echo e($item['title']); ?></h3>
            <p><?php echo e($item['body']); ?></p>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="next-cta">
    <div class="section-inner section-inner--narrow">
      <p class="section-label"><?php echo e($philosophy['cta']['label']); ?></p>
      <h2><?php echo e($philosophy['cta']['title']); ?></h2>
      <p><?php echo e($philosophy['cta']['body']); ?></p>
      <a class="button button--primary" href="<?php echo e($philosophy['cta']['link']['url']); ?>">
        <?php echo e($philosophy['cta']['link']['label']); ?>
      </a>
    </div>
  </section>
</main>

<?php include __DIR__ . '/include/footer.php'; ?>
