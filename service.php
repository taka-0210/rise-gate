<?php
$site = require __DIR__ . '/data/site.php';
$navigation = require __DIR__ . '/data/navigation.php';
require __DIR__ . '/include/functions.php';

$current_page = 'service';
$page_title = 'Service';
$page_description = 'ライズゲートは、ホームページ制作会社でもシステム開発会社でもありません。会社の改善プロジェクトを設計し、社員を巻き込みながら改善が続く状態をつくります。';

$experts = [
  [
    'label' => 'Design',
    'title' => 'デザインが得意な人',
    'body' => '伝えたい価値を整理し、見やすく、使いやすく、続けやすい形にします。',
  ],
  [
    'label' => 'Coding',
    'title' => 'コーディングが得意な人',
    'body' => '会社に合わせた画面や仕組みを、扱いやすい形で実装します。',
  ],
  [
    'label' => 'Improvement',
    'title' => '業務改善が得意な人',
    'body' => '現場の流れを見て、どこから改善するべきかを整理します。',
  ],
  [
    'label' => 'Management',
    'title' => '経営を整理できる人',
    'body' => '会社の目的、優先順位、判断基準を言葉にして、進め方を整えます。',
  ],
  [
    'label' => 'AI',
    'title' => 'AI活用が得意な人',
    'body' => 'AIを目的にせず、更新、整理、判断、改善の流れに自然に組み込みます。',
  ],
];

$project_steps = [
  [
    'number' => '01',
    'title' => '現場を知る',
    'body' => '社長だけでなく、実際に使う人、更新する人、困っている人の声を聞きます。',
  ],
  [
    'number' => '02',
    'title' => '目的を整理する',
    'body' => 'ホームページを作るのか、システムを入れるのかではなく、何を改善したいのかを明確にします。',
  ],
  [
    'number' => '03',
    'title' => 'チームで設計する',
    'body' => '必要な専門家が集まり、発信、業務、運用、AI活用まで含めて改善プロジェクトを設計します。',
  ],
  [
    'number' => '04',
    'title' => '会社で育てる',
    'body' => '納品で終わらせず、社員を巻き込みながら、使って見直し続けられる状態をつくります。',
  ],
];

include __DIR__ . '/include/head.php';
include __DIR__ . '/include/header.php';
?>

