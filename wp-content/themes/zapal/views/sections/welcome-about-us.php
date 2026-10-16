<?php

$title = $args['title-h1'] ?? '';
$kicker = $args['kicker'] ?? '';
$rightSubtitle = $args['right-subtitle'] ?? '';
$description = $args['description'] ?? '';

?>
<section class="about-hero" data-header="dark">
    <div class="shell about-hero-grid">
        <div class="about-hero-copy">
            <?php if ($kicker): ?>
                <div class="kicker"><?= esc_html($kicker) ?></div>
            <?php endif; ?>

            <?php if ($title): ?>
                <h1><?= WBZX_Zapal_heading_content($title) ?></h1>
            <?php endif; ?>
        </div>

        <?php if ($rightSubtitle || $description): ?>
            <div class="about-hero-note">
                <?php if ($rightSubtitle): ?>
                    <strong><?= esc_html($rightSubtitle) ?></strong>
                <?php endif; ?>

                <?php if ($description): ?>
                    <p><?= esc_html($description) ?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
