<?php
$site = require __DIR__ . '/data/site.php';
$navigation = require __DIR__ . '/data/navigation.php';
$rise_os_page = require __DIR__ . '/data/rise-gate-os.php';
require __DIR__ . '/include/functions.php';

$current_page = 'rise-gate-os';
$page_title = $rise_os_page['meta']['title'];
$page_description = $rise_os_page['meta']['description'];
$platform_image_path = __DIR__ . '/image/scene/rise-gate-os-platform.png';
$platform_image_version = file_exists($platform_image_path) ? (string) filemtime($platform_image_path) : '1';

$platform_points = [
  [
    'label' => 'Progress',
    'title' => '「今どうなってる？」をなくす。',
    'body' => [
      '制作会社へ依頼したあと、今どこまで進んでいるんだろう。そんな不安を感じたことはありませんか。',
      'RISE GATE OSでは、現在の進捗状況や担当者、次の予定を同じ画面で共有します。',
      '制作の見える化によって、安心してプロジェクトを進められます。',
    ],
  ],
  [
    'label' => 'Idea',
    'title' => '改善提案を、いつでも。',
    'body' => [
      '思いついた改善アイデアは、その場で登録できます。',
      '社員紹介を追加したい。写真を変更したい。この作業をもっと効率化したい。',
      '日々の気付きは蓄積され、優先順位を付けながら計画的に実装していきます。',
    ],
  ],
  [
    'label' => 'History',
    'title' => '改善の履歴が、会社の資産になる。',
    'body' => [
      'なぜ、この機能を追加したのか。いつ改善したのか。どんな課題を解決したのか。',
      '改善の履歴が残ることで、担当者が変わっても、会社の知識は失われません。',
      '積み重ねた改善は、未来の判断材料になります。',
    ],
  ],
];

$members = ['デザイナー', 'エンジニア', 'ディレクター', 'クライアント'];

include __DIR__ . '/include/head.php';
include __DIR__ . '/include/header.php';
?>

