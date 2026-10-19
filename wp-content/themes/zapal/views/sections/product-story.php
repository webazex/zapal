<?php

$productData = is_array($args['data'] ?? null) ? $args['data'] : [];
$info = is_array($productData['info'] ?? null) ? $productData['info'] : [];

$title = $info['story-title'] ?? '';
$content = is_string($args['content'] ?? null)
    ? trim($args['content'])
    : '';

$tags = is_array($args['tags'] ?? null)
    ? $args['tags']
    : [];

if (!$title && $content === '' && !$tags) {
    return;
}

?>
<section class="product-story">
    <div class="shell product-story-grid">
        <?php
        WBZX_Zapal_get_blocks('h2', [
            'content' => $title,
            'reveal' => true,
        ]);
        ?>

        <div class="product-story-copy" data-reveal="">
            <?php if ($content !== ''): ?>
                <?= wp_kses_post(apply_filters('the_content', $content)) ?>
            <?php endif; ?>

            <?php if ($tags): ?>
                <div class="use-tags">
                    <?php foreach ($tags as $tag): ?>
                        <?php
                        if (!$tag instanceof WP_Term || trim($tag->name) === '') {
                            continue;
                        }
                        ?>
                        <span><?= esc_html($tag->name) ?></span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
