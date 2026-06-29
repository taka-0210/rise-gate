<?php
$site = require __DIR__ . '/data/site.php';
$navigation = require __DIR__ . '/data/navigation.php';
$company = require __DIR__ . '/data/company.php';
require __DIR__ . '/include/functions.php';

$current_page = 'company';
$page_title = $company['meta']['title'];
$page_description = $company['meta']['description'];

include __DIR__ . '/include/head.php';
include __DIR__ . '/include/header.php';
?>

<main>
  <section class="page-hero hero-scene hero-scene--company">
    <div class="section-inner section-inner--narrow">
      <p class="section-label"><?php echo e($company['hero']['label']); ?></p>
      <h1><?php echo e($company['hero']['title']); ?></h1>

    </div>
  </section>

  <section class="company-message">
    <div class="section-inner section-inner--narrow">
      <p class="section-label"><?php echo e($company['message']['label']); ?></p>
      <h2><?php echo e($company['message']['title']); ?></h2>
      <?php foreach ($company['message']['body'] as $paragraph) : ?>
        <p><?php echo e($paragraph); ?></p>
      <?php endforeach; ?>
      <p class="message-signature"><?php echo e($company['message']['signature']); ?></p>
    </div>
  </section>

  <section class="company-profile">
    <div class="section-inner">
      <div class="representative-profile">
        <figure class="representative-profile__photo">
          <img src="image/profile/representative.png" alt="ライズゲート代表者のポートレート" loading="lazy">
        </figure>
        <div>
          <p class="section-label"><?php echo e($company['profile']['label']); ?></p>
          <h2><?php echo e($company['profile']['title']); ?></h2>
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
      <h2><?php echo e($company['cta']['title']); ?></h2>
      <p><?php echo e($company['cta']['body']); ?></p>
      <a class="button button--primary" href="<?php echo e($company['cta']['link']['url']); ?>">
        <?php echo e($company['cta']['link']['label']); ?>
      </a>
    </div>
  </section>
</main>

<?php include __DIR__ . '/include/footer.php'; ?>
