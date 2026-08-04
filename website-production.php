<?php
$site = require __DIR__ . '/data/site.php';
$navigation = require __DIR__ . '/data/navigation.php';
$service = require __DIR__ . '/data/service.php';
require __DIR__ . '/include/functions.php';

$current_page = 'service';
$page_title = 'ホームページ制作';
$page_description = '会社やサービスの強みを整理し、自社で更新しながら育てられるホームページを制作します。';
$service_item = $service['services']['items'][0];
$feature = $service['features']['items'][0];
$flow = $service['flow'];
include __DIR__ . '/include/head.php';
include __DIR__ . '/include/header.php';
?>

<main>
  <section class="page-hero hero-scene hero-scene--service">
    <div class="section-inner section-inner--narrow">
      <p class="section-label">01 / Website Production</p>
      <h1>会社の魅力を伝え、<br>自社で育てられるホームページ。</h1>
      <p class="section-lead">情報の整理からデザイン、制作、公開後の更新まで。会社の発信に合ったホームページを設計します。</p>
    </div>
  </section>

  <section class="service-offering-section service-offering-section--website">
    <div class="section-inner service-offering__layout">
      <div class="service-offering__summary">
        <p class="section-label"><?php echo e($service_item['label']); ?></p>
        <h2><?php echo e($service_item['title']); ?></h2>
        <p class="service-offering__lead"><?php echo e($service_item['body']); ?></p>
        <ul class="service-offering__examples">
          <?php foreach ($service_item['examples'] as $example) : ?><li><?php echo e($example); ?></li><?php endforeach; ?>
        </ul>
      </div>
      <article class="service-feature-card service-offering__feature">
        <p class="section-label"><?php echo e($feature['label']); ?></p>
        <h3><?php echo e($feature['title']); ?></h3>
        <p><?php echo e($feature['body']); ?></p>
        <ul class="service-feature-list">
          <?php foreach ($feature['points'] as $point) : ?><li><?php echo e($point); ?></li><?php endforeach; ?>
        </ul>
        <p class="service-feature-note"><?php echo e($feature['note']); ?></p>
      </article>
    </div>
  </section>

  <section class="service-project-section">
    <div class="section-inner">
      <div class="section-heading">
        <p class="section-label"><?php echo e($flow['label']); ?></p>
        <h2><?php echo responsive_text($flow, 'title'); ?></h2>
      </div>
      <div class="service-project-flow">
        <?php foreach ($flow['steps'] as $step) : ?>
          <article class="service-project-step"><span><?php echo e($step['number']); ?></span><h3><?php echo e($step['title']); ?></h3><p><?php echo e($step['body']); ?></p></article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="service-related">
    <div class="section-inner">
      <p class="section-label">Other Service</p>
      <a class="service-related__link" href="business-system-development.php"><span>業務システムについて相談したい方へ</span><strong>業務システム開発を見る</strong></a>
    </div>
  </section>

  <section class="next-cta">
    <div class="section-inner section-inner--narrow">
      <p class="section-label">Contact</p><h2>ホームページについて、<br>ご希望をお聞かせください。</h2>
      <p>新規制作、リニューアル、自社更新の仕組みづくりについてご相談いただけます。</p>
      <a class="button button--primary" href="contact.php">お問い合わせ</a>
    </div>
  </section>
</main>

<?php include __DIR__ . '/include/footer.php'; ?>
