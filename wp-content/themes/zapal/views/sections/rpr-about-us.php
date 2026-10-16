<?php

$kicker = $args['kicker'] ?? '';
$title = $args['h2-content'] ?? '';
$content = $args['content'] ?? '';

?>
<section class="about-block about-block-dark" data-header="dark">
    <div class="shell about-block-grid">
        <div>
            <?php if ($kicker): ?>
                <div class="kicker"><?= esc_html($kicker) ?></div>
            <?php endif; ?>

            <?php WBZX_Zapal_get_blocks('h2', ['content' => $title]); ?>
        </div>

        <?php if ($content): ?>
            <div class="about-body">
                <?= wp_kses_post($content) ?>
            </div>
        <?php endif; ?>
    </div>
</section>
