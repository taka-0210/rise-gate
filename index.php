<?php
$site = require __DIR__ . '/data/site.php';
$navigation = require __DIR__ . '/data/navigation.php';
$home = require __DIR__ . '/data/home.php';
require __DIR__ . '/include/functions.php';

$current_page = 'home';
$page_title = $site['name'];
$page_description = $site['description'];

include __DIR__ . '/include/head.php';
include __DIR__ . '/include/header.php';
?>

<main>
  <section class="home-hero hero-scene hero-scene--home">
    <div class="section-inner hero-layout">
      <div class="hero-copy">
        <p class="section-label"><?php echo e($home['hero']['label']); ?></p>
        <h1><?php echo responsive_text($home['hero'], 'title'); ?></h1>
        <p class="hero-subcopy"><?php echo responsive_text($home['hero'], 'subtitle'); ?></p>
        <p class="section-lead"><?php echo responsive_text($home['hero'], 'lead'); ?></p>
        <div class="button-group">
          <a class="button button--primary" href="<?php echo e($home['hero']['primary_cta']['url']); ?>">
            <?php echo e($home['hero']['primary_cta']['label']); ?>
          </a>
          <a class="button button--secondary" href="<?php echo e($home['hero']['secondary_cta']['url']); ?>">
            <?php echo e($home['hero']['secondary_cta']['label']); ?>
          </a>
        </div>
      </div>
    </div>
  </section>

  <section class="sme-focus-section">
    <div class="section-inner sme-focus-layout">
      <div class="sme-focus-copy">
        <p class="section-label"><?php echo e($home['sme_focus']['label']); ?></p>
        <h2><?php echo responsive_text($home['sme_focus'], 'title'); ?></h2>
        <p class="section-lead"><?php echo e($home['sme_focus']['lead']); ?></p>
        <?php foreach ($home['sme_focus']['body'] as $paragraph) : ?>
          <p><?php echo e($paragraph); ?></p>
        <?php endforeach; ?>
      </div>

      <div class="sme-focus-stats" aria-label="中小企業に関する統計">
        <?php foreach ($home['sme_focus']['stats'] as $stat) : ?>
          <div class="sme-focus-stat">
            <strong>
              <?php if ($stat['number'] === '約7割') : ?>
                <span class="sme-focus-stat__unit">約</span><span>7</span><span class="sme-focus-stat__unit">割</span>
              <?php else : ?>
                <?php echo e($stat['number']); ?>
              <?php endif; ?>
            </strong>
            <span><?php echo e($stat['label']); ?></span>
          </div>
        <?php endforeach; ?>
      </div>

      <p class="sme-focus-closing">
        <?php foreach ($home['sme_focus']['closing'] as $index => $line) : ?>
          <?php if ($index === 2) : ?>
            <span class="sme-focus-closing__last">
              <span class="sme-focus-closing__desktop"><?php echo e($line); ?></span>
              <span class="sme-focus-closing__mobile">日本はもっと<br>良くなる</span>
            </span>
          <?php else : ?>
            <span><?php echo e($line); ?></span>
          <?php endif; ?>
        <?php endforeach; ?>
      </p>
    </div>
  </section>

  <section class="nurture-bridge-section">
    <div class="section-inner nurture-bridge-layout">
      <div class="nurture-bridge-heading">
        <p class="section-label"><?php echo e($home['nurture_bridge']['label']); ?></p>
        <h2>
          <span class="nurture-bridge-heading__intro"><?php echo e($home['nurture_bridge']['title'][0]); ?></span>
          <span class="nurture-bridge-heading__contrast">
            <span class="nurture-bridge-heading__make"><?php echo e($home['nurture_bridge']['title'][1]); ?></span>
            <span class="nurture-bridge-heading__not"><?php echo e($home['nurture_bridge']['title'][2]); ?></span>
          </span>
          <span class="nurture-bridge-heading__nurture"><?php echo e($home['nurture_bridge']['title'][3]); ?></span>
          <span class="nurture-bridge-heading__choice"><?php echo e($home['nurture_bridge']['title'][4]); ?></span>
        </h2>
      </div>

      <div class="nurture-bridge-copy">
        <?php foreach ($home['nurture_bridge']['body'] as $index => $paragraph) : ?>
          <p<?php echo $index >= 2 ? ' class="nurture-bridge-copy__conclusion"' : ''; ?>><?php echo e($paragraph); ?></p>
        <?php endforeach; ?>
        <a class="text-link text-link--statement nurture-bridge-link" href="<?php echo e($home['nurture_bridge']['link']['url']); ?>">
          <?php echo e($home['nurture_bridge']['link']['label']); ?>
        </a>
      </div>
    </div>
  </section>

  <section class="service-teaser">
    <div class="section-inner">
      <div class="section-heading">
        <p class="section-label"><?php echo e($home['service_teaser']['label']); ?></p>
        <h2><?php echo responsive_text($home['service_teaser'], 'title'); ?></h2>
        <p class="service-teaser__subtitle"><?php echo responsive_text($home['service_teaser'], 'subtitle'); ?></p>
        <p><?php echo e($home['service_teaser']['lead']); ?></p>
      </div>
      <div class="content-grid content-grid--two">
        <?php foreach ($home['service_teaser']['items'] as $index => $item) : ?>
          <article class="content-card">
            <span class="service-teaser__number"><?php echo e(str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)); ?></span>
            <h3><?php echo e($item['title']); ?></h3>
            <p class="service-teaser__catch"><?php echo e($item['catch']); ?></p>
            <p><?php echo e($item['body']); ?></p>
          </article>
        <?php endforeach; ?>
      </div>
      <div class="text-link-group">
        <?php foreach ($home['service_teaser']['links'] as $link) : ?>
          <a class="text-link" href="<?php echo e($link['url']); ?>">
            <?php echo e($link['label']); ?>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="problem-section">
    <div class="section-inner">
      <div class="section-heading">
        <p class="section-label"><?php echo e($home['problems']['label']); ?></p>
        <h2><?php echo responsive_text($home['problems'], 'title'); ?></h2>
        <p><?php echo e($home['problems']['lead']); ?></p>
      </div>
      <div class="content-grid content-grid--four">
        <?php foreach ($home['problems']['items'] as $item) : ?>
          <article class="content-card">
            <h3><?php echo e($item['title']); ?></h3>
            <p><?php echo e($item['body']); ?></p>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="environment-section">
    <div class="section-inner">
      <div class="section-heading">
        <p class="section-label"><?php echo e($home['environment']['label']); ?></p>
        <h2><?php echo responsive_text($home['environment'], 'title'); ?></h2>
        <p><?php echo e($home['environment']['lead']); ?></p>
      </div>
      <div class="content-grid content-grid--three">
        <?php foreach ($home['environment']['items'] as $item) : ?>
          <article class="content-card environment-card">
            <h3><?php echo e($item['title']); ?></h3>
            <p><?php echo e($item['body']); ?></p>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="brand-message">
    <div class="section-inner section-inner--narrow">
      <p class="section-label"><?php echo e($home['brand_message']['label']); ?></p>
      <h2><?php echo responsive_text($home['brand_message'], 'title'); ?></h2>
      <?php foreach ($home['brand_message']['body'] as $paragraph) : ?>
        <p><?php echo e($paragraph); ?></p>
      <?php endforeach; ?>
      <div class="text-link-group text-link-group--statement">
        <?php foreach ($home['brand_message']['links'] as $link) : ?>
          <a class="text-link" href="<?php echo e($link['url']); ?>">
            <?php echo e($link['label']); ?>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="approach-section">
    <div class="section-inner">
      <div class="section-heading">
        <p class="section-label"><?php echo e($home['approach']['label']); ?></p>
        <h2><?php echo responsive_text($home['approach'], 'title'); ?></h2>
        <p><?php echo e($home['approach']['lead']); ?></p>
      </div>
      <ol class="step-list">
        <?php foreach ($home['approach']['steps'] as $index => $step) : ?>
          <li>
            <span><?php echo e(str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)); ?></span>
            <?php echo e($step); ?>
          </li>
        <?php endforeach; ?>
      </ol>
      <a class="text-link" href="<?php echo e($home['approach']['link']['url']); ?>">
        <?php echo e($home['approach']['link']['label']); ?>
      </a>
    </div>
  </section>

  <section class="next-cta">
    <div class="section-inner section-inner--narrow">
      <p class="section-label"><?php echo e($home['cta']['label']); ?></p>
      <h2><?php echo responsive_text($home['cta'], 'title'); ?></h2>
      <p><?php echo e($home['cta']['body']); ?></p>
      <a class="button button--primary" href="<?php echo e($home['cta']['link']['url']); ?>">
        <?php echo e($home['cta']['link']['label']); ?>
      </a>
    </div>
  </section>
</main>

<?php include __DIR__ . '/include/footer.php'; ?>
