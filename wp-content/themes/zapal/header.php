<!DOCTYPE html>
<html data-theme="light" lang="<?= esc_attr(get_bloginfo('language')) ?>">
<head>
    <meta charset="<?= esc_attr(get_bloginfo('charset')) ?>"/>
    <meta content="width=device-width,initial-scale=1" name="viewport"/>
    <meta content="#f4f0e7" name="theme-color"/>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&amp;display=swap"
          rel="stylesheet"/>
    <?php wp_head(); ?>
</head>
<body <?php body_class('is-loading'); ?>>
<?php wp_body_open(); ?>

<div aria-label="<?= esc_attr__('Помилка відправки форми', 'zapal') ?>"
     aria-live="assertive"
     aria-modal="true"
     class="responce-error"
     data-response-error=""
     hidden=""
     role="alertdialog">
    <div class="responce-error__inner">
        <p data-response-error-text=""></p>
        <button data-response-error-close="" type="button">
            <?= esc_html__('Закрити', 'zapal') ?>
        </button>
    </div>
</div>

<div aria-label="<?= esc_attr__('Результат відправки форми', 'zapal') ?>"
     aria-live="polite"
     aria-modal="true"
     class="form-feedback"
     data-form-feedback=""
     role="dialog">
    <div class="form-feedback-card">
        <button aria-label="<?= esc_attr__('Закрити', 'zapal') ?>"
                class="form-feedback-close"
                data-form-feedback-close=""
                type="button">×</button>
        <div aria-hidden="true" class="form-feedback-mark" data-form-feedback-mark="">✓</div>
        <div class="form-feedback-kicker"><?= esc_html__("Зворотний зв’язок", 'zapal') ?></div>
        <h3 data-form-feedback-title=""></h3>
        <p data-form-feedback-text=""></p>
    </div>
</div>

<div aria-hidden="true" class="site-preloader" data-preloader="">
    <div class="preloader-inner">
        <div class="preloader-mark">
            <img alt="" src="<?= esc_url(WBZX_ZAPAL_ASSET_DIR_URI . 'img/logo.svg') ?>"/>
        </div>
        <div class="preloader-word">ZAPAL</div>
        <div class="preloader-line"><i></i></div>
        <div class="preloader-caption">Professional Solutions for HoReCa</div>
    </div>
</div>

<header class="site-header">
    <div class="shell header-inner">
        <a aria-label="<?= esc_attr__('ZAPAL — головна', 'zapal') ?>"
           class="logo-lockup"
           href="<?= esc_url(home_url('/')) ?>">
            <img alt=""
                 class="logo-mark"
                 src="<?= esc_url(WBZX_ZAPAL_ASSET_DIR_URI . 'img/logo.svg') ?>"/>
            <span>ZAPAL</span>
        </a>

        <nav aria-label="<?= esc_attr__('Головна навігація', 'zapal') ?>" class="nav">
            <a href="<?= esc_url(home_url('/catalog/')) ?>"><?= esc_html__('Продукція', 'zapal') ?></a>
            <a href="<?= esc_url(home_url('/#b2b')) ?>">B2B</a>
            <a href="<?= esc_url(home_url('/about/')) ?>"><?= esc_html__('Про нас', 'zapal') ?></a>
            <a class="header-cta" href="<?= esc_url(home_url('/#contact')) ?>">
                <?= esc_html__('Задати питання', 'zapal') ?>
            </a>
        </nav>

        <button aria-label="<?= esc_attr__('Увімкнути темну тему', 'zapal') ?>"
                aria-pressed="false"
                class="theme-toggle"
                data-theme-toggle=""
                title="<?= esc_attr__('Темна тема', 'zapal') ?>"
                type="button">
            <span aria-hidden="true" class="theme-toggle-icon"></span>
        </button>

        <button aria-label="<?= esc_attr__('Меню', 'zapal') ?>" class="menu-toggle">
            <i></i>
        </button>
    </div>
</header>
