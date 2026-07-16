<?php
$site = require __DIR__ . '/data/site.php';
$navigation = require __DIR__ . '/data/navigation.php';
$company = require __DIR__ . '/data/company.php';
require __DIR__ . '/include/functions.php';

$current_page = 'company';
$page_title = $company['meta']['title'];
$page_description = $company['meta']['description'];
$representative_image_path = __DIR__ . '/image/profile/representative.png';
$representative_image_version = file_exists($representative_image_path) ? (string) filemtime($representative_image_path) : '1';

include __DIR__ . '/include/head.php';
include __DIR__ . '/include/header.php';
?>

<main>
  <section class="page-hero hero-scene hero-scene--company">
    <div class="section-inner section-inner--narrow">
      <p class="section-label"><?php echo e($company['hero']['label']); ?></p>
      <h1><?php echo responsive_text($company['hero'], 'title'); ?></h1>
      <p class="section-lead"><?php echo e($company['hero']['lead']); ?></p>
    </div>
  </section>

  <section class="company-message">
    <div class="section-inner company-message__layout">
      <div class="company-message__heading">
        <p class="section-label"><?php echo e($company['message']['label']); ?></p>
        <h2><?php echo responsive_text($company['message'], 'title'); ?></h2>
      </div>
      <div class="company-message__body">
        <?php foreach ($company['message']['body'] as $index => $paragraph) : ?>
          <?php if ($index === 3) : ?>
            <p class="company-message__highlight"><?php echo e($company['message']['highlight']); ?></p>
          <?php endif; ?>
          <p><?php echo e($paragraph); ?></p>
        <?php endforeach; ?>
        <div class="message-signature">
          <span><?php echo e($company['message']['signature_role']); ?></span>
          <strong><?php echo e($company['message']['signature_name']); ?></strong>
        </div>
      </div>
    </div>
  </section>

  <section class="company-logo-meaning">
    <div class="section-inner company-logo-meaning__layout">
      <div class="company-logo-meaning__visual">
        <img src="<?php echo e($company['logo_meaning']['image']); ?>" alt="<?php echo e($company['logo_meaning']['image_alt']); ?>" loading="lazy">
        <div class="company-logo-meaning__words" aria-label="RISE GATEの意味">
          <?php foreach ($company['logo_meaning']['words'] as $item) : ?>
            <div>
              <strong><?php echo e($item['word']); ?></strong>
              <span><?php echo e($item['meaning']); ?></span>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="company-logo-meaning__copy">
        <p class="section-label"><?php echo e($company['logo_meaning']['label']); ?></p>
        <h2><?php echo responsive_text($company['logo_meaning'], 'title'); ?></h2>
        <p class="section-lead"><?php echo e($company['logo_meaning']['lead']); ?></p>
        <?php foreach ($company['logo_meaning']['body'] as $paragraph) : ?>
          <p><?php echo e($paragraph); ?></p>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="company-profile">
    <div class="section-inner">
      <div class="representative-profile">
        <figure class="representative-profile__photo">
          <img src="image/profile/representative.png?v=<?php echo e($representative_image_version); ?>" alt="ライズゲート代表者のポートレート" loading="lazy">
        </figure>
        <div>
          <p class="section-label"><?php echo e($company['profile']['label']); ?></p>
          <h2><?php echo responsive_text($company['profile'], 'title'); ?></h2>
          <p class="representative-profile__lead"><?php echo e($company['profile']['lead']); ?></p>
          <div class="representative-profile__body">
            <?php foreach ($company['profile']['body'] as $paragraph) : ?>
              <p><?php echo e($paragraph); ?></p>
            <?php endforeach; ?>
          </div>
          <div class="representative-profile__highlights" aria-label="代表プロフィールの要点">
            <?php foreach ($company['profile']['highlights'] as $highlight) : ?>
              <?php if (($highlight['url'] ?? '') !== '') : ?>
              <a href="<?php echo e($highlight['url']); ?>" target="_blank" rel="noopener noreferrer">
                <strong><?php echo e($highlight['label']); ?></strong>
                <p><?php echo e($highlight['text']); ?></p>
                <span class="representative-profile__link-label">詳しく見る</span>
              </a>
              <?php else : ?>
              <article>
                <strong><?php echo e($highlight['label']); ?></strong>
                <p><?php echo e($highlight['text']); ?></p>
              </article>
              <?php endif; ?>
            <?php endforeach; ?>
          </div>
          <dl class="profile-list">
            <?php foreach ($company['profile']['items'] as $term => $description) : ?>
              <div>
                <dt><?php echo e($term); ?></dt>
                <dd><?php echo e($description); ?></dd>
              </div>
            <?php endforeach; ?>
          </dl>
        </div>
      </div>
    </div>
  </section>

  <section class="next-cta">
    <div class="section-inner section-inner--narrow">
      <p class="section-label"><?php echo e($company['cta']['label']); ?></p>
      <h2><?php echo responsive_text($company['cta'], 'title'); ?></h2>
      <p><?php echo e($company['cta']['body']); ?></p>
      <a class="button button--primary" href="<?php echo e($company['cta']['link']['url']); ?>">
        <?php echo e($company['cta']['link']['label']); ?>
      </a>
    </div>
  </section>
</main>

<?php include __DIR__ . '/include/footer.php'; ?>
