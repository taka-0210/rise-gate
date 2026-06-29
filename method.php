<?php
$site = require __DIR__ . '/data/site.php';
$navigation = require __DIR__ . '/data/navigation.php';
$method = require __DIR__ . '/data/method.php';
require __DIR__ . '/include/functions.php';

$current_page = 'method';
$page_title = $method['meta']['title'];
$page_description = $method['meta']['description'];

include __DIR__ . '/include/head.php';
include __DIR__ . '/include/header.php';
?>

<main>
  <section class="page-hero hero-scene hero-scene--method">
    <div class="section-inner section-inner--narrow">
      <p class="section-label"><?php echo e($method['hero']['label']); ?></p>
      <h1><?php echo e($method['hero']['title']); ?></h1>
      <p class="section-lead"><?php echo e($method['hero']['lead']); ?></p>
    </div>
  </section>

  <section class="method-intro">
    <div class="section-inner section-inner--narrow">
      <p class="section-label"><?php echo e($method['intro']['label']); ?></p>
      <h2><?php echo e($method['intro']['title']); ?></h2>
      <?php foreach ($method['intro']['body'] as $paragraph) : ?>
        <p><?php echo e($paragraph); ?></p>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="method-steps">
    <div class="section-inner">
      <div class="section-heading">
        <p class="section-label">Process</p>
        <h2>改善が続く形へ、
段階を分けて整えます。</h2>
      </div>
      <div class="method-step-list">
        <?php foreach ($method['steps'] as $step) : ?>
          <article class="method-step">
            <p class="section-label"><?php echo e($step['label']); ?></p>
            <h3><?php echo e($step['title']); ?></h3>
            <p><?php echo e($step['body']); ?></p>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="method-stance">
    <div class="section-inner section-inner--narrow">
      <p class="section-label"><?php echo e($method['stance']['label']); ?></p>
      <h2><?php echo e($method['stance']['title']); ?></h2>
      <?php foreach ($method['stance']['body'] as $paragraph) : ?>
        <p><?php echo e($paragraph); ?></p>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="next-cta">
    <div class="section-inner section-inner--narrow">
      <p class="section-label"><?php echo e($method['cta']['label']); ?></p>
      <h2><?php echo e($method['cta']['title']); ?></h2>
      <p><?php echo e($method['cta']['body']); ?></p>
      <div class="button-group">
        <a class="button button--primary" href="<?php echo e($method['cta']['link']['url']); ?>">
          <?php echo e($method['cta']['link']['label']); ?>
        </a>
        <a class="button button--secondary" href="<?php echo e($method['cta']['sub_link']['url']); ?>">
          <?php echo e($method['cta']['sub_link']['label']); ?>
        </a>
      </div>
    </div>
  </section>
</main>

<?php include __DIR__ . '/include/footer.php'; ?>
