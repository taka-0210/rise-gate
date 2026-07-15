<?php
$site = require __DIR__ . '/data/site.php';
$navigation = require __DIR__ . '/data/navigation.php';
$logPage = require __DIR__ . '/data/improvement-log.php';
$improvements = require __DIR__ . '/data/improvements.php';
require __DIR__ . '/include/functions.php';

$selected_year = preg_match('/^\d{4}$/', $_GET['year'] ?? '') ? $_GET['year'] : '';
$selected_month = preg_match('/^\d{2}$/', $_GET['month'] ?? '') ? $_GET['month'] : '';
$current_log_page = max(1, (int) ($_GET['page'] ?? 1));
$logs_per_page = 10;

usort($improvements, function ($a, $b) {
    $date_compare = strcmp($b['published_at'], $a['published_at']);
    if ($date_compare !== 0) {
        return $date_compare;
    }

    return strcmp($b['number'], $a['number']);
});

$archives = [];
foreach ($improvements as $log) {
    $year = substr($log['published_at'], 0, 4);
    $month = substr($log['published_at'], 5, 2);

    if (!isset($archives[$year])) {
        $archives[$year] = [
            'count' => 0,
            'months' => [],
        ];
    }

    $archives[$year]['count']++;
    $archives[$year]['months'][$month] = ($archives[$year]['months'][$month] ?? 0) + 1;
}

krsort($archives);
foreach ($archives as &$archive) {
    krsort($archive['months']);
}
unset($archive);

$filtered_improvements = array_values(array_filter($improvements, function ($log) use ($selected_year, $selected_month) {
    if ($selected_year !== '' && substr($log['published_at'], 0, 4) !== $selected_year) {
        return false;
    }

    if ($selected_month !== '' && substr($log['published_at'], 5, 2) !== $selected_month) {
        return false;
    }

    return true;
}));

$total_logs = count($filtered_improvements);
$total_pages = max(1, (int) ceil($total_logs / $logs_per_page));
$current_log_page = min($current_log_page, $total_pages);
$paged_improvements = array_slice($filtered_improvements, ($current_log_page - 1) * $logs_per_page, $logs_per_page);

function log_archive_url(array $params = []): string
{
    $query = array_filter(array_merge($_GET, $params), function ($value) {
        return $value !== '' && $value !== null;
    });

    return 'improvement-log.php' . (empty($query) ? '' : '?' . http_build_query($query));
}

function render_log_pagination(int $current_log_page, int $total_pages): void
{
    if ($total_pages <= 1) {
        return;
    }
    ?>
    <nav class="pagination" aria-label="改善ログのページ送り">
      <?php for ($page = 1; $page <= $total_pages; $page++) : ?>
        <a href="<?php echo e(log_archive_url(['page' => $page])); ?>"<?php echo $page === $current_log_page ? ' aria-current="page"' : ''; ?>>
          <?php echo e((string) $page); ?>
        </a>
      <?php endfor; ?>
    </nav>
    <?php
}

$current_page = 'improvement-log';
$page_title = $logPage['meta']['title'];
$page_description = $logPage['meta']['description'];

include __DIR__ . '/include/head.php';
include __DIR__ . '/include/header.php';
?>

