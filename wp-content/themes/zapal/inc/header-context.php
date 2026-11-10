<?php
/**
 * Prepare header-only data outside the presentation template.
 * Keep the active Polylang language explicit in <html lang>.
 */
function WBZX_Zapal_header_context(): array
{
    $languages = function_exists('pll_the_languages')
        ? pll_the_languages([
            'raw' => 1,
            'hide_if_empty' => 0,
            'hide_if_no_translation' => 0,
        ])
        : [];

    $currentLanguage = function_exists('pll_current_language')
        ? pll_current_language('slug')
        : '';
    if (!is_string($currentLanguage) || $currentLanguage === '') {
        $currentLanguage = (string)get_bloginfo('language');
    }

    return [
        'homeUrl' => WBZX_Zapal_home_url(),
        'navLabels' => WBZX_Zapal_nav_labels(),
        'hasMenu' => has_nav_menu('main'),
        'hasCta' => has_nav_menu('header_cta'),
        'languages' => is_array($languages) ? $languages : [],
        'darkmodeEnabled' => function_exists('get_field')
            ? (bool)get_field('darkmode', 'option')
            : false,
        'htmlLang' => str_replace('_', '-', $currentLanguage),
    ];
}
