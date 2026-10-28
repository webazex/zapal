<?php

add_action('wp_default_scripts', function ($scripts) {
    if (is_admin()) {
        return;
    }

    if (isset($scripts->registered['jquery'])) {
        $scripts->registered['jquery']->deps = array_diff(
            $scripts->registered['jquery']->deps,
            ['jquery-migrate']
        );
    }

    $scripts->add_data('jquery', 'group', 1);
    $scripts->add_data('jquery-core', 'group', 1);
});

function zapal_enqueue_sources()
{
    $cssPath = get_stylesheet_directory() . '/style.css';
    $cssVersion = is_file($cssPath) ? (string)filemtime($cssPath) : null;

    $jsPath = WBZX_ZAPAL_ASSET_DIR . 'js' . DIRECTORY_SEPARATOR . 'main.js';
    $jsVersion = is_file($jsPath) ? (string)filemtime($jsPath) : null;
    $jsUri = WBZX_ZAPAL_ASSET_DIR_URI . 'js/main.js';

    wp_enqueue_style('zapal-css', get_stylesheet_uri(), [], $cssVersion);

    // The missing-page layout is isolated from shared site styles.
    if (is_404()) {
        $notFoundPath = WBZX_ZAPAL_ASSET_DIR . 'css' . DIRECTORY_SEPARATOR . '404.css';
        $notFoundVersion = is_file($notFoundPath) ? (string)filemtime($notFoundPath) : null;

        wp_enqueue_style(
            'zapal-404',
            WBZX_ZAPAL_ASSET_DIR_URI . 'css/404.css',
            ['zapal-css'],
            $notFoundVersion
        );
    }

    wp_enqueue_script(
        'zapal-js',
        $jsUri,
        ['jquery'],
        $jsVersion,
        ['in_footer' => true]
    );

    // Text for dynamic frontend elements. Polylang selects the active WordPress locale.
    // Keep wording in gettext catalogues, not in page-specific JS conditions.
    wp_localize_script('zapal-js', 'zapalI18n', [
        'successTitle' => __('Дякуємо! Запит отримано.', 'zapal'),
        'successMessage' => __('Ми отримали ваше звернення та зв’яжемося з вами.', 'zapal'),
        'errorMessage' => __('Не вдалося відправити запит. Спробуйте пізніше або зв’яжіться з нами напряму.', 'zapal'),
        'enableLightTheme' => __('Увімкнути світлу тему', 'zapal'),
        'enableDarkTheme' => __('Увімкнути темну тему', 'zapal'),
        'lightTheme' => __('Світла тема', 'zapal'),
        'darkTheme' => __('Темна тема', 'zapal'),
        'closeMenu' => __('Закрити меню', 'zapal'),
        'menu' => __('Меню', 'zapal'),
        'chooseRecipe' => __('Оберіть рецепт', 'zapal'),
        'product' => __('продукт', 'zapal'),
        'specificationRequest' => __('Хочу отримати технічну специфікацію для %s.', 'zapal'),
        'viewImage' => __('Перегляд зображення', 'zapal'),
        'close' => __('Закрити', 'zapal'),
        'previousImage' => __('Попереднє зображення', 'zapal'),
        'nextImage' => __('Наступне зображення', 'zapal'),
        'image' => __('Зображення', 'zapal'),
        'openLargeImage' => __('%s — відкрити у великому розмірі', 'zapal'),
        'sending' => __('Надсилаємо…', 'zapal'),
        'requiredFields' => __('Заповніть, будь ласка, усі обов’язкові поля.', 'zapal'),
    ]);

    $darkmodeEnabled = function_exists('get_field')
        ? (bool)get_field('darkmode', 'option')
        : false;

    if (!$darkmodeEnabled) {
        wp_add_inline_script(
            'zapal-js',
            "try{window.localStorage.removeItem('zapal-theme');}catch(e){}",
            'before'
        );
    }
}

add_action('wp_enqueue_scripts', 'zapal_enqueue_sources');
