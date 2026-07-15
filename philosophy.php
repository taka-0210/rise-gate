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

$philosophy_cta = $philosophy['cta'];
if (($philosophy_cta['label'] ?? '') === 'Method') {
  $philosophy_cta['title'] = "この考え方を、\n続けられる進め方へ。";
  $philosophy_cta['body'] = 'ライズゲートは、考え方だけで終わらせません。現場の状況を整理し、会社に合わせて使いながら育てられる形へ落とし込んでいきます。';
  $philosophy_cta['link']['label'] = '進め方を見る';
  $philosophy_cta['link']['url'] = 'method.php';
}
?>

<main>
  <section class="page-hero hero-scene hero-scene--philosophy">
    <div class="section-inner section-inner--narrow">
      <p class="section-label"><?php echo e($philosophy['hero']['label']); ?></p>
      <h1><?php echo responsive_text($philosophy['hero'], 'title'); ?></h1>
      <p class="hero-subcopy"><?php echo responsive_text($philosophy['hero'], 'subtitle'); ?></p>
      <p class="section-lead"><?php echo e($philosophy['hero']['lead']); ?></p>
    </div>
  </section>

  <?php foreach ($philosophy['definitions'] as $definition) : ?>
    <section class="definition-section definition-section--<?php echo e($definition['id']); ?>">
      <div class="section-inner section-inner--narrow">
        <p class="section-label"><?php echo e($definition['label']); ?></p>
        <h2><?php echo responsive_text($definition, 'title'); ?></h2>
        <?php foreach ($definition['body'] as $paragraph) : ?>
          <p><?php echo e($paragraph); ?></p>
        <?php endforeach; ?>
      </div>
    </section>
  <?php endforeach; ?>

  <section class="means-section">
    <div class="section-inner section-inner--narrow">
      <p class="section-label"><?php echo e($philosophy['means']['label']); ?></p>
      <h2><?php echo responsive_text($philosophy['means'], 'title'); ?></h2>
      <?php foreach ($philosophy['means']['body'] as $paragraph) : ?>
        <p><?php echo e($paragraph); ?></p>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="values-section">
    <div class="section-inner">
      <div class="section-heading">
        <p class="section-label"><?php echo e($philosophy['values']['label']); ?></p>
        <h2><?php echo responsive_text($philosophy['values'], 'title'); ?></h2>
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
      <p class="section-label"><?php echo e($philosophy_cta['label']); ?></p>
      <h2><?php echo responsive_text($philosophy_cta, 'title'); ?></h2>
      <p><?php echo e($philosophy_cta['body']); ?></p>
      <a class="button button--primary" href="<?php echo e($philosophy_cta['link']['url']); ?>">
        <?php echo e($philosophy_cta['link']['label']); ?>
      </a>
    </div>
  </section>
</main>

<?php include __DIR__ . '/include/footer.php'; ?>
