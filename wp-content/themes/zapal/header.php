<?php
$homeUrl = WBZX_Zapal_home_url();
$catalogUrl = WBZX_Zapal_catalog_url();
$navLabels = WBZX_Zapal_nav_labels();
$hasMenu = has_nav_menu('main');
$languages = function_exists('pll_the_languages')
    ? pll_the_languages([
        'raw' => 1,
        'hide_if_empty' => 0,
        'hide_if_no_translation' => 0,
    ])
    : [];
$languages = is_array($languages) ? $languages : [];

$darkmodeEnabled = function_exists('get_field')
    ? (bool)get_field('darkmode', 'option')
    : false;
?>
<!DOCTYPE html>
<html data-theme="light"
      data-darkmode="<?= $darkmodeEnabled ? '1' : '0' ?>"
      <?php language_attributes(); ?>>
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
           href="<?= esc_url($homeUrl) ?>">
            <img alt=""
                 class="logo-mark"
                 src="<?= esc_url(WBZX_ZAPAL_ASSET_DIR_URI . 'img/logo.svg') ?>"/>
            <span>ZAPAL</span>
        </a>

        <?php if ($hasMenu): ?>
            <nav aria-label="<?= esc_attr($navLabels['navigation']) ?>"
                 class="nav"
                 id="zapal-primary-nav">
                <?php
                wp_nav_menu([
                    'theme_location' => 'main',
                    'container' => false,
                    'menu_class' => 'zapal-menu',
                    'fallback_cb' => false,
                    'depth' => 1,
                    'items_wrap' => '<ul class="%2$s">%3$s</ul>',
                ]);
                ?>
            </nav>
        <?php endif; ?>

        <?php if (count($languages) > 1): ?>
            <div class="zapal-languages"
                 role="group"
                 aria-label="<?= esc_attr($navLabels['language']) ?>">
                <?php foreach ($languages as $language): ?>
                    <?php
                    $slug = strtolower((string)($language['slug'] ?? ''));
                    $url = (string)($language['url'] ?? '');
                    if ($slug === '' || $url === '') {
                        continue;
                    }
                    $isCurrent = !empty($language['current_lang']);
                    ?>
                    <a href="<?= esc_url($url) ?>"
                       lang="<?= esc_attr($slug) ?>"
                       hreflang="<?= esc_attr($slug) ?>"
                       aria-label="<?= esc_attr((string)($language['name'] ?? strtoupper($slug))) ?>"
                       <?= $isCurrent ? 'aria-current="page"' : '' ?>>
                        <?= esc_html(strtoupper($slug === 'uk' ? 'UA' : $slug)) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($darkmodeEnabled): ?>
            <button aria-label="<?= esc_attr__('Увімкнути темну тему', 'zapal') ?>"
                    aria-pressed="false"
                    class="theme-toggle"
                    data-theme-toggle=""
                    title="<?= esc_attr__('Темна тема', 'zapal') ?>"
                    type="button">
                <span aria-hidden="true" class="theme-toggle-icon"></span>
            </button>
        <?php endif; ?>

        <?php if ($hasMenu): ?>
            <button aria-label="<?= esc_attr__('Меню', 'zapal') ?>"
                    aria-controls="zapal-primary-nav"
                    aria-expanded="false"
                    class="menu-toggle"
                    type="button">
                <i></i>
            </button>
        <?php endif; ?>
    </div>
</header>
