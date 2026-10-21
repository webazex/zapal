<?php
/**
 * Product catalogue and filtered taxonomy landing pages.
 * Reuses the catalogue CSS and product mapper used by the homepage carousel.
 */

$term = $args['term'] ?? null;
$isTerm = $term instanceof WP_Term;
$kicker = trim((string)($args['kicker'] ?? ''));
$bottomRight = trim((string)($args['bottom_right'] ?? ''));
$title = $isTerm ? $term->name : ($args['title'] ?? __('Продукція', 'zapal'));
$description = $isTerm
    ? wp_strip_all_tags(term_description($term->term_id, $term->taxonomy))
    : ($args['description'] ?? '');

$queryArgs = [
    'post_type' => 'products',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'orderby' => ['menu_order' => 'ASC', 'title' => 'ASC'],
];
if ($isTerm) {
    $queryArgs['tax_query'] = [[
        'taxonomy' => $term->taxonomy,
        'field' => 'term_id',
        'terms' => [$term->term_id],
        'include_children' => true,
    ]];
}

$products = array_values(WBZX_Zapal_products($queryArgs));
$groups = [];

if ($isTerm) {
    $groups[] = ['term' => $term, 'products' => $products];
} else {
    $terms = get_terms([
        'taxonomy' => 'cproducts',
        'hide_empty' => false,
        'orderby' => 'name',
        'order' => 'ASC',
    ]);
    $terms = is_wp_error($terms) || !is_array($terms) ? [] : $terms;
    $categorized = [];

    foreach ($terms as $category) {
        $categoryProducts = [];
        foreach ($products as $product) {
            $productCategories = is_array($product['category'] ?? null)
                ? $product['category']
                : [];

            foreach ($productCategories as $productCategory) {
                if ($productCategory instanceof WP_Term && (int)$productCategory->term_id === (int)$category->term_id) {
                    $categoryProducts[] = $product;
                    $categorized[(int)$product['id']] = true;
                    break;
                }
            }
        }
        if ($categoryProducts) {
            $groups[] = ['term' => $category, 'products' => $categoryProducts];
        }
    }

    $uncategorized = array_values(array_filter($products, static function (array $product) use ($categorized): bool {
        return !isset($categorized[(int)$product['id']]);
    }));
    if ($uncategorized) {
        $groups[] = ['term' => null, 'products' => $uncategorized];
    }
}

$catalogUrl = $args['catalog_url'] ?? '';
if (!$catalogUrl) {
    $catalogPage = get_page_by_path('catalog');
    $catalogUrl = $catalogPage instanceof WP_Post ? get_permalink($catalogPage->ID) : home_url('/catalog/');
}
?>
<section class="subhero" data-header="dark">
    <div class="shell">
        <?php if ($kicker !== ''): ?>
            <div class="kicker"><?= esc_html($kicker) ?></div>
        <?php endif; ?>
        <h1><?= esc_html($title) ?></h1>
        <div class="subhero-bottom">
            <?php if ($description): ?>
                <p><?= esc_html($description) ?></p>
            <?php endif; ?>
            <?php if ($bottomRight !== '' || $isTerm): ?>
                <div class="subhero-side">
                    <?php if ($bottomRight !== ''): ?>
                        <div class="kicker"><?= esc_html($bottomRight) ?></div>
                    <?php endif; ?>
                    <?php if ($isTerm): ?>
                        <a class="text-link" href="<?= esc_url($catalogUrl) ?>">
                            ← <?= esc_html__('Уся продукція', 'zapal') ?>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php if (!$isTerm && $groups): ?>
    <nav class="catalog-anchor" aria-label="<?= esc_attr__('Категорії продукції', 'zapal') ?>" data-catalog-nav="">
        <div class="shell catalog-anchor-inner">
            <a class="active" href="#catalog-start"><?= esc_html__('Усі', 'zapal') ?></a>
            <?php foreach ($groups as $group): ?>
                <?php $groupTerm = $group['term']; ?>
                <a href="#<?= $groupTerm instanceof WP_Term ? 'category-' . (int)$groupTerm->term_id : 'category-other' ?>">
                    <?= esc_html($groupTerm instanceof WP_Term ? $groupTerm->name : __('Інші соуси', 'zapal')) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </nav>
<?php endif; ?>

<section class="catalog" id="catalog-start">
    <div class="shell">
        <?php if (!$products): ?>
            <p><?= esc_html__('Продукцію поки не додано.', 'zapal') ?></p>
        <?php endif; ?>

        <?php foreach ($groups as $group): ?>
            <?php
            $groupTerm = $group['term'];
            $groupTitle = $groupTerm instanceof WP_Term ? $groupTerm->name : __('Інші соуси', 'zapal');
            $groupId = $groupTerm instanceof WP_Term ? 'category-' . (int)$groupTerm->term_id : 'category-other';
            $groupDescription = $groupTerm instanceof WP_Term
                ? wp_strip_all_tags(term_description($groupTerm->term_id, $groupTerm->taxonomy))
                : '';
            $termLink = $groupTerm instanceof WP_Term ? get_term_link($groupTerm) : '';
            $termLink = is_wp_error($termLink) ? '' : $termLink;
            ?>
            <section class="catalog-group" id="<?= esc_attr($groupId) ?>">
                <div class="catalog-group-head">
                    <h2><?= esc_html($groupTitle) ?></h2>
                    <?php if (!$isTerm && $groupDescription): ?>
                        <p><?= esc_html($groupDescription) ?></p>
                    <?php endif; ?>
                    <?php if (!$isTerm && $termLink): ?>
                        <a class="text-link" href="<?= esc_url($termLink) ?>">
                            <?= esc_html__('Переглянути категорію', 'zapal') ?> ↗
                        </a>
                    <?php endif; ?>
                </div>
                <div class="catalog-list<?= count($group['products']) >= 6 ? ' hot-catalog-list' : '' ?>">
                    <?php foreach ($group['products'] as $product): ?>
                        <?php
                        $name = (string)($product['name'] ?? '');
                        $descriptionText = trim((string)($product['description'] ?? ''));
                        if ($descriptionText === '') {
                            $descriptionText = wp_trim_words(wp_strip_all_tags((string)($product['content'] ?? '')), 18, '…');
                        }
                        $imageUrl = (string)($product['image_url'] ?? '');
                        $recipeImageUrl = (string)($product['recipe_image_url'] ?? '');
                        ?>
                        <a class="catalog-item<?= $imageUrl ? '' : ' catalog-art-item' ?>"
                           href="<?= esc_url($product['permalink'] ?? '') ?>">
                            <?php if ($imageUrl): ?>
                                <img alt="<?= esc_attr($name) ?>"
                                     loading="lazy"
                                     src="<?= esc_url($imageUrl) ?>"/>
                                <?php if ($recipeImageUrl): ?>
                                    <img class="catalog-alt" alt="" loading="lazy"
                                         src="<?= esc_url($recipeImageUrl) ?>"/>
                                <?php endif; ?>
                            <?php endif; ?>
                            <span class="tag"><?= esc_html($groupTitle) ?></span>
                            <div>
                                <h3><?= esc_html($name) ?></h3>
                                <?php if ($descriptionText): ?>
                                    <p><?= esc_html($descriptionText) ?></p>
                                <?php endif; ?>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endforeach; ?>
    </div>
</section>
