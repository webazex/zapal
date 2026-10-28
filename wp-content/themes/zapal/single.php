<?php
/**
 * Generic single fallback for posts and CPTs without a specific single template.
 * WordPress selects single-products.php for sauce pages automatically.
 */
get_header();
?>
<main>
    <?php if (have_posts()): ?>
        <?php while (have_posts()): the_post(); ?>
            <?php
            $postType = get_post_type_object(get_post_type());
            $postTypeName = $postType ? $postType->labels->singular_name : __('Матеріал', 'zapal');
            ?>
            <article <?php post_class('zapal-single'); ?> id="post-<?= (int)get_the_ID() ?>">
                <header class="zapal-single__hero" data-header="dark">
                    <div class="shell">
                        <div class="kicker">ZAPAL / <?= esc_html($postTypeName) ?></div>
                        <h1><?= esc_html(get_the_title()) ?></h1>
                    </div>
                </header>

                <div class="zapal-single__body">
                    <div class="shell">
                        <?php if (has_post_thumbnail()): ?>
                            <figure class="zapal-single__image">
                                <?= get_the_post_thumbnail(get_the_ID(), 'large', ['loading' => 'eager']) ?>
                            </figure>
                        <?php endif; ?>

                        <div class="zapal-single__content">
                            <?php the_content(); ?>
                            <?php
                            wp_link_pages([
                                'before' => '<nav class="zapal-single__pagination" aria-label="' . esc_attr__('Сторінки матеріалу', 'zapal') . '">',
                                'after' => '</nav>',
                            ]);
                            ?>
                        </div>
                    </div>
                </div>
            </article>
        <?php endwhile; ?>
    <?php else: ?>
        <?php WBZX_Zapal_get_section('no-content'); ?>
    <?php endif; ?>
</main>
<?php get_footer(); ?>
