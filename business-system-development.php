<?php
$site = require __DIR__ . '/data/site.php';
$navigation = require __DIR__ . '/data/navigation.php';
$service = require __DIR__ . '/data/service.php';
$prototype = require __DIR__ . '/data/prototype-development.php';
require __DIR__ . '/include/functions.php';

$current_page = 'system';
$page_title = '業務システム開発';
$page_description = '現在の業務を整理し、現場で使いながら育てられる業務システムを設計・開発します。';
$service_item = $service['services']['items'][1];
include __DIR__ . '/include/head.php';
include __DIR__ . '/include/header.php';
?>

<main>
  <section class="page-hero hero-scene hero-scene--service">
    <div class="section-inner section-inner--narrow">
      <p class="section-label">01 / Business System Development</p>
      <h1>業務システム開発は、<br>動く試作品から。</h1>
      <p class="section-lead">完成形を想像だけで決めず、実際に触って方向性を確認する。安心して改善できる進め方から、会社に合った仕組みをつくります。</p>
    </div>
  </section>

  <section class="business-prototype-intro">
    <div class="section-inner business-prototype-intro__layout">
      <div><p class="section-label">RISE GATE Development Method</p><h2>システムをつくる前に、<br>安心して改善できる進め方をつくる。</h2></div>
      <div><p>お客様が必要としているのは、システムそのものではありません。自社の仕事に合い、現場で使い続けられる仕組みです。</p><p>ライズゲートでは、本開発の前に動く試作品を用意します。実際に触り、違いや不足を確認し、方向性を合わせてから開発へ進みます。</p><p class="business-prototype-intro__strong">プロトタイプ開発は、ライズゲートの業務システム開発における標準の進め方です。</p></div>
    </div>
  </section>

  <section class="prototype-concerns"><div class="section-inner"><div class="section-heading"><p class="section-label"><?php echo e($prototype['concerns']['label']); ?></p><h2><?php echo e($prototype['concerns']['title']); ?></h2><p><?php echo e($prototype['concerns']['lead']); ?></p></div><ol class="prototype-concern-list"><?php foreach ($prototype['concerns']['items'] as $index => $item) : ?><li><span><?php echo str_pad((string)($index + 1), 2, '0', STR_PAD_LEFT); ?></span><?php echo e($item); ?></li><?php endforeach; ?></ol><p class="prototype-concerns__closing"><?php echo e($prototype['concerns']['closing']); ?></p></div></section>

  <section class="prototype-comparison"><div class="section-inner"><div class="section-heading"><p class="section-label"><?php echo e($prototype['comparison']['label']); ?></p><h2><?php echo e($prototype['comparison']['title']); ?></h2><p class="prototype-comparison__message"><?php echo e($prototype['comparison']['message']); ?></p></div><div class="prototype-comparison__grid"><?php foreach ($prototype['comparison']['methods'] as $method) : ?><article class="prototype-method<?php echo !empty($method['recommended']) ? ' prototype-method--risegate' : ''; ?>"><p class="section-label"><?php echo e($method['label']); ?></p><h3><?php echo e($method['title']); ?></h3><ol><?php foreach ($method['steps'] as $step) : ?><li><?php echo e($step); ?></li><?php endforeach; ?></ol><p><?php echo e($method['note']); ?></p></article><?php endforeach; ?></div></div></section>

  <section class="prototype-purpose"><div class="section-inner section-inner--narrow"><p class="section-label"><?php echo e($prototype['purpose']['label']); ?></p><h2><?php echo e($prototype['purpose']['title']); ?></h2><?php foreach ($prototype['purpose']['body'] as $paragraph) : ?><p><?php echo e($paragraph); ?></p><?php endforeach; ?><p class="prototype-key-message"><?php echo e($prototype['purpose']['message']); ?></p></div></section>

  <section class="prototype-confidence"><div class="section-inner"><div class="section-heading"><p class="section-label"><?php echo e($prototype['confidence']['label']); ?></p><h2><?php echo e($prototype['confidence']['title']); ?></h2><p><?php echo e($prototype['confidence']['body']); ?></p></div><div class="prototype-confidence__grid"><?php foreach ($prototype['confidence']['items'] as $item) : ?><article><p class="section-label"><?php echo e($item['label']); ?></p><h3><?php echo e($item['title']); ?></h3></article><?php endforeach; ?></div><p class="prototype-confidence__closing"><?php echo e($prototype['confidence']['closing']); ?></p></div></section>

  <section class="prototype-flow business-prototype-flow" id="prototype-first">
    <div class="section-inner"><div class="section-heading"><p class="section-label">Prototype First</p><h2>まずは、<br>動くものを。</h2><p>完成前に操作と方向性を確かめ、現場の声を本開発へ反映します。</p></div><ol class="prototype-flow__list"><?php foreach ($prototype['flow']['steps'] as $step) : ?><li><span><?php echo e($step['number']); ?></span><h3><?php echo e($step['title']); ?></h3><p><?php echo e($step['body']); ?></p><small><?php echo e($step['meta']); ?></small></li><?php endforeach; ?></ol></div>
  </section>

  <section class="prototype-trial"><div class="section-inner prototype-trial__box"><p class="section-label"><?php echo e($prototype['trial']['label']); ?></p><h2><?php echo e($prototype['trial']['title']); ?></h2><?php foreach ($prototype['trial']['body'] as $paragraph) : ?><p><?php echo e($paragraph); ?></p><?php endforeach; ?><ul><?php foreach ($prototype['trial']['conditions'] as $condition) : ?><li><?php echo e($condition); ?></li><?php endforeach; ?></ul><p class="prototype-trial__note"><?php echo e($prototype['trial']['note']); ?></p></div></section>

  <section class="service-offering-section service-offering-section--business-system"><div class="section-inner service-offering__layout"><div class="service-offering__summary"><p class="section-label"><?php echo e($service_item['label']); ?></p><h2>会社に合った仕組みへ、<br>本開発する。</h2><p class="service-offering__lead"><?php echo e($service_item['body']); ?></p></div><div><p class="section-label">Development Examples</p><ul class="service-offering__examples"><?php foreach ($service_item['examples'] as $example) : ?><li><?php echo e($example); ?></li><?php endforeach; ?></ul></div></div></section>

  <section class="service-team-section">
    <div class="section-inner">
      <div class="section-heading"><p class="section-label"><?php echo e($service['team']['label']); ?></p><h2><?php echo responsive_text($service['team'], 'title'); ?></h2><p><?php echo e($service['team']['lead']); ?></p></div>
      <div class="service-team-diagram" aria-label="業務システム開発を支える役割">
        <div class="service-team-diagram__center"><span><?php echo e($service['team']['center_label']); ?></span><strong><?php echo responsive_text($service['team'], 'center_title'); ?></strong></div>
        <div class="service-team-diagram__roles"><?php foreach ($service['team']['experts'] as $expert) : ?><article class="service-team-card"><p class="section-label"><?php echo e($expert['label']); ?></p><h3><?php echo e($expert['title']); ?></h3><p><?php echo e($expert['body']); ?></p></article><?php endforeach; ?></div>
      </div>
    </div>
  </section>

  <section class="prototype-brand business-system-brand"><div class="section-inner section-inner--narrow"><p class="section-label">Continuous Improvement</p><h2>導入は、改善の始まりです。</h2><p>運用後に見つかった課題や要望も、次の改善へつなげます。納品して終わるのではなく、会社が改善し続けられる仕組みとして育てていきます。</p><p class="prototype-brand__statement">私たちは、システムを開発しているのではありません。会社が改善し続けられる進め方を、一緒につくっています。</p><p class="prototype-brand__tagline">改善を、文化に。</p></div></section>
  <section class="next-cta"><div class="section-inner section-inner--narrow"><p class="section-label">Contact</p><h2>完成形が決まる前から、<br>ご相談いただけます。</h2><p>現在の仕事の流れや困っていることを伺い、何を改善するべきか一緒に整理します。</p><a class="button button--primary" href="contact.php?type=<?php echo urlencode('業務システム開発'); ?>#contact-form">業務システム開発について相談する</a></div></section>
</main>

<?php include __DIR__ . '/include/footer.php'; ?>
