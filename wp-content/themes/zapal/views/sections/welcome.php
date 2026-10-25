<?php

$sauceId = !empty($args['sauge-link']) ? (int)$args['sauge-link'] : 0;
$sauceLink = $sauceId && get_post_type($sauceId) === 'products' && get_post_status($sauceId) === 'publish'
    ? get_permalink($sauceId)
    : '';
$sauceImg = is_array($args['img'] ?? null) ? $args['img'] : null;
$backgroundText = trim((string)($args['background-text'] ?? ''));
$kicker = trim((string)($args['left-small-title'] ?? ''));
$title = trim((string)($args['title-h1'] ?? ''));
$description = trim((string)($args['description'] ?? ''));
$b2bTitle = trim((string)($args['b2b-title'] ?? ''));
$b2bDescription = trim((string)($args['b2b-description'] ?? ''));
$leftText = trim((string)($args['left-bottom-text'] ?? ''));
$rightText = trim((string)($args['right-bottom-text'] ?? ''));

?>
<section class="hero">
    <?php if ($backgroundText !== ''): ?>
        <div aria-hidden="true" class="hero-word"><?= esc_html($backgroundText) ?></div>
    <?php endif; ?>

    <div class="shell hero-grid">
        <?php if ($kicker !== '' || $title !== '' || $description !== ''): ?>
            <div class="hero-copy">
                <?php if ($kicker !== ''): ?>
                    <div class="kicker"><?= esc_html($kicker) ?></div>
                <?php endif; ?>

                <?php if ($title !== ''): ?>
                    <h1><?= esc_html($title) ?></h1>
                <?php endif; ?>

                <?php if ($description !== ''): ?>
                    <p><?= esc_html($description) ?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($sauceImg && $sauceLink && !empty($sauceImg['url'])): ?>
            <a aria-label="<?= esc_attr__('Відкрити продукт ZAPAL', 'zapal') ?>"
               class="hero-product"
               data-tilt-product=""
               href="<?= esc_url($sauceLink) ?>">
                <img alt="<?= esc_attr($sauceImg['alt'] ?? '') ?>"
                     class="hero-premium-packshot"
                     fetchpriority="high"
                     src="<?= esc_url($sauceImg['url']) ?>"/>
            </a>
        <?php endif; ?>

        <?php if ($b2bTitle !== '' || $b2bDescription !== ''): ?>
            <div class="hero-side">
                <?php if ($b2bTitle !== ''): ?>
                    <div class="hero-stat"><?= esc_html($b2bTitle) ?></div>
                <?php endif; ?>

                <?php if ($b2bDescription !== ''): ?>
                    <p><?= esc_html($b2bDescription) ?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($leftText !== '' || $rightText !== ''): ?>
            <div class="hero-bottom">
                <?php if ($leftText !== ''): ?>
                    <span class="scroll-cue"><i></i><?= esc_html($leftText) ?></span>
                <?php endif; ?>

                <?php if ($rightText !== ''): ?>
                    <span><?= esc_html($rightText) ?></span>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
