<?php
$site = require __DIR__ . '/data/site.php';
$navigation = require __DIR__ . '/data/navigation.php';
$works = require __DIR__ . '/data/works.php';
require __DIR__ . '/include/functions.php';

$works = array_values(array_filter($works, function ($work) {
  return ($work['status'] ?? 'published') === 'published';
}));

usort($works, function ($a, $b) {
  return strcmp($b['published_at'] ?? '', $a['published_at'] ?? '');
});

function work_card_excerpt(array $work, string $key): string
{
  $excerpt = trim((string) ($work[$key . '_excerpt'] ?? ''));
  if ($excerpt !== '') {
    return rtrim($excerpt, " \t\n\r\0\x0B…") . '…';
  }

  $body = trim((string) ($work[$key] ?? ''));
  $paragraphs = preg_split('/\R{2,}/u', $body) ?: [];
  $excerpt = trim((string) ($paragraphs[0] ?? $body));
  $excerpt = preg_replace('/\s+/u', ' ', $excerpt) ?? $excerpt;

  if (function_exists('mb_strlen') && mb_strlen($excerpt) > 95) {
    return mb_substr($excerpt, 0, 95) . '…';
  }

  return rtrim($excerpt, " \t\n\r\0\x0B…") . '…';
}

$current_page = 'works';
$page_title = '実績';
$page_description = 'ホームページは会社が改善を続けるための土台です。制作内容だけでなく、なぜ変えたのか、どう良くなったのかを実績として残します。';

include __DIR__ . '/include/head.php';
include __DIR__ . '/include/header.php';
?>

<main>
  <section class="page-hero hero-scene hero-scene--works">
    <div class="section-inner section-inner--narrow">
      <p class="section-label">05 / Works</p>
      <h1>実績を、改善の記録として残す。</h1>
      <p class="section-lead">ホームページは、公開して終わりの制作物ではありません。採用、営業、信頼づくり、社内更新のしやすさなど、会社が改善を続けるための土台になります。</p>
      <p class="section-lead">そのため実績では、なぜ変えたのか、どう良くなったのか。制作内容だけでなく「課題」と「改善」をセットで残します。</p>
    </div>
  </section>

  <section class="works-list-section">
    <div class="section-inner">
      <div class="section-heading">
        <p class="section-label">Website Works</p>
        <h2>サイト制作実績。</h2>
        <p>まずはホームページ制作の実績から掲載していきます。</p>
      </div>

      <div class="work-list">
        <?php if (empty($works)) : ?>
          <p class="log-empty">公開中の実績はまだありません。</p>
        <?php endif; ?>

        <?php foreach ($works as $work) : ?>
          <article class="work-card">
            <?php if (($work['screenshots']['desktop'] ?? '') !== '' || ($work['screenshots']['mobile'] ?? '') !== '') : ?>
              <figure class="work-card__visual">
                <span class="work-device-mock work-device-mock--card">
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
            <?php endif; ?>
            <div class="improvement-card__meta">
              <time datetime="<?php echo e($work['published_at']); ?>"><?php echo e(str_replace('-', '.', $work['published_at'])); ?></time>
              <span><?php echo e($work['type_label'] ?? 'サイト制作'); ?></span>
              <?php if (($work['client_name'] ?? '') !== '') : ?>
                <span><?php echo e($work['client_name']); ?></span>
              <?php endif; ?>
            </div>
            <h3><?php echo e($work['title']); ?></h3>
            <p><?php echo e($work['summary']); ?></p>
            <dl class="work-card__change">
              <div>
                <dt>課題</dt>
                <dd><?php echo e(work_card_excerpt($work, 'challenge')); ?></dd>
              </div>
              <div>
                <dt>改善</dt>
                <dd><?php echo e(work_card_excerpt($work, 'improvement')); ?></dd>
              </div>
            </dl>
            <a class="text-link" href="work-detail.php?slug=<?php echo e($work['slug']); ?>">詳しく読む</a>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="next-cta">
    <div class="section-inner section-inner--narrow">
      <p class="section-label">Contact</p>
      <h2>あなたの会社では、何を変えたいですか。</h2>
      <p>サイトを作る前に、まずは今止まっていること、変えたいことを一緒に整理します。</p>
      <a class="button button--primary" href="contact.php">改善について相談する</a>
    </div>
  </section>
</main>

<?php include __DIR__ . '/include/footer.php'; ?>
