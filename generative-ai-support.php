<?php
$site = require __DIR__ . '/data/site.php';
$navigation = require __DIR__ . '/data/navigation.php';
$ai = require __DIR__ . '/data/generative-ai-support.php';
require __DIR__ . '/include/functions.php';
$current_page = 'ai';
$page_title = $ai['meta']['title'];
$page_description = $ai['meta']['description'];
include __DIR__ . '/include/head.php';
include __DIR__ . '/include/header.php';
?>
<main class="ai-support-page">
  <section class="ai-support-hero"><div class="section-inner ai-support-hero__layout"><div><p class="section-label"><?php echo e($ai['hero']['label']); ?></p><h1><?php echo responsive_text($ai['hero'], 'title'); ?></h1><p class="ai-support-hero__subtitle"><?php echo e($ai['hero']['subtitle']); ?></p><p class="section-lead"><?php echo e($ai['hero']['lead']); ?></p><div class="button-group"><a class="button button--primary" href="#ai-method">活用メソッドを見る</a><a class="button button--secondary" href="contact.php?type=<?php echo urlencode('生成AI活用支援'); ?>#contact-form">相談する</a></div></div><div class="ai-support-hero__visual" aria-label="生成AI活用が社内へ広がるイメージ"><span>業務を知る</span><span>一緒に使う</span><span>仕組みにする</span><span>自走する</span></div></div></section>

  <section class="prototype-concerns"><div class="section-inner"><div class="section-heading"><p class="section-label"><?php echo e($ai['concerns']['label']); ?></p><h2><?php echo e($ai['concerns']['title']); ?></h2></div><ol class="prototype-concern-list"><?php foreach ($ai['concerns']['items'] as $index => $item) : ?><li><span><?php echo str_pad((string)($index + 1), 2, '0', STR_PAD_LEFT); ?></span><?php echo e($item); ?></li><?php endforeach; ?></ol><p class="prototype-concerns__closing"><?php echo e($ai['concerns']['closing']); ?></p></div></section>

  <section class="ai-method" id="ai-method"><div class="section-inner"><div class="section-heading"><p class="section-label"><?php echo e($ai['method']['label']); ?></p><h2><?php echo e($ai['method']['title']); ?></h2><p><?php echo e($ai['method']['lead']); ?></p></div><ol class="ai-method__phases"><?php foreach ($ai['method']['phases'] as $phase) : ?><li><div class="ai-method__head"><span><?php echo e($phase['number']); ?></span><p class="section-label"><?php echo e($phase['label']); ?></p></div><h3><?php echo e($phase['title']); ?></h3><p class="ai-method__catch"><?php echo e($phase['catch']); ?></p><p><?php echo e($phase['body']); ?></p><ul><?php foreach ($phase['outputs'] as $output) : ?><li><?php echo e($output); ?></li><?php endforeach; ?></ul></li><?php endforeach; ?></ol><p class="ai-method__cycle">業務を知る　→　一緒に使う　→　仕組みにする　→　自走する　→　次の課題へ</p></div></section>

  <section class="ai-use-cases"><div class="section-inner"><div class="section-heading"><p class="section-label"><?php echo e($ai['uses']['label']); ?></p><h2><?php echo responsive_text($ai['uses'], 'title'); ?></h2></div><div class="ai-use-cases__grid"><?php foreach ($ai['uses']['items'] as $item) : ?><article><h3><?php echo e($item['title']); ?></h3><p><?php echo e($item['body']); ?></p></article><?php endforeach; ?></div></div></section>

  <section class="ai-safety"><div class="section-inner ai-safety__layout"><div><p class="section-label"><?php echo e($ai['safety']['label']); ?></p><h2><?php echo e($ai['safety']['title']); ?></h2><p><?php echo e($ai['safety']['body']); ?></p></div><ul><?php foreach ($ai['safety']['items'] as $item) : ?><li><?php echo e($item); ?></li><?php endforeach; ?></ul></div></section>

  <section class="ai-independence"><div class="section-inner"><div class="section-heading"><p class="section-label"><?php echo e($ai['independence']['label']); ?></p><h2><?php echo e($ai['independence']['title']); ?></h2><p><?php echo e($ai['independence']['body']); ?></p></div><ol><?php foreach ($ai['independence']['items'] as $index => $item) : ?><li><span><?php echo str_pad((string)($index + 1), 2, '0', STR_PAD_LEFT); ?></span><?php echo e($item); ?></li><?php endforeach; ?></ol></div></section>

  <section class="prototype-brand"><div class="section-inner section-inner--narrow"><p class="section-label">Continuous Improvement</p><h2>AI活用も、改善を続ける。</h2><p>最初から完璧な使い方を決めるのではなく、小さく試し、実際に使い、気づきを反映する。その積み重ねを、会社の力へ変えていきます。</p><p class="prototype-brand__statement">AIを導入するのではなく、AIと一緒に改善できる会社をつくる。</p><p class="prototype-brand__tagline">改善を、文化に。</p></div></section>

  <section class="next-cta"><div class="section-inner section-inner--narrow"><p class="section-label">Contact</p><h2>AIを活用したい業務について、<br>お聞かせください。</h2><p>どのツールを使うか決まっていなくても構いません。現在の業務から、活用する意味がある仕事を一緒に整理します。</p><a class="button button--primary" href="contact.php?type=<?php echo urlencode('生成AI活用支援'); ?>#contact-form">生成AI活用支援について相談する</a></div></section>
</main>
<?php include __DIR__ . '/include/footer.php'; ?>
