<?php
$site = require __DIR__ . '/data/site.php';
$navigation = require __DIR__ . '/data/navigation.php';
$service = require __DIR__ . '/data/service.php';
require __DIR__ . '/include/functions.php';

$current_page = 'service';
$page_title = '業務システム開発';
$page_description = '現在の業務を整理し、現場で使いながら育てられる業務システムを設計・開発します。';
$service_item = $service['services']['items'][1];
$feature = $service['features']['items'][1];
$agile = $feature['agile'];
include __DIR__ . '/include/head.php';
include __DIR__ . '/include/header.php';
?>

<main>
  <section class="page-hero hero-scene hero-scene--service">
    <div class="section-inner section-inner--narrow">
      <p class="section-label">02 / Business System Development</p>
      <h1>現場の仕事に合い、<br>使いながら育てられるシステム。</h1>
      <p class="section-lead">現在の業務を整理し、必要な機能と優先順位を設計。導入後の改善まで見据えて開発します。</p>
    </div>
  </section>

  <section class="service-offering-section service-offering-section--business-system">
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
        <div class="service-feature-methods">
          <?php foreach ($feature['methods'] as $method) : ?><div><h4><?php echo e($method['title']); ?></h4><p><?php echo e($method['body']); ?></p></div><?php endforeach; ?>
        </div>
        <p class="service-feature-note"><?php echo e($feature['note']); ?></p>
      </article>
    </div>

    <div class="section-inner service-agile">
      <div class="service-agile__heading">
        <p class="section-label"><?php echo e($agile['label']); ?></p><h3><?php echo e($agile['title']); ?></h3>
        <p class="service-agile__experience"><?php echo e($agile['experience']); ?></p><p><?php echo e($agile['body']); ?></p>
      </div>
      <div class="service-agile__comparison">
        <?php foreach ($agile['comparison'] as $method) : ?>
          <article class="service-agile-method<?php echo !empty($method['recommended']) ? ' service-agile-method--recommended' : ''; ?>">
            <p class="section-label"><?php echo e($method['label']); ?></p><h4><?php echo e($method['title']); ?></h4><p><?php echo e($method['lead']); ?></p>
            <ol class="service-agile-method__flow"><?php foreach ($method['flow'] as $step) : ?><li><?php echo e($step); ?></li><?php endforeach; ?></ol>
            <p class="service-agile-method__note"><?php echo e($method['note']); ?></p>
          </article>
        <?php endforeach; ?>
      </div>
      <div class="service-agile__cycle-wrap">
        <p class="section-label">Continuous Development Cycle</p><h4>月額開発で、現場と開発のPDCAを回す。</h4>
        <ol class="service-agile-cycle"><?php foreach ($agile['cycle'] as $step) : ?><li><span><?php echo e($step['number']); ?></span><strong><?php echo e($step['title']); ?></strong><p><?php echo e($step['body']); ?></p></li><?php endforeach; ?></ol>
        <p class="service-agile__caption">※ <?php echo e($agile['caption']); ?></p>
      </div>
    </div>
  </section>

  <section class="service-team-section">
    <div class="section-inner">
      <div class="section-heading"><p class="section-label"><?php echo e($service['team']['label']); ?></p><h2><?php echo responsive_text($service['team'], 'title'); ?></h2><p><?php echo e($service['team']['lead']); ?></p></div>
      <div class="service-team-diagram" aria-label="業務システム開発を支える役割">
        <div class="service-team-diagram__center"><span><?php echo e($service['team']['center_label']); ?></span><strong><?php echo responsive_text($service['team'], 'center_title'); ?></strong></div>
        <div class="service-team-diagram__roles"><?php foreach ($service['team']['experts'] as $expert) : ?><article class="service-team-card"><p class="section-label"><?php echo e($expert['label']); ?></p><h3><?php echo e($expert['title']); ?></h3><p><?php echo e($expert['body']); ?></p></article><?php endforeach; ?></div>
      </div>
    </div>
  </section>

  <section class="service-project-section">
    <div class="section-inner"><div class="section-heading"><p class="section-label"><?php echo e($service['flow']['label']); ?></p><h2><?php echo responsive_text($service['flow'], 'title'); ?></h2></div>
      <div class="service-project-flow"><?php foreach ($service['flow']['steps'] as $step) : ?><article class="service-project-step"><span><?php echo e($step['number']); ?></span><h3><?php echo e($step['title']); ?></h3><p><?php echo e($step['body']); ?></p></article><?php endforeach; ?></div>
    </div>
  </section>

  <section class="service-related"><div class="section-inner"><p class="section-label">Related Service</p><a class="service-related__link" href="prototype-development.php"><span>完成前に操作と方向性を確かめたい方へ</span><strong>プロトタイプ開発を見る</strong></a></div></section>
  <section class="next-cta"><div class="section-inner section-inner--narrow"><p class="section-label">Contact</p><h2>業務やシステムの課題を、<br>お聞かせください。</h2><p>内容がまだ具体的でない段階でも、現在の仕事の流れから一緒に整理します。</p><a class="button button--primary" href="contact.php">お問い合わせ</a></div></section>
</main>

<?php include __DIR__ . '/include/footer.php'; ?>
