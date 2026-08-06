<?php
$site = require __DIR__ . '/data/site.php';
$navigation = require __DIR__ . '/data/navigation.php';
$prototype = require __DIR__ . '/data/prototype-development.php';
require __DIR__ . '/include/functions.php';
$current_page = 'service';
$page_title = $prototype['meta']['title'];
$page_description = $prototype['meta']['description'];
include __DIR__ . '/include/head.php';
include __DIR__ . '/include/header.php';
?>
<main class="prototype-page">
  <section class="prototype-hero">
    <div class="section-inner prototype-hero__layout">
      <div><p class="section-label"><?php echo e($prototype['hero']['label']); ?></p><p class="prototype-hero__name"><?php echo e($prototype['hero']['name']); ?> — 業務システム開発の標準メソッド</p><h1><?php echo e($prototype['hero']['title']); ?></h1><p class="prototype-hero__subtitle"><?php echo e($prototype['hero']['subtitle']); ?></p><p class="section-lead"><?php echo e($prototype['hero']['lead']); ?></p><div class="button-group"><a class="button button--primary" href="#process">開発の進め方を見る</a><a class="button button--secondary" href="contact.php?type=<?php echo urlencode('試作品から始める業務システム開発'); ?>#contact-form">相談する</a></div></div>
      <div class="prototype-mock" aria-label="確認と改善を重ねる試作品のイメージ"><div class="prototype-mock__bar"><i></i><i></i><i></i></div><div class="prototype-mock__body"><span></span><span></span><span></span><div></div></div><p>触る　→　気づく　→　改善する</p></div>
    </div>
  </section>

  <section class="prototype-concerns"><div class="section-inner"><div class="section-heading"><p class="section-label"><?php echo e($prototype['concerns']['label']); ?></p><h2><?php echo e($prototype['concerns']['title']); ?></h2><p><?php echo e($prototype['concerns']['lead']); ?></p></div><ol class="prototype-concern-list"><?php foreach ($prototype['concerns']['items'] as $index => $item) : ?><li><span><?php echo str_pad((string)($index + 1), 2, '0', STR_PAD_LEFT); ?></span><?php echo e($item); ?></li><?php endforeach; ?></ol><p class="prototype-concerns__closing"><?php echo e($prototype['concerns']['closing']); ?></p></div></section>

  <section class="prototype-comparison" id="process"><div class="section-inner"><div class="section-heading"><p class="section-label"><?php echo e($prototype['comparison']['label']); ?></p><h2><?php echo e($prototype['comparison']['title']); ?></h2><p class="prototype-comparison__message"><?php echo e($prototype['comparison']['message']); ?></p></div><div class="prototype-comparison__grid"><?php foreach ($prototype['comparison']['methods'] as $method) : ?><article class="prototype-method<?php echo !empty($method['recommended']) ? ' prototype-method--risegate' : ''; ?>"><p class="section-label"><?php echo e($method['label']); ?></p><h3><?php echo e($method['title']); ?></h3><ol><?php foreach ($method['steps'] as $step) : ?><li><?php echo e($step); ?></li><?php endforeach; ?></ol><p><?php echo e($method['note']); ?></p></article><?php endforeach; ?></div></div></section>

  <section class="prototype-purpose"><div class="section-inner section-inner--narrow"><p class="section-label"><?php echo e($prototype['purpose']['label']); ?></p><h2><?php echo e($prototype['purpose']['title']); ?></h2><?php foreach ($prototype['purpose']['body'] as $paragraph) : ?><p><?php echo e($paragraph); ?></p><?php endforeach; ?><p class="prototype-key-message"><?php echo e($prototype['purpose']['message']); ?></p></div></section>

  <section class="prototype-voices"><div class="section-inner"><div class="section-heading"><p class="section-label"><?php echo e($prototype['voices']['label']); ?></p><h2><?php echo e($prototype['voices']['title']); ?></h2></div><div class="prototype-voices__stage"><div class="prototype-voices__screen"><span></span><span></span><div></div></div><ul><?php foreach ($prototype['voices']['items'] as $item) : ?><li><?php echo e($item); ?></li><?php endforeach; ?></ul></div><p class="prototype-voices__body"><?php echo e($prototype['voices']['body']); ?></p></div></section>

  <section class="prototype-confidence"><div class="section-inner"><div class="section-heading"><p class="section-label"><?php echo e($prototype['confidence']['label']); ?></p><h2><?php echo e($prototype['confidence']['title']); ?></h2><p><?php echo e($prototype['confidence']['body']); ?></p></div><div class="prototype-confidence__grid"><?php foreach ($prototype['confidence']['items'] as $item) : ?><article><p class="section-label"><?php echo e($item['label']); ?></p><h3><?php echo e($item['title']); ?></h3></article><?php endforeach; ?></div><p class="prototype-confidence__closing"><?php echo e($prototype['confidence']['closing']); ?></p></div></section>

  <section class="prototype-flow"><div class="section-inner"><div class="section-heading"><p class="section-label"><?php echo e($prototype['flow']['label']); ?></p><h2><?php echo e($prototype['flow']['title']); ?></h2></div><ol class="prototype-flow__list"><?php foreach ($prototype['flow']['steps'] as $step) : ?><li><span><?php echo e($step['number']); ?></span><h3><?php echo e($step['title']); ?></h3><p><?php echo e($step['body']); ?></p><small><?php echo e($step['meta']); ?></small></li><?php endforeach; ?></ol></div></section>

  <section class="prototype-cycle"><div class="section-inner"><div class="prototype-cycle__copy"><p class="section-label"><?php echo e($prototype['cycle']['label']); ?></p><h2><?php echo e($prototype['cycle']['title']); ?></h2><p><?php echo e($prototype['cycle']['body']); ?></p></div><ol><?php foreach ($prototype['cycle']['items'] as $index => $item) : ?><li><span><?php echo str_pad((string)($index + 1), 2, '0', STR_PAD_LEFT); ?></span><?php echo e($item); ?></li><?php endforeach; ?></ol></div></section>

  <section class="prototype-technology"><div class="section-inner section-inner--narrow"><p class="section-label"><?php echo e($prototype['technology']['label']); ?></p><h2><?php echo e($prototype['technology']['title']); ?></h2><p><?php echo e($prototype['technology']['body']); ?></p></div></section>

  <section class="prototype-trial"><div class="section-inner prototype-trial__box"><p class="section-label"><?php echo e($prototype['trial']['label']); ?></p><h2><?php echo e($prototype['trial']['title']); ?></h2><?php foreach ($prototype['trial']['body'] as $paragraph) : ?><p><?php echo e($paragraph); ?></p><?php endforeach; ?><ul><?php foreach ($prototype['trial']['conditions'] as $condition) : ?><li><?php echo e($condition); ?></li><?php endforeach; ?></ul><p class="prototype-trial__note"><?php echo e($prototype['trial']['note']); ?></p></div></section>

  <section class="prototype-brand"><div class="section-inner section-inner--narrow"><p class="section-label"><?php echo e($prototype['brand']['label']); ?></p><h2><?php echo e($prototype['brand']['title']); ?></h2><p><?php echo e($prototype['brand']['body']); ?></p><p class="prototype-brand__statement"><?php echo e($prototype['brand']['statement']); ?></p><p class="prototype-brand__tagline"><?php echo e($prototype['brand']['tagline']); ?></p></div></section>

  <section class="next-cta"><div class="section-inner section-inner--narrow"><p class="section-label">Contact</p><h2>完成形が決まる前から、<br>ご相談いただけます。</h2><p>現在の業務や困っていることを伺い、何を確かめるべきか、一緒に整理します。</p><a class="button button--primary" href="contact.php?type=<?php echo urlencode('試作品から始める業務システム開発'); ?>#contact-form">業務システム開発について相談する</a><p class="prototype-cta__note">初回ヒアリングで、対象業務と試作品の実現性を確認します。</p></div></section>
</main>
<?php include __DIR__ . '/include/footer.php'; ?>
