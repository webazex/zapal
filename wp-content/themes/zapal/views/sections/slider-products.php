<?php

$title = $args['h2-content'] ?? '';
$subtitle = $args['subtitle'] ?? '';
$description = $args['descr'] ?? '';
$sliderText = $args['slider-text'] ?? '';
$showSelected = !empty($args['sauge-data-status']);

if ($showSelected) {
    $selectedSauces = is_array($args['selected_sauges'] ?? null)
        ? $args['selected_sauges']
        : [];

    $selectedIds = array_values(array_filter(array_map(
        static function ($item): int {
            if ($item instanceof WP_Post) {
                return $item->ID;
            }

            return is_numeric($item) ? (int)$item : 0;
        },
        $selectedSauces
    )));

    $products = $selectedIds
        ? WBZX_Zapal_products([
            'posts_per_page' => count($selectedIds),
            'post__in' => $selectedIds,
            'orderby' => 'post__in',
        ])
        : [];
} else {
    $products = WBZX_Zapal_products([
        'posts_per_page' => 10,
        'orderby' => 'date',
        'order' => 'DESC',
    ]);
}

?>
<section class="menu-section" id="products">
    <div class="shell section-head" data-reveal="">
        <div>
            <?php if ($subtitle): ?>
                <div class="kicker"><?= esc_html($subtitle) ?></div>
            <?php endif; ?>

            <?php WBZX_Zapal_get_blocks('h2', ['content' => $title]); ?>
        </div>

        <?php if ($description): ?>
            <p><?= esc_html($description) ?></p>
        <?php endif; ?>
    </div>

    <div class="product-rail-wrap">
        <div aria-label="<?= esc_attr__('Продукти ZAPAL', 'zapal') ?>"
             class="product-rail"
             data-product-rail="">
            <?php foreach (array_values($products) as $index => $product): ?>
                <?php
                $categories = is_array($product['category'] ?? null) ? $product['category'] : [];
                $categoryName = $categories ? $categories[0]->name : '';
                $descriptionText = trim((string)($product['description'] ?? ''));

                if ($descriptionText === '') {
                    $descriptionText = wp_trim_words(
                        wp_strip_all_tags((string)($product['content'] ?? '')),
                        18,
                        '…'
                    );
                }
                ?>
                <a class="product-card" href="<?= esc_url($product['permalink'] ?? '') ?>">
                    <?php if (!empty($product['image_url'])): ?>
                        <img alt="<?= esc_attr($product['name'] ?? '') ?>"
                             class="card-bg"
                             src="<?= esc_url($product['image_url']) ?>"/>
                    <?php endif; ?>

                    <?php if (!empty($product['recipe_image_url'])): ?>
                        <img alt=""
                             class="card-bg card-alt"
                             loading="lazy"
                             src="<?= esc_url($product['recipe_image_url']) ?>"/>
                    <?php endif; ?>

                    <span class="num">
                        <?= esc_html(sprintf('%02d', $index + 1)) ?>
                        <?php if ($categoryName): ?>
                            / <?= esc_html(mb_strtoupper($categoryName)) ?>
                        <?php endif; ?>
                    </span>

                    <div class="card-bottom">
                        <h3><?= esc_html($product['name'] ?? '') ?></h3>

                        <?php if ($descriptionText): ?>
                            <p><?= esc_html($descriptionText) ?></p>
                        <?php endif; ?>

                        <span class="circle-link">↗</span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="shell rail-controls">
        <?php if ($sliderText): ?>
            <div class="rail-hint"><?= esc_html($sliderText) ?></div>
        <?php endif; ?>

        <div aria-hidden="true" class="rail-progress">
            <i data-rail-progress=""></i>
        </div>

        <div aria-label="<?= esc_attr__('Навігація продуктами', 'zapal') ?>" class="rail-buttons">
            <button aria-label="<?= esc_attr__('Попередні продукти', 'zapal') ?>"
                    class="rail-button"
                    data-rail-prev=""
                    type="button">←</button>
            <button aria-label="<?= esc_attr__('Наступні продукти', 'zapal') ?>"
                    class="rail-button"
                    data-rail-next=""
                    type="button">→</button>
        </div>
    </div>
</section>
