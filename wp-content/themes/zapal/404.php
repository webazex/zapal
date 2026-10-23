<?php
/** Proper 404 document; do not fall through to the development placeholder. */
status_header(404);
nocache_headers();
get_header();
?>
<main>
    <section class="no-content" data-header="dark" aria-labelledby="zapal-404-title">
        <div class="no-content__inner">
            <div class="no-content__meta" aria-hidden="true"><span>404</span><span>ZAPAL</span></div>
            <div class="no-content__content">
                <div class="no-content__kicker">ZAPAL · HoReCa</div>
                <h1 class="no-content__title" id="zapal-404-title">
                    <?= esc_html__('Сторінку не знайдено.', 'zapal') ?>
                </h1>
                <p class="no-content__text">
                    <?= esc_html__('Перевірте адресу або поверніться на головну.', 'zapal') ?>
                </p>
                <a class="btn" href="<?= esc_url(WBZX_Zapal_home_url()) ?>">
                    <?= esc_html__('На головну', 'zapal') ?> ↗
                </a>
            </div>
        </div>
    </section>
</main>
<?php get_footer(); ?>
