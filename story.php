<?php
$site = require __DIR__ . '/data/site.php';
$navigation = require __DIR__ . '/data/navigation.php';
$story = require __DIR__ . '/data/story.php';
require __DIR__ . '/include/functions.php';

$current_page = 'story';
$page_title = $story['meta']['title'];
$page_description = $story['meta']['description'];

include __DIR__ . '/include/head.php';
include __DIR__ . '/include/header.php';
?>

<main>
  <section class="page-hero hero-scene hero-scene--story">
    <div class="section-inner section-inner--narrow">
      <p class="section-label"><?php echo e($story['hero']['label']); ?></p>
      <h1><?php echo responsive_text($story['hero'], 'title'); ?></h1>
      <p class="section-lead"><?php echo e($story['hero']['lead']); ?></p>
    </div>
  </section>

  <?php foreach ($story['sections'] as $section) : ?>
    <?php $is_system_section = strtolower($section['label']) === 'system'; ?>
    <section class="story-section<?php echo $is_system_section ? ' story-section--system' : ''; ?>">
      <div class="section-inner section-inner--narrow">
        <p class="section-label"><?php echo e($section['label']); ?></p>
        <h2><?php echo responsive_text($section, 'title'); ?></h2>
        <?php foreach ($section['body'] as $paragraph) : ?>
          <p><?php echo e($paragraph); ?></p>
        <?php endforeach; ?>
        <?php if ($is_system_section) : ?>
          <div class="story-system-visual" aria-hidden="true">
            <img class="story-system-visual__devices" src="image/story/chubo-kun2.png" alt="">
          </div>
        <?php endif; ?>
      </div>
    </section>
  <?php endforeach; ?>

  <section class="next-cta">
    <div class="section-inner section-inner--narrow">
      <p class="section-label"><?php echo e($story['cta']['label']); ?></p>
      <h2><?php echo responsive_text($story['cta'], 'title'); ?></h2>
      <p><?php echo e($story['cta']['body']); ?></p>
      <a class="button button--primary" href="<?php echo e($story['cta']['link']['url']); ?>">
        <?php echo e($story['cta']['link']['label']); ?>
      </a>
    </div>
  </section>
</main>

<?php include __DIR__ . '/include/footer.php'; ?>
