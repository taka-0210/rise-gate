<?php
$site = require __DIR__ . '/data/site.php';
$navigation = require __DIR__ . '/data/navigation.php';
$method = require __DIR__ . '/data/method.php';
require __DIR__ . '/include/functions.php';

$current_page = 'method';
$page_title = $method['meta']['title'];
$page_description = $method['meta']['description'];

$method['steps'] = [
  [
    'label' => 'Step 01',
    'title' => '現場を見る',
    'body' => '答えは、実際の仕事の中にあります。働く人の声、日々の流れ、止まっている情報や業務を見ながら、改善の入口を探します。',
  ],
  [
    'label' => 'Step 02',
    'title' => '目的を整理する',
    'body' => '何を伝えたいのか、何を改善したいのか。採用、実績、商品、サービス、日々の業務など、これから育てたい目的を言葉にします。',
  ],
  [
    'label' => 'Step 03',
    'title' => '最適な進め方を決める',
    'body' => 'ホームページ、管理画面、業務システム、既存ツールの活用など、会社の状況に合わせて無理なく始められる進め方を決めます。',
  ],
  [
    'label' => 'Step 04',
    'title' => '形にする',
    'body' => '最初から大きく作り込みすぎず、実際に使える範囲から形にします。公開や運用に乗せながら、続けられる土台をつくります。',
  ],
  [
    'label' => 'Step 05',
    'title' => '使いながら育てる',
    'body' => '作って終わりではありません。使って分かったことをもとに、情報、表示方法、入力項目、業務の流れを少しずつ見直していきます。',
  ],
];

include __DIR__ . '/include/head.php';
include __DIR__ . '/include/header.php';
?>

<main>
  <section class="page-hero hero-scene hero-scene--method">
    <div class="section-inner section-inner--narrow">
      <p class="section-label"><?php echo e($method['hero']['label']); ?></p>
      <h1><?php echo e($method['hero']['title']); ?></h1>
      <p class="section-lead"><?php echo e($method['hero']['lead']); ?></p>
    </div>
  </section>

  <section class="method-intro">
    <div class="section-inner section-inner--narrow">
      <p class="section-label"><?php echo e($method['intro']['label']); ?></p>
      <h2><?php echo e($method['intro']['title']); ?></h2>
      <?php foreach ($method['intro']['body'] as $paragraph) : ?>
        <p><?php echo e($paragraph); ?></p>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="method-steps">
    <div class="section-inner">
      <div class="section-heading">
        <p class="section-label">Process</p>
        <h2>改善が続く形へ、
段階を分けて整えます。</h2>
      </div>
      <div class="method-step-list">
        <?php foreach ($method['steps'] as $step) : ?>
          <article class="method-step">
            <p class="section-label"><?php echo e($step['label']); ?></p>
            <h3><?php echo e($step['title']); ?></h3>
            <p><?php echo e($step['body']); ?></p>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="method-stance">
    <div class="section-inner section-inner--narrow">
      <p class="section-label"><?php echo e($method['stance']['label']); ?></p>
      <h2><?php echo e($method['stance']['title']); ?></h2>
      <?php foreach ($method['stance']['body'] as $paragraph) : ?>
        <p><?php echo e($paragraph); ?></p>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="next-cta">
    <div class="section-inner section-inner--narrow">
      <p class="section-label"><?php echo e($method['cta']['label']); ?></p>
      <h2><?php echo e($method['cta']['title']); ?></h2>
      <p><?php echo e($method['cta']['body']); ?></p>
      <div class="button-group">
        <a class="button button--primary" href="<?php echo e($method['cta']['link']['url']); ?>">
          <?php echo e($method['cta']['link']['label']); ?>
        </a>
        <a class="button button--secondary" href="<?php echo e($method['cta']['sub_link']['url']); ?>">
          <?php echo e($method['cta']['sub_link']['label']); ?>
        </a>
      </div>
    </div>
  </section>
</main>

<?php include __DIR__ . '/include/footer.php'; ?>
