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


/**
 * Keep the stored slug when the term editor resubmits an unchanged slug.
 * WordPress passes the whole edit form through wp_update_term(), including
 * the old slug; global sanitize_title can transliterate it on that save.
 * A deliberately changed slug remains editable.
 */
function WBZX_Zapal_preserve_term_slug_on_rename(array $data, int $termId, string $taxonomy, array $args): array
{
    if (!in_array($taxonomy, ['ctags', 'cproducts', 'category', 'post_tag'], true)
        || !isset($data['slug']) || !is_string($data['slug'])) {
        return $data;
    }

    $existing = get_term($termId, $taxonomy);
    if (!$existing instanceof WP_Term || $data['slug'] === $existing->slug) {
        return $data;
    }

    // The WordPress term edit form submits the slug even when only the label
    // was edited. Compare the unsanitized input with the actual stored slug.
    if (isset($_POST['tag_ID'], $_POST['slug'])
        && (int)$_POST['tag_ID'] === $termId && is_string($_POST['slug'])) {
        $submitted = (string)wp_unslash($_POST['slug']);
        if ($submitted === $existing->slug
            || rawurldecode($submitted) === rawurldecode($existing->slug)) {
            $data['slug'] = $existing->slug;
        }
        // Different explicit slug: leave the user's requested change intact.
        return $data;
    }

    // API / programmatic name-only edits can also re-sanitize the original
    // slug; only undo the exact mechanical transformation of the old value.
    // An explicit change to any other slug is unaffected.
    if ($data['slug'] === sanitize_title($existing->slug)) {
        $data['slug'] = $existing->slug;
    }
    return $data;
}
add_filter('wp_update_term_data', 'WBZX_Zapal_preserve_term_slug_on_rename', 20, 4);
