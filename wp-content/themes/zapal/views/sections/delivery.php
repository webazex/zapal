<?php

$title = $args['h2-content'] ?? '';
$kicker = $args['kicker'] ?? '';
$color = sanitize_hex_color($args['color'] ?? '') ?: '';

$deliveries = get_field('deliveries', 'option');
$deliveries = is_array($deliveries) ? $deliveries : [];

?>
<section class="logistics" id="delivery">
    <div class="shell logistics-grid">
        <div class="logistics-title" data-reveal="">
            <?php if ($kicker): ?>
                <div class="kicker"
                    <?php if ($color): ?>style="color: <?= esc_attr($color) ?>"<?php endif; ?>>
                    <?= esc_html($kicker) ?>
                </div>
            <?php endif; ?>

            <?php WBZX_Zapal_get_blocks('h2', ['content' => $title]); ?>
        </div>

        <?php if ($deliveries): ?>
            <div class="logistics-cards" data-reveal="">
                <?php foreach ($deliveries as $index => $delivery): ?>
                    <?php
                    $image = is_array($delivery['img'] ?? null)
                        ? $delivery['img']
                        : [];
                    $text = $delivery['delivery-text'] ?? '';

                    $imageId = !empty($image['ID']) ? (int)$image['ID'] : 0;
                    $imageUrl = $image['url'] ?? '';
                    $imageAlt = $image['alt'] ?? '';

                    if (!$imageId && !$imageUrl && !$text) {
                        continue;
                    }

                    $number = str_pad((string)($index + 1), 2, '0', STR_PAD_LEFT);
                    ?>
                    <article>
                        <span><?= esc_html($number) ?></span>

                        <div class="logistics-brand">
                            <?php if ($imageId): ?>
                                <?= wp_get_attachment_image(
                                    $imageId,
                                    'medium',
                                    false,
                                    [
                                        'loading' => 'lazy',
                                        'alt' => $imageAlt,
                                    ]
                                ) ?>
                            <?php elseif ($imageUrl): ?>
                                <img
                                    alt="<?= esc_attr($imageAlt) ?>"
                                    loading="lazy"
                                    src="<?= esc_url($imageUrl) ?>"
                                />
                            <?php endif; ?>

                            <?php if ($imageAlt): ?>
                                <h3 class="sr-only"><?= esc_html($imageAlt) ?></h3>
                            <?php endif; ?>
                        </div>

                        <?php if ($text): ?>
                            <p><?= esc_html($text) ?></p>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
