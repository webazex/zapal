<?php

$title = $args['h2-content'] ?? '';
$kicker = $args['kicker'] ?? '';
$cases = is_array($args['cases'] ?? null) ? $args['cases'] : [];
$image = is_array($args['img'] ?? null) ? $args['img'] : null;

?>
<section class="use-section">
    <div class="shell use-grid">
        <div class="use-copy" data-reveal="">
            <div>
                <?php if ($kicker): ?>
                    <div class="kicker"><?= esc_html($kicker) ?></div>
                <?php endif; ?>

                <?php WBZX_Zapal_get_blocks('h2', ['content' => $title]); ?>
            </div>

            <?php if ($cases): ?>
                <div class="use-list">
                    <?php foreach ($cases as $case): ?>
                        <?php if (!empty($case['case'])): ?>
                            <div><?= esc_html($case['case']) ?></div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($image && !empty($image['url'])): ?>
            <div class="use-image use-image-poster" data-reveal="">
                <img alt="<?= esc_attr($image['alt'] ?? '') ?>"
                     loading="lazy"
                     src="<?= esc_url($image['url']) ?>"/>
            </div>
        <?php endif; ?>
    </div>
</section>
