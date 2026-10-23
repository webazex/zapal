<?php

$productData = is_array($args['data'] ?? null) ? $args['data'] : [];
$info = is_array($productData['info'] ?? null) ? $productData['info'] : [];

$title = $info['story-title'] ?? '';
$productId = absint($args['id'] ?? 0);
$hasSavedStory = $productId > 0
    && metadata_exists('post', $productId, 'data-product_info_story-content');

// ACF WYSIWYG is already formatted by get_field(). Unmigrated posts continue
// to use their original post_content until the editor saves the new field.
$acfContent = $info['story-content'] ?? '';
$legacyContent = is_string($args['content'] ?? null) ? trim($args['content']) : '';
$content = $hasSavedStory
    ? (is_string($acfContent) ? trim($acfContent) : '')
    : $legacyContent;

$renderedContent = $hasSavedStory
    ? wp_kses_post($content)
    : ($content !== '' ? wp_kses_post(apply_filters('the_content', $content)) : '');

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
            <?php if ($renderedContent !== ''): ?>
                <?= $renderedContent ?>
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
