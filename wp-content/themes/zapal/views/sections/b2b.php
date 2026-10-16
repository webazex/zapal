<?php

$title = $args['h2-content'] ?? '';
$kicker = $args['kicker'] ?? '';
$description = $args['descr'] ?? '';
$image = is_array($args['img'] ?? null) ? $args['img'] : null;
$packagingTitle = $args['packaging-title'] ?? '';
$packaging = is_array($args['packaging'] ?? null) ? $args['packaging'] : [];
$link = is_array($args['link'] ?? null) ? $args['link'] : null;

?>
<section class="packaging packaging-v6" id="b2b">
    <div class="shell packaging-head" data-reveal="">
        <div>
            <?php if ($kicker): ?>
                <div class="kicker"><?= esc_html($kicker) ?></div>
            <?php endif; ?>

            <?php WBZX_Zapal_get_blocks('h2', ['content' => $title]); ?>
        </div>

        <?php if ($description): ?>
            <p><?= esc_html($description) ?></p>
        <?php endif; ?>
    </div>

    <div class="shell packaging-stage order-stage">
        <?php if ($image && !empty($image['url'])): ?>
            <figure class="packaging-visual">
                <img alt="<?= esc_attr($image['alt'] ?? '') ?>"
                     loading="lazy"
                     src="<?= esc_url($image['url']) ?>"/>
            </figure>
        <?php endif; ?>

        <div class="order-table-wrap">
            <?php if ($packagingTitle): ?>
                <div class="kicker"><?= esc_html($packagingTitle) ?></div>
            <?php endif; ?>

            <?php if ($packaging): ?>
                <div aria-label="<?= esc_attr__('Умови B2B замовлення', 'zapal') ?>"
                     class="order-table"
                     role="table">
                    <div class="order-row order-head" role="row">
                        <span role="columnheader"><?= esc_html__('Група', 'zapal') ?></span>
                        <span role="columnheader"><?= esc_html__('Тара', 'zapal') ?></span>
                        <span role="columnheader"><?= esc_html__('Мін. замовлення', 'zapal') ?></span>
                    </div>

                    <?php foreach ($packaging as $row): ?>
                        <div class="order-row" role="row">
                            <strong role="cell"><?= esc_html($row['groupe'] ?? '') ?></strong>
                            <span role="cell"><?= esc_html($row['package'] ?? '') ?></span>
                            <span role="cell"><?= esc_html($row['val'] ?? '') ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if ($link && !empty($link['url'])): ?>
                <a class="text-link"
                   href="<?= esc_url($link['url']) ?>"
                   <?php if (!empty($link['target'])): ?>target="<?= esc_attr($link['target']) ?>"<?php endif; ?>>
                    <?= esc_html($link['title'] ?? __('Уточнити умови', 'zapal')) ?>
                    <span>↗</span>
                </a>
            <?php endif; ?>
        </div>
    </div>
</section>