<main>
  <section class="page-hero hero-scene hero-scene--rise-os">
    <div class="section-inner section-inner--narrow">
      <p class="section-label"><?php echo e($rise_os_page['hero']['label']); ?></p>
      <h1><?php echo responsive_text($rise_os_page['hero'], 'title'); ?></h1>
      <p class="section-lead"><?php echo responsive_text($rise_os_page['hero'], 'lead'); ?></p>
    </div>
  </section>

  <section class="os-release-section">
    <div class="section-inner section-inner--narrow">
      <p class="section-label">Release</p>
      <h2><?php echo responsive_lines([
        'desktop' => ['RISE GATE OSを、', '開発しました。'],
        'tablet' => ['RISE GATE OSを、', '開発しました。'],
        'mobile' => ['RISE GATE OSを、', '開発しました。'],
      ]); ?></h2>
      <p>ホームページや業務システムを作ったあとも、改善は続いていきます。</p>
      <p>その改善を、感覚や口頭のやり取りだけに頼らず、見える形で積み重ねていくために、ライズゲートはRISE GATE OSを開発しました。</p>
      <p class="service-declaration__strong">制作後の進捗も。思いついた改善提案も。これまでの改善履歴も。クライアントとライズゲートが同じ場所で共有し、会社の変化を一緒に育てていきます。</p>
    </div>
  </section>

  <section class="os-visual-section">
    <div class="section-inner">
      <figure class="os-platform-visual">
        <img src="image/scene/rise-gate-os-platform.png?v=<?php echo e($platform_image_version); ?>" alt="RISE GATE OSで進捗、改善提案、改善履歴を共有しながら、クライアントとライズゲートが同じ画面で改善を進めるイメージ">
      </figure>
    </div>
  </section>

  <section class="os-intro-section">
    <div class="section-inner os-intro-layout">
      <div class="os-intro-heading">
        <p class="section-label">Concept</p>
        <h2><?php echo responsive_lines([
          'desktop' => ['公開したら', '終わり。', '導入したら', '終わり。', 'ではありません。'],
          'tablet' => ['公開したら', '終わり。', '導入したら', '終わり。', 'ではありません。'],
          'mobile' => ['公開したら', '終わり。', '導入したら', '終わり。', 'ではありません。'],
        ]); ?></h2>
      </div>
      <div class="os-intro-body">
        <p>ホームページは、公開したら終わりではありません。</p>
        <p>業務システムも、導入したら終わりではありません。</p>
        <p class="service-declaration__strong">本当に大切なのは、改善を続けられる仕組みです。</p>
        <p>RISE GATE OSは、ライズゲートがお客様と一緒に改善を回す実践の場です。進捗、提案、履歴をひとつにつなぎ、会社の変化を見える形で積み重ねていきます。</p>
      </div>
    </div>
  </section>

  <section class="os-points-section">
    <div class="section-inner">
      <div class="section-heading">
        <p class="section-label">Platform</p>
        <h2><?php echo responsive_lines([
          'desktop' => ['機能を並べるためではなく、', '改善が文化になるために。'],
          'tablet' => ['機能を並べるためではなく、', '改善が文化になるために。'],
          'mobile' => ['機能を並べる', 'ためではなく、', '改善が文化になるために。'],
        ]); ?></h2>
      </div>
      <div class="os-point-list">
        <?php foreach ($platform_points as $index => $point) : ?>
          <article class="os-point">
            <span class="os-point__number"><?php echo e(str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)); ?></span>
            <div>
              <p class="section-label"><?php echo e($point['label']); ?></p>
              <h3><?php echo e($point['title']); ?></h3>
              <?php foreach ($point['body'] as $paragraph) : ?>
                <p><?php echo e($paragraph); ?></p>
              <?php endforeach; ?>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="os-together-section">
    <div class="section-inner os-together-layout">
      <div>
        <p class="section-label">Together</p>
        <h2><?php echo responsive_lines([
          'desktop' => ['ライズゲートも、', '同じ画面で伴走します。'],
          'tablet' => ['ライズゲートも、', '同じ画面で伴走します。'],
          'mobile' => ['ライズゲートも、', '同じ画面で', '伴走します。'],
        ]); ?></h2>
        <p>担当者だけが管理するのではありません。全員が同じ情報を見ながら、同じゴールに向かって改善を進めます。</p>
      </div>
      <div class="os-member-list" aria-label="RISE GATE OSで同じ情報を見るメンバー">
        <?php foreach ($members as $member) : ?>
          <span><?php echo e($member); ?></span>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="os-nurture-section">
    <div class="section-inner section-inner--narrow">
      <p class="section-label">Purpose</p>
      <h2><?php echo responsive_lines([
        'desktop' => ['作ることより、育てること。'],
        'tablet' => ['作ることより、育てること。'],
        'mobile' => ['作ることより、', '育てること。'],
      ]); ?></h2>
      <p>私たちが目指しているのは、ホームページ制作会社ではありません。システム開発会社でもありません。</p>
      <p>改善が続く会社を増やすことです。</p>
      <p>ホームページも。業務システムも。改善提案も。すべてを一つにつなぎ、会社が進化し続ける環境を支えます。</p>
    </div>
  </section>

  <section class="os-message-section">
    <div class="section-inner section-inner--narrow">
      <p class="section-label">Message</p>
      <p class="os-message-line">改善が見える。</p>
      <p class="os-message-line">進捗が見える。</p>
      <p class="os-message-line">会社の進化が見える。</p>
      <h2>RISE GATE OS</h2>
      <p>クライアントの改善文化を支えるプラットフォーム。</p>
      <a class="button button--primary" href="contact.php">改善の仕組みについて相談する</a>
    </div>
  </section>
</main>

<?php include __DIR__ . '/include/footer.php'; ?>
