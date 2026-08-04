<section class="service-feature-section">
  <div class="section-inner">
    <div class="section-heading">
      <p class="section-label"><?php echo e($service['features']['label']); ?></p>
      <h2><?php echo responsive_text($service['features'], 'title'); ?></h2>
      <p><?php echo e($service['features']['lead']); ?></p>
    </div>
    <div class="content-grid content-grid--two">
      <?php foreach ($service['features']['items'] as $item) : ?>
        <article class="content-card service-feature-card">
          <p class="section-label"><?php echo e($item['label']); ?></p>
          <h3><?php echo e($item['title']); ?></h3>
          <p><?php echo e($item['body']); ?></p>

          <?php if (!empty($item['points'])) : ?>
            <ul class="service-feature-list">
              <?php foreach ($item['points'] as $point) : ?>
                <li><?php echo e($point); ?></li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>

          <?php if (!empty($item['methods'])) : ?>
            <div class="service-feature-methods">
              <?php foreach ($item['methods'] as $method) : ?>
                <div>
                  <h4><?php echo e($method['title']); ?></h4>
                  <p><?php echo e($method['body']); ?></p>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

          <p class="service-feature-note"><?php echo e($item['note']); ?></p>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
