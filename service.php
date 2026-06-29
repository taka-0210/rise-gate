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
        <p class="section-label">Fields</p>
        <h2>改善文化を支える、4つの手段。</h2>
        <p>それぞれは独立した商品ではありません。会社の状態に合わせて組み合わせる、改善の選択肢です。</p>
      </div>

      <div class="service-field-list">
        <?php foreach ($service['fields'] as $field) : ?>
          <article class="service-field">
            <p class="section-label"><?php echo e($field['label']); ?></p>
            <h3><?php echo e($field['title']); ?></h3>
            <p><?php echo e($field['summary']); ?></p>
            <ul class="simple-list">
              <?php foreach ($field['items'] as $item) : ?>
                <li><?php echo e($item); ?></li>
              <?php endforeach; ?>
            </ul>
            <p class="field-note"><?php echo e($field['note']); ?></p>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="service-choice">
    <div class="section-inner section-inner--narrow">
      <p class="section-label"><?php echo e($service['choice']['label']); ?></p>
      <h2><?php echo e($service['choice']['title']); ?></h2>
      <?php foreach ($service['choice']['body'] as $paragraph) : ?>
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
