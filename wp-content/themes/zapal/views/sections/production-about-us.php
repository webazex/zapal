<?php

$kicker = $args['kicker'] ?? '';
$title = $args['h2-content'] ?? '';
$content = $args['content'] ?? '';
$image = is_array($args['img'] ?? null) ? $args['img'] : [];

$imageId = !empty($image['ID']) ? (int)$image['ID'] : 0;
$imageUrl = $image['url'] ?? '';
$imageAlt = $image['alt'] ?? '';

?>
<section class="about-production">
    <div class="shell about-production-grid">
        <div class="about-production-copy">
            <?php if ($kicker): ?>
                <div class="kicker"><?= esc_html($kicker) ?></div>
            <?php endif; ?>

            <?php WBZX_Zapal_get_blocks('h2', ['content' => $title]); ?>

            <?php if ($content): ?>
                <?= wp_kses_post($content) ?>
            <?php endif; ?>
        </div>

        <?php if ($imageId || $imageUrl): ?>
            <figure>
                <?php if ($imageId): ?>
                    <?= wp_get_attachment_image(
                        $imageId,
                        'large',
                        false,
                        [
                            'loading' => 'lazy',
                            'alt' => $imageAlt,
                        ]
                    ) ?>
                <?php else: ?>
                    <img
                        alt="<?= esc_attr($imageAlt) ?>"
                        loading="lazy"
                        src="<?= esc_url($imageUrl) ?>"
                    />
                <?php endif; ?>
            </figure>
        <?php endif; ?>
    </div>
</section>
