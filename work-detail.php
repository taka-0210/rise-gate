<?php
$site = require __DIR__ . '/data/site.php';
$navigation = require __DIR__ . '/data/navigation.php';
$works = require __DIR__ . '/data/works.php';
require __DIR__ . '/include/functions.php';

$slug = $_GET['slug'] ?? '';
$work = null;
foreach ($works as $item) {
    if (($item['slug'] ?? '') === $slug && ($item['status'] ?? 'published') === 'published') {
        $work = $item;
        break;
    }
}

if ($work === null) {
    http_response_code(404);
    $work = [
        'title' => '実績が見つかりません',
        'summary' => '指定された実績は見つかりませんでした。',
        'type_label' => 'Not Found',
        'published_at' => date('Y-m-d'),
        'client_name' => '',
        'challenge' => 'URLが変更されたか、実績がまだ公開されていない可能性があります。',
        'improvement' => '実績一覧から、公開済みの実績をご確認ください。',
        'result' => '',
        'role' => '',
        'site_url' => '',
        'screenshots' => [
            'desktop' => '',
            'mobile' => '',
        ],
        'gallery' => [],
        'tags' => [],
    ];
}

$work_gallery = array_values(array_filter($work['gallery'] ?? [], function ($item) {
    return (($item['image'] ?? '') !== '') || (($item['title'] ?? '') !== '') || (($item['description'] ?? '') !== '');
}));

$work_types = [
    'website' => 'Webサイト制作',
    'system' => 'システム導入',
];
$work_type = (string) ($work['type'] ?? 'website');
$work_type_label = (string) ($work['type_label'] ?? $work_types[$work_type] ?? '改善実績');
$external_link_label = $work_type === 'website' ? 'サイトを見る' : '取り組みを見る';

$current_page = 'works';
$page_title = $work['title'];
$page_description = $work['summary'];

include __DIR__ . '/include/head.php';
include __DIR__ . '/include/header.php';
?>

