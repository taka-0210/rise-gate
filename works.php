<?php
$site = require __DIR__ . '/data/site.php';
$navigation = require __DIR__ . '/data/navigation.php';
$works = require __DIR__ . '/data/works.php';
require __DIR__ . '/include/functions.php';

$works = array_values(array_filter($works, function ($work) {
  return ($work['status'] ?? 'published') === 'published';
}));

$work_types = [
  'website' => 'Webサイト制作',
  'system' => 'システム導入',
];
$selected_work_type = (string) ($_GET['type'] ?? '');
if (!array_key_exists($selected_work_type, $work_types)) {
  $selected_work_type = '';
}

usort($works, function ($a, $b) {
  return strcmp($b['published_at'] ?? '', $a['published_at'] ?? '');
});

$visible_works = array_values(array_filter($works, function ($work) use ($selected_work_type) {
  if ($selected_work_type === '') {
    return true;
  }

  return ($work['type'] ?? 'website') === $selected_work_type;
}));

function work_type_label(array $work, array $work_types): string
{
  $type = (string) ($work['type'] ?? 'website');

  return (string) ($work['type_label'] ?? $work_types[$type] ?? '改善実績');
}

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
$page_description = 'Webサイト制作とシステム導入を、なぜ変えたのか、どう良くなったのかが伝わる改善実績として紹介します。';

include __DIR__ . '/include/head.php';
include __DIR__ . '/include/header.php';
?>

<main>
  <section class="page-hero hero-scene hero-scene--works">
    <div class="section-inner section-inner--narrow">
      <p class="section-label">04 / Works</p>
      <h1>実績を、改善の記録として残す。</h1>
      <p class="section-lead">ホームページも、システムも、会社を良くするための手段です。採用、営業、情報共有、現場の業務など、会社が改善を続けるための土台として整えていきます。</p>
      <p class="section-lead">そのため実績では、何を作ったかだけでなく、なぜ変えたのか、どう良くなったのか。「課題」と「改善」をセットで残します。</p>
    </div>
  </section>

  <section class="works-scope-section">
    <div class="section-inner">
      <div class="works-scope">
        <div>
          <p class="section-label">Improvement Works</p>
          <h2>Webサイト制作から、システム導入まで。</h2>
          <p>ライズゲートの実績は、完成した画面を並べるだけではなく、会社の発信や業務がどう改善されたかを見る場所です。</p>
        </div>
      </div>
    </div>
  </section>

  <section class="works-list-section" id="works-list">
    <div class="section-inner">
      <div class="section-heading">
        <p class="section-label">Works</p>
        <h2>改善実績。</h2>
        <p>Webサイトと業務システムを入口に、会社の改善につながった取り組みを掲載していきます。</p>
      </div>

      <ul class="works-type-list works-type-list--filters">
        <li>
          <a href="works.php#works-list"<?php echo $selected_work_type === '' ? ' aria-current="page"' : ''; ?>>
            全ての実績
          </a>
        </li>
        <?php foreach ($work_types as $type_value => $type_label) : ?>
          <li>
            <a href="works.php?type=<?php echo e($type_value); ?>#works-list"<?php echo $selected_work_type === $type_value ? ' aria-current="page"' : ''; ?>>
              <?php echo e($type_label); ?>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>

      <div class="work-list">
        <?php if (empty($visible_works)) : ?>
          <p class="log-empty">公開中の実績はまだありません。</p>
        <?php endif; ?>

        <?php foreach ($visible_works as $work) : ?>
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
              <span><?php echo e(work_type_label($work, $work_types)); ?></span>
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
      <p>Webサイトか、システムか。決める前に、まずは今止まっていること、変えたいことを一緒に整理します。</p>
      <a class="button button--primary" href="contact.php">改善について相談する</a>
    </div>
  </section>
</main>

<?php include __DIR__ . '/include/footer.php'; ?>
