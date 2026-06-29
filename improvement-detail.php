<?php
$site = require __DIR__ . '/data/site.php';
$navigation = require __DIR__ . '/data/navigation.php';
$improvements = require __DIR__ . '/data/improvements.php';
require __DIR__ . '/include/functions.php';

$slug = $_GET['slug'] ?? '';
$log = null;
foreach ($improvements as $item) {
    if ($item['slug'] === $slug) {
        $log = $item;
        break;
    }
}

if ($log === null) {
    http_response_code(404);
    $log = [
        'title' => '改善ログが見つかりません',
        'summary' => '指定された改善ログは見つかりませんでした。',
        'category' => 'Not Found',
        'published_at' => date('Y-m-d'),
        'updated_at' => date('Y-m-d'),
        'problem' => 'URLが変更されたか、改善ログがまだ公開されていない可能性があります。',
        'changed' => '改善ログ一覧から、公開済みの記事をご確認ください。',
        'reason' => '改善ログは公開状態に合わせて育てていきます。',
        'learned' => '見つからないページも、導線を見直すきっかけになります。',
        'next' => '改善ログ一覧へ戻ってください。',
        'history' => [],
    ];
}

$current_page = 'improvement-log';
$page_title = $log['title'];
$page_description = $log['summary'];

include __DIR__ . '/include/head.php';
include __DIR__ . '/include/header.php';
?>

<main>
  <article class="improvement-detail">
    <section class="page-hero hero-scene hero-scene--log">
      <div class="section-inner section-inner--narrow">
        <p class="section-label"><?php echo e($log['category']); ?></p>
        <h1><?php echo e($log['title']); ?></h1>
        <p class="section-lead"><?php echo e($log['summary']); ?></p>
        <p class="detail-date">
          <?php echo e($log['number'] ?? 'LOG'); ?>
          /
          公開日 <?php echo e(str_replace('-', '.', $log['published_at'])); ?>
          / 更新日 <?php echo e(str_replace('-', '.', $log['updated_at'])); ?>
        </p>
      </div>
    </section>

    <section class="detail-body">
      <div class="section-inner section-inner--narrow">
        <div class="before-after-detail">
          <section>
            <p class="section-label">Before</p>
            <h2>改善前の課題</h2>
            <p><?php echo e($log['problem']); ?></p>
          </section>

          <section>
            <p class="section-label">After</p>
            <h2>何を変えたか</h2>
            <p><?php echo e($log['changed']); ?></p>
          </section>
        </div>

        <h2>なぜ変えたか</h2>
        <p><?php echo e($log['reason']); ?></p>

        <h2>気づいたこと</h2>
        <p><?php echo e($log['learned']); ?></p>

        <h2>次に見直したいこと</h2>
        <p><?php echo e($log['next']); ?></p>

        <?php if (!empty($log['history'])) : ?>
          <h2>更新履歴</h2>
          <ul class="history-list">
            <?php foreach ($log['history'] as $history) : ?>
              <li>
                <time datetime="<?php echo e($history['date']); ?>"><?php echo e(str_replace('-', '.', $history['date'])); ?></time>
                <span><?php echo e($history['note']); ?></span>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>

        <a class="text-link" href="improvement-log.php">改善ログ一覧へ戻る</a>
      </div>
    </section>
  </article>
</main>

<?php include __DIR__ . '/include/footer.php'; ?>
