<?php

$sauceId = !empty($args['sauge-link']) ? (int)$args['sauge-link'] : 0;
$sauceLink = $sauceId ? get_permalink($sauceId) : '';
$sauceImg = is_array($args['img'] ?? null) ? $args['img'] : null;

?>
<section class="hero">
    <div aria-hidden="true" class="hero-word">
        <?= esc_html($args['background-text'] ?? '') ?>
    </div>

    <div class="shell hero-grid">
        <div class="hero-copy">
            <div class="kicker">
                <?= esc_html($args['left-small-title'] ?? '') ?>
            </div>

            <h1>
                <?= esc_html($args['title-h1'] ?? '') ?>
            </h1>

            <p><?= esc_html($args['description'] ?? '') ?></p>
        </div>

        <?php if ($sauceImg && $sauceLink): ?>
            <a aria-label="<?= esc_attr__('Відкрити продукт ZAPAL', 'zapal') ?>"
               class="hero-product"
               data-tilt-product=""
               href="<?= esc_url($sauceLink) ?>">
                <img alt="<?= esc_attr($sauceImg['alt'] ?? '') ?>"
                     class="hero-premium-packshot"
                     fetchpriority="high"
                     src="<?= esc_url($sauceImg['url'] ?? '') ?>"/>
            </a>
        <?php endif; ?>

        <div class="hero-side">
            <div class="hero-stat"><?= esc_html($args['b2b-title'] ?? '') ?></div>
            <p><?= esc_html($args['b2b-description'] ?? '') ?></p>
        </div>

        <div class="hero-bottom">
            <span class="scroll-cue">
                <i></i>
                <?= esc_html($args['left-bottom-text'] ?? '') ?>
            </span>
            <span><?= esc_html($args['right-bottom-text'] ?? '') ?></span>
        </div>
    </div>
</section>
