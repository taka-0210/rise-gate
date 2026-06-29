<?php
$site = require __DIR__ . '/data/site.php';
$navigation = require __DIR__ . '/data/navigation.php';
$contact = require __DIR__ . '/data/contact.php';
require __DIR__ . '/include/functions.php';

$current_page = 'contact';
$page_title = $contact['meta']['title'];
$page_description = $contact['meta']['description'];

$mail_subject = rawurlencode($contact['form']['subject']);
$mail_body = rawurlencode("お名前:\n会社名:\nメールアドレス:\n\n今、困っていること:\n\n改善したいこと:\n\n相談したい背景:\n\nまだ整理できていないこと:\n");
$mail_href = 'mailto:' . $contact['form']['mail_to'] . '?subject=' . $mail_subject . '&body=' . $mail_body;

include __DIR__ . '/include/head.php';
include __DIR__ . '/include/header.php';
?>

<main>
  <section class="page-hero hero-scene hero-scene--contact">
    <div class="section-inner section-inner--narrow">
      <p class="section-label"><?php echo e($contact['hero']['label']); ?></p>
      <h1><?php echo e($contact['hero']['title']); ?></h1>
      <p class="section-lead"><?php echo e($contact['hero']['lead']); ?></p>
    </div>
  </section>

  <section class="contact-examples">
    <div class="section-inner">
      <div class="section-heading">
        <p class="section-label"><?php echo e($contact['examples']['label']); ?></p>
        <h2><?php echo e($contact['examples']['title']); ?></h2>
      </div>
      <div class="content-grid content-grid--three">
        <?php foreach ($contact['examples']['items'] as $item) : ?>
          <article class="content-card">
            <h3><?php echo e($item); ?></h3>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="contact-stance">
    <div class="section-inner section-inner--narrow">
      <p class="section-label"><?php echo e($contact['stance']['label']); ?></p>
      <h2><?php echo e($contact['stance']['title']); ?></h2>
      <?php foreach ($contact['stance']['body'] as $paragraph) : ?>
        <p><?php echo e($paragraph); ?></p>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="contact-pricing">
    <div class="section-inner section-inner--narrow">
      <p class="section-label"><?php echo e($contact['pricing']['label']); ?></p>
      <h2><?php echo e($contact['pricing']['title']); ?></h2>
      <?php foreach ($contact['pricing']['body'] as $paragraph) : ?>
        <p><?php echo e($paragraph); ?></p>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="contact-mail">
    <div class="section-inner section-inner--narrow">
      <p class="section-label"><?php echo e($contact['form']['label']); ?></p>
      <h2><?php echo e($contact['form']['title']); ?></h2>
      <p><?php echo e($contact['form']['body']); ?></p>
      <ul class="simple-list">
        <?php foreach ($contact['form']['items'] as $item) : ?>
          <li><?php echo e($item); ?></li>
        <?php endforeach; ?>
      </ul>
      <a class="button button--primary" href="<?php echo e($mail_href); ?>">メールを作成する</a>
    </div>
  </section>

  <section class="company-link">
    <div class="section-inner section-inner--narrow">
      <p class="section-label"><?php echo e($contact['company_link']['label']); ?></p>
      <h2><?php echo e($contact['company_link']['title']); ?></h2>
      <p><?php echo e($contact['company_link']['body']); ?></p>
      <a class="text-link" href="<?php echo e($contact['company_link']['link']['url']); ?>">
        <?php echo e($contact['company_link']['link']['label']); ?>
      </a>
    </div>
  </section>
</main>

<?php include __DIR__ . '/include/footer.php'; ?>
