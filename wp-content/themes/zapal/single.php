<?php
/**
 * Fallback for single posts. WordPress normally selects single-products.php
 * for the products custom post type, but the product is routed explicitly
 * here too, instead of showing the old construction placeholder.
 */
if (is_singular('products')) {
    get_template_part('single-products');
    return;
}

get_header();
?>
<main>
    <?php if (have_posts()): ?>
        <?php while (have_posts()): the_post(); ?>
            <article id="post-<?= (int)get_the_ID() ?>">
                <section class="subhero" data-header="dark">
                    <div class="shell">
                        <h1><?= esc_html(get_the_title()) ?></h1>
                    </div>
                </section>
                <section class="product-story">
                    <div class="shell product-story-grid">
                        <div class="product-story-copy">
                            <?php if (has_post_thumbnail()): ?>
                                <?= get_the_post_thumbnail(get_the_ID(), 'large', ['loading' => 'lazy']) ?>
                            <?php endif; ?>
                            <?php the_content(); ?>
                        </div>
                    </div>
                </section>
            </article>
        <?php endwhile; ?>
    <?php else: ?>
        <?php WBZX_Zapal_get_section('no-content'); ?>
    <?php endif; ?>
</main>
<?php get_footer(); ?>