<main>
  <section class="page-hero hero-scene hero-scene--log">
    <div class="section-inner section-inner--narrow">
      <p class="section-label"><?php echo e($logPage['hero']['label']); ?></p>
      <h1><?php echo responsive_text($logPage['hero'], 'title'); ?></h1>
      <p class="section-lead"><?php echo e($logPage['hero']['lead']); ?></p>
    </div>
  </section>

  <section class="log-purpose">
    <div class="section-inner section-inner--narrow">
      <p class="section-label"><?php echo e($logPage['intro']['label']); ?></p>
      <h2><?php echo responsive_text($logPage['intro'], 'title'); ?></h2>
      <?php foreach ($logPage['intro']['body'] as $paragraph) : ?>
        <p><?php echo e($paragraph); ?></p>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="log-categories">
    <div class="section-inner">
      <div class="section-heading">
        <p class="section-label">Categories</p>
        <h2><?php echo responsive_lines([
          'desktop' => ['改善の種類。'],
          'tablet' => ['改善の種類。'],
          'mobile' => ['改善の種類。'],
        ]); ?></h2>
      </div>
      <ul class="tag-list">
        <?php foreach ($logPage['categories'] as $category) : ?>
          <li><?php echo e($category); ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  </section>

  <section class="log-list-section">
    <div class="section-inner">
      <div class="section-heading">
        <p class="section-label">Logs</p>
        <h2><?php echo responsive_lines([
          'desktop' => ['何を変え、', 'なぜ変えたのか。'],
          'tablet' => ['何を変え、', 'なぜ変えたのか。'],
          'mobile' => ['何を変え、', 'なぜ変えたのか。'],
        ]); ?></h2>
      </div>

      <div class="log-layout">
        <div>
          <p class="log-count">
            <?php echo e((string) $total_logs); ?>件の改善ログ
            <?php if ($selected_year !== '') : ?>
              / <?php echo e($selected_year); ?>年<?php echo $selected_month !== '' ? e((string) ((int) $selected_month)) . '月' : ''; ?>
            <?php endif; ?>
          </p>

          <?php render_log_pagination($current_log_page, $total_pages); ?>

          <div class="improvement-list">
            <?php if (empty($paged_improvements)) : ?>
              <p class="log-empty">該当する改善ログはまだありません。</p>
            <?php endif; ?>
            <?php foreach ($paged_improvements as $log) : ?>
              <article class="improvement-card">
                <div class="improvement-card__meta">
                  <span><?php echo e($log['number']); ?></span>
                  <time datetime="<?php echo e($log['published_at']); ?>"><?php echo e(str_replace('-', '.', $log['published_at'])); ?></time>
                  <span><?php echo e($log['category']); ?></span>
                </div>
                <h3><?php echo e($log['title']); ?></h3>
                <p><?php echo e($log['summary']); ?></p>
                <a class="text-link" href="improvement-detail.php?slug=<?php echo e($log['slug']); ?>">詳しく読む</a>
              </article>
            <?php endforeach; ?>
          </div>

          <?php render_log_pagination($current_log_page, $total_pages); ?>
        </div>

        <aside class="log-archive" aria-label="改善ログアーカイブ">
          <p class="section-label">Archive</p>
          <a class="log-archive__all" href="improvement-log.php">すべてのログ</a>
          <ul class="archive-list">
            <?php foreach ($archives as $year => $archive) : ?>
              <li>
                <a class="archive-list__year" href="<?php echo e(log_archive_url(['year' => $year, 'month' => '', 'page' => 1])); ?>"<?php echo $selected_year === $year && $selected_month === '' ? ' aria-current="page"' : ''; ?>>
                  <span><?php echo e($year); ?>年</span>
                  <span><?php echo e((string) $archive['count']); ?></span>
                </a>
                <ul>
                  <?php foreach ($archive['months'] as $month => $count) : ?>
                    <li>
                      <a href="<?php echo e(log_archive_url(['year' => $year, 'month' => $month, 'page' => 1])); ?>"<?php echo $selected_year === $year && $selected_month === $month ? ' aria-current="page"' : ''; ?>>
                        <span><?php echo e((string) ((int) $month)); ?>月</span>
                        <span><?php echo e((string) $count); ?></span>
                      </a>
                    </li>
                  <?php endforeach; ?>
                </ul>
              </li>
            <?php endforeach; ?>
          </ul>
        </aside>
      </div>
    </div>
  </section>

  <section class="next-cta">
    <div class="section-inner section-inner--narrow">
      <p class="section-label"><?php echo e($logPage['cta']['label']); ?></p>
      <h2><?php echo responsive_text($logPage['cta'], 'title'); ?></h2>
      <p><?php echo e($logPage['cta']['body']); ?></p>
      <a class="button button--primary" href="<?php echo e($logPage['cta']['link']['url']); ?>">
        <?php echo e($logPage['cta']['link']['label']); ?>
      </a>
    </div>
  </section>
</main>

<?php include __DIR__ . '/include/footer.php'; ?>
