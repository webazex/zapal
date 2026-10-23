<?php
/** Final WordPress template fallback for posts and archives. */
get_header();

$heading = is_archive()
    ? get_the_archive_title()
    : (is_home() && get_option('page_for_posts')
        ? get_the_title((int)get_option('page_for_posts'))
        : get_bloginfo('name'));
?>
<main>
    <section class="subhero" data-header="dark">
        <div class="shell">
            <div class="kicker">ZAPAL</div>
            <h1><?= esc_html(wp_strip_all_tags($heading)) ?></h1>
        </div>
    </section>
    <section class="catalog">
        <div class="shell">
            <?php if (have_posts()): ?>
                <div class="catalog-list">
                    <?php while (have_posts()): the_post(); ?>
                        <?php
                        $postTitle = get_the_title();
                        $image = get_the_post_thumbnail_url(get_the_ID(), 'large');
                        ?>
                        <a class="catalog-item<?= $image ? '' : ' catalog-art-item' ?>"
                           href="<?= esc_url(get_permalink()) ?>">
                            <?php if ($image): ?>
                                <img src="<?= esc_url($image) ?>"
                                     alt="<?= esc_attr($postTitle) ?>" loading="lazy"/>
                            <?php endif; ?>
                            <div>
                                <h2><?= esc_html($postTitle) ?></h2>
                                <p><?= esc_html(wp_trim_words(get_the_excerpt(), 24, '…')) ?></p>
                            </div>
                        </a>
                    <?php endwhile; ?>
                </div>
                <div class="zapal-pagination">
                    <?php the_posts_pagination(['mid_size' => 1]); ?>
                </div>
            <?php else: ?>
                <?php WBZX_Zapal_get_section('no-content'); ?>
            <?php endif; ?>
        </div>
    </section>
</main>
<?php get_footer(); ?>
