<?php

$title = $args['h2-content'] ?? '';
$description = $args['descr'] ?? '';
$link = $args['link'] ?? '';
$advantages = is_array($args['advantages'] ?? null) ? $args['advantages'] : [];

?>
<section class="story" id="about">
    <div class="shell story-grid">
        <?php
        WBZX_Zapal_get_blocks('h2', [
            'content' => $title,
            'class' => 'story-title',
            'reveal' => true,
        ]);
        ?>

        <div class="story-copy" data-reveal="">
            <?php if ($description): ?>
                <?= wp_kses_post($description) ?>
            <?php endif; ?>

            <?php if ($link): ?>
                <a class="text-link story-link" href="<?= esc_url($link) ?>">
                    <?= esc_html__('Про компанію', 'zapal') ?>
                    <span>↗</span>
                </a>
            <?php endif; ?>

            <?php if ($advantages): ?>
                <div class="story-stats">
                    <?php foreach ($advantages as $advantage): ?>
                        <div class="story-stat">
                            <strong><?= esc_html($advantage['title'] ?? '') ?></strong>
                            <span><?= esc_html($advantage['subtitle'] ?? '') ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