<main>
  <article class="work-detail">
    <section class="page-hero hero-scene hero-scene--works">
      <div class="section-inner section-inner--narrow">
        <p class="section-label"><?php echo e($work_type_label); ?></p>
        <h1><?php echo e($work['title']); ?></h1>
        <p class="section-lead"><?php echo e($work['summary']); ?></p>
        <p class="detail-date">
          <?php if (($work['client_name'] ?? '') !== '') : ?>
            <?php echo e($work['client_name']); ?> /
          <?php endif; ?>
          公開日 <?php echo e(str_replace('-', '.', $work['published_at'])); ?>
        </p>
      </div>
    </section>

    <?php if (($work['screenshots']['desktop'] ?? '') !== '' || ($work['screenshots']['mobile'] ?? '') !== '') : ?>
      <section class="work-screenshots-section">
        <div class="section-inner">
          <div class="section-heading">
            <p class="section-label">Screenshots</p>
            <h2>画面で見る。</h2>
            <p>どこを変え、どう整えたのか。課題と改善の流れを、実際の画面や導入後の状態とあわせて紹介します。</p>
          </div>

          <figure class="work-device-showcase">
            <span class="work-device-mock">
              <span class="work-device-mock__frame work-device-mock__frame--back" aria-hidden="true"></span>
              <?php if (($work['screenshots']['desktop'] ?? '') !== '') : ?>
                <span class="work-device-mock__shot work-device-mock__shot--desktop">
                  <img src="<?php echo e($work['screenshots']['desktop']); ?>" alt="<?php echo e($work['title']); ?>のパソコン画面" loading="lazy">
                </span>
              <?php endif; ?>
              <?php if (($work['screenshots']['mobile'] ?? '') !== '') : ?>
                <span class="work-device-mock__shot work-device-mock__shot--mobile">
                  <img src="<?php echo e($work['screenshots']['mobile']); ?>" alt="<?php echo e($work['title']); ?>のスマホ画面" loading="lazy">
                </span>
              <?php endif; ?>
              <span class="work-device-mock__frame work-device-mock__frame--front" aria-hidden="true"></span>
            </span>
          </figure>

        </div>
      </section>
    <?php endif; ?>

    <?php if (!empty($work_gallery)) : ?>
      <section class="work-gallery-section work-gallery-section--hidden">
        <div class="section-inner">
          <div class="section-heading">
            <p class="section-label">Screens</p>
            <h2>整えた画面</h2>
            <p>公開サイト、管理画面、会員専用画面、業務画面など、整えた画面ごとの役割を紹介します。</p>
          </div>

          <div class="work-gallery-grid">
            <?php foreach ($work_gallery as $gallery_item) : ?>
              <article class="work-gallery-card">
                <?php if (($gallery_item['image'] ?? '') !== '') : ?>
                  <figure class="work-gallery-card__image">
                    <img src="<?php echo e($gallery_item['image']); ?>" alt="<?php echo e(($gallery_item['title'] ?? '') !== '' ? $gallery_item['title'] : $work['title']); ?>" loading="lazy">
                  </figure>
                <?php endif; ?>
                <div class="work-gallery-card__body">
                  <?php if (($gallery_item['title'] ?? '') !== '') : ?>
                    <h3><?php echo e($gallery_item['title']); ?></h3>
                  <?php endif; ?>
                  <?php if (($gallery_item['description'] ?? '') !== '') : ?>
                    <p><?php echo e($gallery_item['description']); ?></p>
                  <?php endif; ?>
                </div>
              </article>
            <?php endforeach; ?>
          </div>
        </div>
      </section>
    <?php endif; ?>

    <section class="detail-body">
      <div class="section-inner section-inner--narrow">
        <div class="before-after-detail before-after-detail--single before-after-detail--challenge">
          <section>
            <p class="section-label">Challenge</p>
            <h2>何を変えたかったか</h2>
            <p><?php echo e($work['challenge']); ?></p>
          </section>
        </div>

        <?php if (!empty($work_gallery)) : ?>
          <section class="work-gallery-inline">
            <div class="section-heading">
              <p class="section-label">Screens</p>
              <h2>整えた画面</h2>
              <p>公開サイト、管理画面、会員専用画面、業務画面など、整えた画面ごとの役割を紹介します。</p>
            </div>

            <div class="work-gallery-compact">
              <?php foreach ($work_gallery as $gallery_item) : ?>
                <article class="work-gallery-card work-gallery-card--compact">
                  <?php if (($gallery_item['image'] ?? '') !== '') : ?>
                    <figure class="work-gallery-card__image">
                      <img src="<?php echo e($gallery_item['image']); ?>" alt="<?php echo e(($gallery_item['title'] ?? '') !== '' ? $gallery_item['title'] : $work['title']); ?>" loading="lazy">
                    </figure>
                  <?php endif; ?>
                  <div class="work-gallery-card__body">
                    <?php if (($gallery_item['title'] ?? '') !== '') : ?>
                      <h3><?php echo e($gallery_item['title']); ?></h3>
                    <?php endif; ?>
                    <?php if (($gallery_item['description'] ?? '') !== '') : ?>
                      <p><?php echo e($gallery_item['description']); ?></p>
                    <?php endif; ?>
                  </div>
                </article>
              <?php endforeach; ?>
            </div>
          </section>
        <?php endif; ?>

        <div class="before-after-detail before-after-detail--single before-after-detail--improvement">
          <section>
            <p class="section-label">Improvement</p>
            <h2>どのような視点で整えたか</h2>
            <p><?php echo e($work['improvement']); ?></p>
          </section>
        </div>

        <?php if (($work['result'] ?? '') !== '') : ?>
          <h2>改善の結果</h2>
          <p><?php echo e($work['result']); ?></p>
        <?php endif; ?>

        <?php if (($work['role'] ?? '') !== '') : ?>
          <h2>担当したこと</h2>
          <p><?php echo e($work['role']); ?></p>
        <?php endif; ?>

        <?php if (!empty($work['tags'])) : ?>
          <ul class="tag-list work-tags">
            <?php foreach ($work['tags'] as $tag) : ?>
              <li><?php echo e($tag); ?></li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>

        <div class="work-detail__links">
          <?php if (($work['site_url'] ?? '') !== '') : ?>
            <a class="button button--secondary" href="<?php echo e($work['site_url']); ?>" target="_blank" rel="noopener"><?php echo e($external_link_label); ?></a>
          <?php endif; ?>
          <a class="text-link" href="works.php">実績一覧へ戻る</a>
        </div>
      </div>
    </section>
  </article>
</main>

<?php include __DIR__ . '/include/footer.php'; ?>
