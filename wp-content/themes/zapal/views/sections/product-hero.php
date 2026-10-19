<?php

$productData = is_array($args['data'] ?? null) ? $args['data'] : [];
$info = is_array($productData['info'] ?? null) ? $productData['info'] : [];
$properties = is_array($productData['properties'] ?? null)
    ? $productData['properties']
    : [];

$productName = $args['name'] ?? '';
$title = $info['h1-title'] ?? $productName;
$kicker = $info['kicker'] ?? '';
$subtitle = $info['subtitle'] ?? '';
$imageUrl = $args['image_url'] ?? '';

?>
<section class="product-hero">
    <?php
    WBZX_Zapal_get_templates('breadcrumbs', [
        'items' => [
            [
                'title' => __('Головна', 'zapal'),
                'url' => home_url('/'),
            ],
            [
                'title' => __('Продукція', 'zapal'),
                'url' => home_url('/catalog/'),
            ],
            [
                'title' => $productName ?: $title,
            ],
        ],
        'class' => 'product-breadcrumbs shell',
    ]);
    ?>

    <div class="shell product-hero-inner">
        <div class="product-name">
            <?php if ($kicker): ?>
                <div class="kicker"><?= esc_html($kicker) ?></div>
            <?php endif; ?>

            <?php if ($title): ?>
                <h1><?= esc_html($title) ?></h1>
            <?php endif; ?>

            <?php if ($subtitle): ?>
                <p><?= esc_html($subtitle) ?></p>
            <?php endif; ?>
        </div>

        <div class="product-stage" data-tilt-product="">
            <?php if ($imageUrl): ?>
                <img
                    alt="<?= esc_attr($title ?: $productName) ?>"
                    fetchpriority="high"
                    src="<?= esc_url($imageUrl) ?>"
                />
            <?php endif; ?>
        </div>

        <?php if ($properties): ?>
            <div class="product-facts">
                <dl>
                    <?php foreach ($properties as $property): ?>
                        <?php
                        $key = $property['key'] ?? '';
                        $value = $property['value'] ?? '';

                        if (!$key && !$value) {
                            continue;
                        }
                        ?>
                        <div>
                            <?php if ($key): ?>
                                <dt><?= esc_html($key) ?></dt>
                            <?php endif; ?>

                            <?php if ($value): ?>
                                <dd><?= esc_html($value) ?></dd>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </dl>
            </div>
        <?php endif; ?>
    </div>
</section>