<main>
  <section class="page-hero hero-scene hero-scene--service">
    <div class="section-inner section-inner--narrow">
      <p class="section-label">03 / Service</p>
      <h1><?php echo responsive_lines([
        'desktop' => ['改善プロジェクトを、会社と設計する。'],
        'tablet' => ['改善プロジェクトを、', '会社と設計する。'],
        'mobile' => ['改善プロジェクトを、', '会社と', '設計する。'],
      ]); ?></h1>
      <p class="section-lead">ライズゲートは、ホームページ制作会社でも、システム開発会社でもありません。会社の改善が続く状態をつくるための、プロジェクト設計チームです。</p>
    </div>
  </section>

  <section class="service-declaration">
    <div class="section-inner service-declaration__layout">
      <div class="service-declaration__copy">
        <p class="section-label">What We Do</p>
        <h2><?php echo responsive_lines([
          'desktop' => ['作るものではなく、', '変わり続ける仕組みを設計する。'],
          'tablet' => ['作るものではなく、', '変わり続ける仕組みを', '設計する。'],
          'mobile' => ['作るものではなく、', '変わり続ける', '仕組みを設計する。'],
        ]); ?></h2>
      </div>
      <div class="service-declaration__body">
        <p>ホームページも、業務システムも、会社を良くするための手段です。</p>
        <p>大切なのは、何を作るかではなく、誰を巻き込み、どこから始め、どう改善を続けられる状態にするか。</p>
        <p class="service-declaration__strong">ライズゲートの仕事は、会社の改善プロジェクトを設計することです。</p>
      </div>
    </div>
  </section>

  <section class="service-team-section">
    <div class="section-inner">
      <div class="section-heading">
        <p class="section-label">Project Team</p>
        <h2><?php echo responsive_lines([
          'desktop' => ['専門家が集まり、', '改善をひとつのプロジェクトにする。'],
          'tablet' => ['専門家が集まり、', '改善をひとつの', 'プロジェクトにする。'],
          'mobile' => ['専門家が集まり、', '改善をひとつの', 'プロジェクトにする。'],
        ]); ?></h2>
        <p>一人の担当者だけで、発信も、業務改善も、AI活用も、経営整理も進めるのは簡単ではありません。ライズゲートは、それぞれの得意分野を持つ人がチームとなって支えます。</p>
      </div>

      <div class="service-team-diagram" aria-label="改善プロジェクトを支える専門家チーム">
        <div class="service-team-diagram__center">
          <span>RISE GATE</span>
          <strong>改善プロジェクト設計</strong>
        </div>
        <div class="service-team-diagram__roles">
          <?php foreach ($experts as $expert) : ?>
            <article class="service-team-card">
              <p class="section-label"><?php echo e($expert['label']); ?></p>
              <h3><?php echo e($expert['title']); ?></h3>
              <p><?php echo e($expert['body']); ?></p>
            </article>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>

  <section class="service-involvement">
    <div class="section-inner service-involvement__layout">
      <div>
        <p class="section-label">Not President Only</p>
        <h2><?php echo responsive_lines([
          'desktop' => ['社長だけのプロジェクトにしない。'],
          'tablet' => ['社長だけのプロジェクトにしない。'],
          'mobile' => ['社長だけの', 'プロジェクトにしない。'],
        ]); ?></h2>
      </div>
      <div class="service-involvement__body">
        <p>ホームページも、システムも、社長だけが考えて、外部に依頼して終わりにしてしまうと、会社の中に改善は残りません。</p>
        <p>使う人、更新する人、判断する人、困っている人。社員を巻き込み、会社全体で改善できる状態をつくること。</p>
        <p class="service-declaration__strong">それが、ライズゲートのサービスです。</p>
      </div>
    </div>
  </section>

  <section class="service-project-section">
    <div class="section-inner">
      <div class="section-heading">
        <p class="section-label">How It Works</p>
        <h2><?php echo responsive_lines([
          'desktop' => ['制作ではなく、', '改善プロジェクトとして進める。'],
          'tablet' => ['制作ではなく、', '改善プロジェクトとして', '進める。'],
          'mobile' => ['制作ではなく、', '改善プロジェクトとして', '進める。'],
        ]); ?></h2>
      </div>
      <div class="service-project-flow">
        <?php foreach ($project_steps as $step) : ?>
          <article class="service-project-step">
            <span><?php echo e($step['number']); ?></span>
            <h3><?php echo e($step['title']); ?></h3>
            <p><?php echo e($step['body']); ?></p>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="service-tools-section">
    <div class="section-inner service-tools__layout">
      <div>
        <p class="section-label">Tools</p>
        <h2><?php echo responsive_lines([
          'desktop' => ['ホームページも、システムも、目的ではなく手段です。'],
          'tablet' => ['ホームページも、システムも、', '目的ではなく手段です。'],
          'mobile' => ['ホームページも、', 'システムも、', '目的ではなく手段です。'],
        ]); ?></h2>
      </div>
      <div class="service-tools__items">
        <article class="service-tool-card">
          <p class="section-label">Website</p>
          <h3>発信を育てるホームページ</h3>
          <p>採用、実績、商品、サービス、改善ログなど、会社の価値を増やし続けられる発信の土台をつくります。</p>
        </article>
        <article class="service-tool-card">
          <p class="section-label">System</p>
          <h3>改善を育てる業務システム</h3>
          <p>現場の課題を見える化し、入力、確認、判断、共有の流れを少しずつ整えながら育てます。</p>
        </article>
        <article class="service-tool-card">
          <p class="section-label">RISE GATE OS</p>
          <h3>改善文化を支えるプラットフォーム</h3>
          <p>進捗、改善提案、改善履歴を同じ場所に集め、ライズゲートとクライアントが一緒に改善を回せる状態をつくります。</p>
          <a class="text-link" href="rise-gate-os.php">RISE GATE OSを見る</a>
        </article>
      </div>
    </div>
  </section>

  <section class="next-cta">
    <div class="section-inner section-inner--narrow">
      <p class="section-label">Contact</p>
      <h2><?php echo responsive_lines([
        'desktop' => ['社長一人ではなく、会社みんなで。'],
        'tablet' => ['社長一人ではなく、', '会社みんなで。'],
        'mobile' => ['社長一人ではなく、', '会社みんなで。'],
      ]); ?></h2>
      <p>ホームページも。システムも。社員も巻き込みながら改善が続く会社へ。</p>
      <div class="button-group">
        <a class="button button--primary" href="contact.php">改善プロジェクトについて相談する</a>
        <a class="button button--secondary" href="method.php">進め方を見る</a>
      </div>
    </div>
  </section>
</main>

<?php include __DIR__ . '/include/footer.php'; ?>
