<?php
$kicker = $args['kicker'] ?? '';
$title = $args['h2-content'] ?? '';
$description = $args['description'] ?? '';
$advantages = $args['our-advantages'] ?? [];

?>
<section class="about-intro">
    <div class="shell about-intro-layout">
        <div class="about-intro-head">
            <?php if ($kicker): ?>
                <div class="kicker"><?= esc_html($kicker) ?></div>
            <?php endif; ?>

            <?php if ($title): ?>
                <h2><?= WBZX_Zapal_heading_content($title) ?></h2>
            <?php endif; ?>
        </div>

        <div class="about-intro-body">
            <?php if ($description): ?>
                <?= wpautop(esc_html($description)) ?>
            <?php endif; ?>

            <?php if (is_array($advantages) && $advantages): ?>
                <div class="about-intro-points">
                    <?php foreach ($advantages as $advantage): ?>
                        <?php
                        $key = $advantage['key'] ?? '';
                        $value = $advantage['value'] ?? '';

                        if (!$key && !$value) {
                            continue;
                        }
                        ?>
                        <div>
                            <?php if ($key): ?>
                                <strong><?= esc_html($key) ?></strong>
                            <?php endif; ?>

                            <?php if ($value): ?>
                                <span><?= esc_html($value) ?></span>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
