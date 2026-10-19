<?php

$productData = is_array($args['data'] ?? null) ? $args['data'] : [];
$productName = trim((string)($args['name'] ?? ''));
$productImageUrl = trim((string)($args['image_url'] ?? ''));

$recipes = $productData['receipes'] ?? [];
if ($recipes instanceof WP_Post) {
    $recipes = [$recipes];
}
$recipes = is_array($recipes) ? array_values(array_filter(
    $recipes,
    static fn($recipe): bool => $recipe instanceof WP_Post
)) : [];

$packages = is_array($productData['packages'] ?? null)
    ? $productData['packages']
    : [];

$recipeItems = [];

foreach ($recipes as $recipe) {
    $recipeId = (int)$recipe->ID;

    if ($recipeId <= 0) {
        continue;
    }

    $recipeItems[] = [
        'id' => 'recipe-' . $recipeId,
        'title' => get_the_title($recipeId),
        'image' => get_the_post_thumbnail_url($recipeId, 'large') ?: '',
        'content' => (string)$recipe->post_content,
    ];
}

$firstRecipe = $recipeItems[0] ?? null;

?>
<section class="gallery gallery-with-recipes" data-header="dark">
    <div class="shell gallery-layout gallery-layout-recipes">
        <div class="gallery-main">
            <nav aria-label="<?= esc_attr__('Галерея продукту', 'zapal') ?>"
                 class="gallery-nav">
                <button
                    class="active"
                    data-gallery-button="product"
                    type="button"
                ><?= esc_html__('Продукт', 'zapal') ?></button>

                <?php if ($recipeItems): ?>
                    <div class="gallery-nav-recipe-group">
                        <button
                            data-gallery-button="recipes"
                            type="button"
                        ><?= esc_html__('Рецепти', 'zapal') ?></button>

                        <div
                            aria-label="<?= esc_attr(sprintf(
                                __('Рецепти %s', 'zapal'),
                                $productName
                            )) ?>"
                            class="gallery-recipe-subnav"
                            data-recipe-subnav=""
                            hidden=""
                        >
                            <?php foreach ($recipeItems as $index => $recipe): ?>
                                <button
                                    <?= $index === 0 ? 'class="active"' : '' ?>
                                    data-recipe-image="<?= esc_url($recipe['image']) ?>"
                                    data-recipe-nav="<?= esc_attr($recipe['id']) ?>"
                                    data-recipe-title="<?= esc_attr($recipe['title']) ?>"
                                    type="button"
                                ><?= esc_html($recipe['title']) ?></button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($packages): ?>
                    <button
                        data-gallery-button="pack"
                        type="button"
                    ><?= esc_html__('Фасування', 'zapal') ?></button>
                <?php endif; ?>
            </nav>

            <div class="gallery-stage" data-reveal="">
                <?php if ($productImageUrl): ?>
                    <figure
                        class="gallery-panel active gallery-panel-horeca"
                        data-gallery-panel="product"
                        style="margin:0"
                    >
                        <img
                            alt="<?= esc_attr($productName) ?>"
                            src="<?= esc_url($productImageUrl) ?>"
                        />
                    </figure>
                <?php endif; ?>

                <?php if ($firstRecipe): ?>
                    <figure
                        class="gallery-panel gallery-panel-recipe"
                        data-gallery-panel="recipes"
                        style="margin:0"
                    >
                        <?php if ($firstRecipe['image']): ?>
                            <img
                                alt="<?= esc_attr($firstRecipe['title']) ?>"
                                data-recipe-hero=""
                                src="<?= esc_url($firstRecipe['image']) ?>"
                            />
                        <?php endif; ?>

                        <figcaption>
                            <span data-recipe-image-title="">
                                <?= esc_html($firstRecipe['title']) ?>
                            </span>
                        </figcaption>
                    </figure>
                <?php endif; ?>

                <?php if ($packages): ?>
                    <div
                        aria-label="<?= esc_attr__('Фасування та логістика ZAPAL', 'zapal') ?>"
                        class="gallery-panel gallery-panel-pack"
                        data-gallery-panel="pack"
                    >
                        <?php foreach ($packages as $package): ?>
                            <?php
                            $image = is_array($package['img'] ?? null)
                                ? $package['img']
                                : [];

                            $imageId = !empty($image['ID'])
                                ? (int)$image['ID']
                                : 0;

                            $imageUrl = $image['url'] ?? '';
                            $imageAlt = $image['alt'] ?? '';
                            $packType = trim((string)($package['packtype'] ?? ''));

                            if (!$imageId && !$imageUrl) {
                                continue;
                            }
                            ?>
                            <figure class="pack-visual">
                                <?php if ($imageId): ?>
                                    <?= wp_get_attachment_image(
                                        $imageId,
                                        'large',
                                        false,
                                        [
                                            'loading' => 'lazy',
                                            'alt' => $imageAlt ?: $packType,
                                        ]
                                    ) ?>
                                <?php else: ?>
                                    <img
                                        alt="<?= esc_attr($imageAlt ?: $packType) ?>"
                                        loading="lazy"
                                        src="<?= esc_url($imageUrl) ?>"
                                    />
                                <?php endif; ?>

                                <?php if ($packType): ?>
                                    <figcaption><?= esc_html($packType) ?></figcaption>
                                <?php endif; ?>
                            </figure>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($recipeItems): ?>
            <section class="product-recipes" data-gallery-recipes="" hidden="">
                <div class="recipe-inline-head">
                    <div class="kicker">
                        <?= esc_html(
                            $productName
                                ? sprintf(__('%s · рецепт', 'zapal'), $productName)
                                : __('Рецепт', 'zapal')
                        ) ?>
                    </div>
                </div>

                <div class="dish-panels">
                    <?php foreach ($recipeItems as $index => $recipe): ?>
                        <article
                            class="dish-panel<?= $index === 0 ? ' active' : '' ?>"
                            data-dish-panel="<?= esc_attr($recipe['id']) ?>"
                            <?= $index === 0 ? '' : 'hidden=""' ?>
                            role="tabpanel"
                        >
                            <div class="dish-number">
                                <?= esc_html(str_pad(
                                    (string)($index + 1),
                                    2,
                                    '0',
                                    STR_PAD_LEFT
                                )) ?>
                            </div>

                            <div>
                                <h3><?= esc_html($recipe['title']) ?></h3>

                                <?php if (trim($recipe['content']) !== ''): ?>
                                    <div class="recipe-source-text">
                                        <?= wp_kses_post(apply_filters(
                                            'the_content',
                                            $recipe['content']
                                        )) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>
    </div>
</section>
