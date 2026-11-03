<?php
/**
 * Cyrillic -> Latin for NEW WordPress slugs and NEW uploaded file names.
 * Existing URLs and existing media are never modified on theme activation.
 * This intentionally implements ZAPAL's UA/RU needs, not the whole Cyr-To-Lat UI.
 */

function WBZX_Zapal_transliteration_language(): string
{
    $language = function_exists('pll_current_language') ? pll_current_language('slug') : '';
    if (!is_string($language) || !in_array($language, ['uk', 'ru'], true)) {
        $locale = function_exists('determine_locale') ? determine_locale() : get_locale();
        $language = str_starts_with(strtolower((string)$locale), 'ru') ? 'ru' : 'uk';
    }

    return (string)apply_filters('wbzx_zapal_transliteration_language', $language);
}

function WBZX_Zapal_transliterate(string $value, string $language = ''): string
{
    if (!preg_match('/[а-яёіїєґыэъʹʼ]/iu', $value)) {
        return $value;
    }

    if ($language === '') {
        $language = WBZX_Zapal_transliteration_language();
    }

    $ua = [
        'а'=>'a', 'б'=>'b', 'в'=>'v', 'г'=>'h', 'ґ'=>'g', 'д'=>'d',
        'е'=>'e', 'є'=>'ye', 'ж'=>'zh', 'з'=>'z', 'и'=>'y', 'і'=>'i',
        'ї'=>'yi', 'й'=>'y', 'к'=>'k', 'л'=>'l', 'м'=>'m', 'н'=>'n',
        'о'=>'o', 'п'=>'p', 'р'=>'r', 'с'=>'s', 'т'=>'t', 'у'=>'u',
        'ф'=>'f', 'х'=>'kh', 'ц'=>'ts', 'ч'=>'ch', 'ш'=>'sh', 'щ'=>'shch',
        'ь'=>'', 'ю'=>'yu', 'я'=>'ya', 'ы'=>'y', 'э'=>'e', 'ё'=>'yo', 'ъ'=>'',
    ];
    $ru = [
        'г'=>'g', 'и'=>'i', 'й'=>'y', 'ы'=>'y', 'э'=>'e', 'ё'=>'yo',
        'е'=>'e', 'є'=>'ye', 'і'=>'i', 'ї'=>'yi', 'ґ'=>'g',
    ];
    $lower = $language === 'ru' ? array_replace($ua, $ru) : $ua;
    $upper = [];
    // Matching alphabet lists avoid relying on mbstring for case conversion.
    $lo = preg_split('//u', 'абвгґдеєжзиіїйклмнопрстуфхцчшщьюяыэёъ', -1, PREG_SPLIT_NO_EMPTY);
    $hi = preg_split('//u', 'АБВГҐДЕЄЖЗИІЇЙКЛМНОПРСТУФХЦЧШЩЬЮЯЫЭЁЪ', -1, PREG_SPLIT_NO_EMPTY);
    foreach ($lo as $i => $letter) {
        if (isset($lower[$letter], $hi[$i])) {
            $upper[$hi[$i]] = ucfirst($lower[$letter]);
        }
    }

    return strtr($value, $upper + $lower + ['ʹ'=>'', 'ʼ'=>'', '’'=>'', '‘'=>'']);
}

function WBZX_Zapal_transliterate_title(string $title, string $rawTitle = '', string $context = 'save'): string
{
    return $context === 'save' ? WBZX_Zapal_transliterate($title) : $title;
}
add_filter('sanitize_title', 'WBZX_Zapal_transliterate_title', 9, 3);

function WBZX_Zapal_transliterate_filename(string $filename): string
{
    return WBZX_Zapal_transliterate($filename);
}
add_filter('sanitize_file_name', 'WBZX_Zapal_transliterate_filename', 9, 1);
