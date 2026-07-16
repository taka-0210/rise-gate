<?php
$site = require __DIR__ . '/data/site.php';
$navigation = require __DIR__ . '/data/navigation.php';
$future = require __DIR__ . '/data/future.php';
$improvement_masters = require __DIR__ . '/data/improvement_masters.php';
require __DIR__ . '/include/functions.php';

$current_page = 'future';
$page_title = $future['meta']['title'];
$page_description = $future['meta']['description'];

$published_masters = array_values(array_filter($improvement_masters, function ($master) {
    return ($master['status'] ?? '') === 'published';
}));

function master_map_position(array $master): array
{
    if (isset($master['map_x'], $master['map_y']) && is_numeric($master['map_x']) && is_numeric($master['map_y'])) {
        $left = (float) $master['map_x'];
        $top = (float) $master['map_y'];
    } else {
        $latitude = (float) ($master['latitude'] ?? 35.681236);
        $longitude = (float) ($master['longitude'] ?? 139.767125);
        $left = (($longitude - 122) / 32) * 100;
        $top = ((46 - $latitude) / 22) * 100;
    }

    return [
        'left' => max(4, min(96, $left)),
        'top' => max(4, min(96, $top)),
    ];
}

include __DIR__ . '/include/head.php';
include __DIR__ . '/include/header.php';
?>

<main>
  <section class="page-hero hero-scene hero-scene--future">
    <div class="section-inner section-inner--narrow">
      <p class="section-label"><?php echo e($future['hero']['label']); ?></p>
      <h1><?php echo responsive_text($future['hero'], 'title'); ?></h1>
      <p class="section-lead"><?php echo e($future['hero']['lead']); ?></p>
    </div>
  </section>

  <?php foreach ($future['sections'] as $section) : ?>
    <section class="future-section">
      <div class="section-inner section-inner--narrow">
        <p class="section-label"><?php echo e($section['label']); ?></p>
        <h2><?php echo responsive_text($section, 'title'); ?></h2>
        <?php foreach ($section['body'] as $paragraph) : ?>
          <p><?php echo e($paragraph); ?></p>
        <?php endforeach; ?>
        <?php if (!empty($section['link'])) : ?>
          <a class="button button--primary" href="<?php echo e($section['link']['url']); ?>">
            <?php echo e($section['link']['label']); ?>
          </a>
        <?php endif; ?>
      </div>
    </section>
  <?php endforeach; ?>

  <section id="improvement-masters" class="master-map-section">
    <div class="section-inner">
      <div class="section-heading">
        <p class="section-label">Improvement Masters</p>
        <h2><?php echo responsive_lines([
          'desktop' => ['改善が文化になる社会へ。'],
          'tablet' => ['改善が文化になる社会へ。'],
          'mobile' => ['改善が文化になる', '社会へ。'],
        ]); ?></h2>
        <p>ライズゲートのプログラムを受講し、各地域で改善を進める人たちを「改善マスター」として記録していきます。</p>
      </div>

      <div class="master-map-layout">
        <div class="japan-map" aria-label="改善マスターの地域マップ">
          <div class="japan-map__canvas">
            <span class="japan-map__land japan-map__land--hokkaido"></span>
            <span class="japan-map__land japan-map__land--honshu"></span>
            <span class="japan-map__land japan-map__land--shikoku"></span>
            <span class="japan-map__land japan-map__land--kyushu"></span>
            <span class="japan-map__land japan-map__land--okinawa"></span>

            <?php foreach ($published_masters as $master) : ?>
              <?php
              $position = master_map_position($master);
              ?>
              <span
                class="japan-map__marker"
                style="left: <?php echo e(number_format($position['left'], 3)); ?>%; top: <?php echo e(number_format($position['top'], 3)); ?>%;"
                tabindex="0"
                role="button"
                aria-label="<?php echo e(($master['name'] ?? '') . ' / ' . ($master['company_name'] ?? '') . ' / ' . ($master['prefecture'] ?? '')); ?>"
              >
                <span class="master-map-popup">
                  <?php if (($master['profile_image'] ?? '') !== '') : ?>
                    <img src="<?php echo e($master['profile_image']); ?>" alt="<?php echo e(($master['name'] ?? '') . 'さんの写真'); ?>">
                  <?php endif; ?>
                  <?php if (($master['company_name'] ?? '') !== '') : ?>
                    <span class="master-map-popup__company"><?php echo e($master['company_name']); ?></span>
                  <?php endif; ?>
                  <strong><?php echo e($master['name'] ?? ''); ?></strong>
                  <span><?php echo e(trim(($master['prefecture'] ?? '') . ' ' . ($master['city'] ?? ''))); ?></span>
                  <a class="master-map-popup__request" href="contact.php?master=<?php echo e(rawurlencode($master['slug'] ?? '')); ?>">相談する</a>
                  <?php if (($master['link_url'] ?? '') !== '') : ?>
                    <a href="<?php echo e($master['link_url']); ?>" target="_blank" rel="noopener">URLを開く</a>
                  <?php endif; ?>
                </span>
              </span>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="master-map-panel">
          <p class="section-label">Network</p>
          <h3><?php echo e((string) count($published_masters)); ?>人の改善マスター</h3>
          <p>まだ小さな点でも、地域に根づいた改善が増えていけば、会社の変化は社会の文化になっていきます。</p>
          <p class="master-map-panel__note">まだ立ち上がったばかり。<br>どんどん改善マスターのメンバーを増やしていきます。</p>

          <?php if (empty($published_masters)) : ?>
            <p class="master-map-empty">改善マスターはこれから登録していきます。</p>
          <?php else : ?>
            <div class="master-list">
              <?php foreach ($published_masters as $master) : ?>
                <article class="master-card">
                  <?php if (($master['profile_image'] ?? '') !== '') : ?>
                    <img class="master-card__image" src="<?php echo e($master['profile_image']); ?>" alt="<?php echo e(($master['name'] ?? '') . 'さんの写真'); ?>" loading="lazy">
                  <?php endif; ?>
                  <p class="master-card__area"><?php echo e(trim(($master['prefecture'] ?? '') . ' ' . ($master['city'] ?? ''))); ?></p>
                  <h4><?php echo e($master['name'] ?? ''); ?></h4>
                  <?php if (($master['company_name'] ?? '') !== '') : ?>
                    <p class="master-card__company"><?php echo e($master['company_name']); ?></p>
                  <?php endif; ?>
                  <?php if (($master['focus'] ?? '') !== '') : ?>
                    <p><?php echo e($master['focus']); ?></p>
                  <?php endif; ?>
                </article>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </section>

  <section class="future-question">
    <div class="section-inner section-inner--narrow">
      <p class="section-label"><?php echo e($future['question']['label']); ?></p>
      <h2><?php echo responsive_text($future['question'], 'title'); ?></h2>
      <?php foreach ($future['question']['body'] as $paragraph) : ?>
        <p><?php echo e($paragraph); ?></p>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="next-cta">
    <div class="section-inner section-inner--narrow">
      <p class="section-label"><?php echo e($future['cta']['label']); ?></p>
      <h2><?php echo responsive_text($future['cta'], 'title'); ?></h2>
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
