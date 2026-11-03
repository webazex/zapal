<?php
/**
 * Language-aware PUBLIC slugs for ZAPAL (Polylang Free).
 *
 * The database's post_name and term.slug remain unique. This avoids bypassing
 * WordPress's uniqueness / term lookup mechanisms. The migration sets a shared
 * public slug in postmeta/termmeta for the linked UA/RU/EN translations.
 *
 * Public permalink: /<language>/<base>/<public-slug>/
 * Incoming requests are mapped to the actual post/term ID of that language.
 * The feature is inert until post/term meta is explicitly migrated.
 */

const WBZX_ZAPAL_PUBLIC_SLUG_META = '_zapal_public_slug';

function WBZX_Zapal_shared_slugs_ready(): bool
{
    return function_exists('pll_get_post_language')
        && function_exists('pll_get_term_language')
        && function_exists('pll_default_language')
        && (bool)get_option('permalink_structure')
        && (bool)get_option('zapal_shared_slug_urls_enabled', false);
}

function WBZX_Zapal_public_slug($id, bool $term = false): string
{
    $value = $term
        ? get_term_meta((int)$id, WBZX_ZAPAL_PUBLIC_SLUG_META, true)
        : get_post_meta((int)$id, WBZX_ZAPAL_PUBLIC_SLUG_META, true);

    if (!is_string($value) || !preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/D', $value)) {
        return '';
    }

    return $value;
}

/** Replace only the final path segment, without destroying language/base/query. */
function WBZX_Zapal_replace_slug_in_url(string $url, string $currentSlug, string $publicSlug): string
{
    if ($currentSlug === '' || $publicSlug === '' || $currentSlug === $publicSlug) {
        return $url;
    }

    if (!preg_match('~^(https?://[^/?#]+)?(?P<path>/[^?#]*)(?P<query>\?[^#]*)?(?P<fragment>\#.*)?$~i', $url, $parts)) {
        return $url;
    }

    $path = $parts['path'];
    if ($path === '/' || rawurldecode(basename(rtrim($path, '/'))) !== rawurldecode($currentSlug)) {
        return $url;
    }

    $newPath = preg_replace(
        '~[^/]+/?$~',
        $publicSlug . (str_ends_with($path, '/') ? '/' : ''),
        $path,
        1
    );
    if (!is_string($newPath) || $newPath === '') {
        return $url;
    }

    return ($parts[1] ?? '') . $newPath . ($parts['query'] ?? '') . ($parts['fragment'] ?? '');
}

function WBZX_Zapal_shared_post_link(string $url, WP_Post $post): string
{
    if (!WBZX_Zapal_shared_slugs_ready() || !in_array($post->post_type, ['page', 'products', 'recipes'], true)) {
        return $url;
    }
    $slug = WBZX_Zapal_public_slug($post->ID);

    return $slug !== '' ? WBZX_Zapal_replace_slug_in_url($url, $post->post_name, $slug) : $url;
}
add_filter('page_link', static function ($url, $id) {
    $post = get_post((int)$id);
    return $post instanceof WP_Post ? WBZX_Zapal_shared_post_link((string)$url, $post) : $url;
}, 99, 2);
add_filter('post_type_link', static function ($url, $post) {
    return $post instanceof WP_Post ? WBZX_Zapal_shared_post_link((string)$url, $post) : $url;
}, 99, 2);

add_filter('term_link', static function ($url, $term, $taxonomy) {
    if (!WBZX_Zapal_shared_slugs_ready()
        || !$term instanceof WP_Term
        || !in_array($taxonomy, ['cproducts', 'ctags'], true)
    ) {
        return $url;
    }
    $slug = WBZX_Zapal_public_slug($term->term_id, true);

    return $slug !== ''
        ? WBZX_Zapal_replace_slug_in_url((string)$url, $term->slug, $slug)
        : $url;
}, 99, 3);

/**
 * Obtain language from the rewritten Polylang query, then URI prefix.
 * This supports the ZAPAL configuration: Ukrainian default without a prefix,
 * plus /ru/ and /en/. Do not use for language-by-domain without further tests.
 */
function WBZX_Zapal_request_language(array $vars): string
{
    $languages = function_exists('pll_languages_list') ? (array)pll_languages_list(['fields' => 'slug']) : [];
    $language = $vars['lang'] ?? '';
    if (is_string($language) && in_array($language, $languages, true)) {
        return $language;
    }

    $uriPath = wp_parse_url((string)($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH);
    $homePath = wp_parse_url(home_url('/'), PHP_URL_PATH);
    $uriPath = is_string($uriPath) ? $uriPath : '/';
    $homePath = is_string($homePath) ? $homePath : '/';

    if ($homePath !== '/' && str_starts_with($uriPath, $homePath)) {
        $uriPath = '/' . ltrim(substr($uriPath, strlen($homePath)), '/');
    }
    $segment = explode('/', trim($uriPath, '/'))[0] ?? '';
    if (in_array($segment, $languages, true)) {
        return $segment;
    }

    $default = pll_default_language('slug');
    return is_string($default) && in_array($default, $languages, true) ? $default : '';
}

/**
 * Only top-level page records and the two flat CPTs are migrated. Avoid
 * ambiguous nested pagename paths and private preview requests.
 */
function WBZX_Zapal_find_shared_post(string $postType, string $publicSlug, string $language): int
{
    static $cache = [];
    $key = $postType . ':' . $publicSlug . ':' . $language;
    if (array_key_exists($key, $cache)) {
        return $cache[$key];
    }

    $ids = get_posts([
        'post_type' => $postType,
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'fields' => 'ids',
        'meta_key' => WBZX_ZAPAL_PUBLIC_SLUG_META,
        'meta_value' => $publicSlug,
        'suppress_filters' => true,
        'no_found_rows' => true,
    ]);
    $matches = [];
    foreach ((array)$ids as $id) {
        $post = get_post((int)$id);
        if (!$post instanceof WP_Post || ($postType === 'page' && (int)$post->post_parent !== 0)) {
            continue;
        }
        if (pll_get_post_language((int)$id, 'slug') === $language) {
            $matches[] = (int)$id;
        }
    }

    // Never guess when two objects of one language claim the same public URL.
    return $cache[$key] = count($matches) === 1 ? $matches[0] : 0;
}

function WBZX_Zapal_find_shared_term(string $taxonomy, string $publicSlug, string $language): ?WP_Term
{
    static $cache = [];
    $key = $taxonomy . ':' . $publicSlug . ':' . $language;
    if (array_key_exists($key, $cache)) {
        return $cache[$key];
    }

    $terms = get_terms([
        'taxonomy' => $taxonomy,
        'hide_empty' => false,
        'number' => 0,
        'lang' => '',
        'meta_query' => [[
            'key' => WBZX_ZAPAL_PUBLIC_SLUG_META,
            'value' => $publicSlug,
        ]],
    ]);
    if (is_wp_error($terms) || !is_array($terms)) {
        return $cache[$key] = null;
    }

    $matches = [];
    foreach ($terms as $term) {
        if ($term instanceof WP_Term && pll_get_term_language($term->term_id, 'slug') === $language) {
            $matches[] = $term;
        }
    }

    return $cache[$key] = count($matches) === 1 ? $matches[0] : null;
}

function WBZX_Zapal_resolve_shared_slug(array $vars): array
{
    if (is_admin() || !WBZX_Zapal_shared_slugs_ready() || is_preview()) {
        return $vars;
    }

    $language = WBZX_Zapal_request_language($vars);
    if ($language === '') {
        return $vars;
    }

    // The language is explicit after alias resolution, avoiding ambiguous 404s.
    if (isset($vars['pagename']) && is_string($vars['pagename'])) {
        $pagePath = trim($vars['pagename'], '/');
        if (str_starts_with($pagePath, $language . '/')) {
            $pagePath = substr($pagePath, strlen($language) + 1);
        }
        if ($pagePath !== '' && !str_contains($pagePath, '/')) {
            $id = WBZX_Zapal_find_shared_post('page', rawurldecode($pagePath), $language);
            if ($id) {
                unset($vars['pagename'], $vars['name']);
                $vars['page_id'] = $id;
                $vars['lang'] = $language;
                return $vars;
            }
        }
    }

    foreach (['products', 'recipes'] as $type) {
        $raw = $vars[$type] ?? ((($vars['post_type'] ?? '') === $type) ? ($vars['name'] ?? null) : null);
        if (is_string($raw) && $raw !== '') {
            $id = WBZX_Zapal_find_shared_post($type, rawurldecode(basename(trim($raw, '/'))), $language);
            if ($id) {
                unset($vars[$type], $vars['name'], $vars['pagename']);
                $vars['post_type'] = $type;
                $vars['p'] = $id;
                $vars['lang'] = $language;
                return $vars;
            }
        }
    }

    foreach (['cproducts', 'ctags'] as $taxonomy) {
        $raw = $vars[$taxonomy] ?? ((($vars['taxonomy'] ?? '') === $taxonomy) ? ($vars['term'] ?? null) : null);
        if (!is_string($raw) || $raw === '') {
            continue;
        }
        $term = WBZX_Zapal_find_shared_term($taxonomy, rawurldecode(basename(trim($raw, '/'))), $language);
        if ($term) {
            // WP/Polylang still see their native, unique term.slug internally.
            $vars[$taxonomy] = $term->slug;
            if (($vars['taxonomy'] ?? '') === $taxonomy) {
                $vars['term'] = $term->slug;
            }
            $vars['lang'] = $language;
            return $vars;
        }
    }

    return $vars;
}
add_filter('request', 'WBZX_Zapal_resolve_shared_slug', 99, 1);

/**
 * Future translated pages/products/recipes: copy an established public slug.
 * If a whole translation group is new, use its clean Ukrainian slug. Do not
 * guess for technical -2/-ru endings or overwrite an established public URL.
 * Polylang 3.9 calls pll_save_post after assigning language/translations.
 */
add_action('pll_save_post', static function ($postId, $post, $translations) {
    if (!WBZX_Zapal_shared_slugs_ready() || !$post instanceof WP_Post
        || !in_array($post->post_type, ['page', 'products', 'recipes'], true)
        || !is_array($translations) || empty($translations['uk'])
    ) {
        return;
    }
    $ukPost = get_post((int)$translations['uk']);
    if (!$ukPost instanceof WP_Post || $ukPost->post_type !== $post->post_type
        || $ukPost->post_status !== 'publish'
        || ($ukPost->post_type === 'page' && (int)$ukPost->post_parent !== 0)
    ) {
        return;
    }
    $aliases = [];
    foreach ($translations as $id) {
        $alias = WBZX_Zapal_public_slug((int)$id);
        if ($alias !== '') {
            $aliases[$alias] = true;
        }
    }
    if (count($aliases) > 1) {
        return; // Inconsistent group needs human review.
    }
    $canonical = $aliases ? (string)array_key_first($aliases) : rawurldecode($ukPost->post_name);
    if (!$aliases && (!preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/D', $canonical)
        || preg_match('/-(?:uk|ua|ukr|ru|en|[2-9])$/', $canonical))
    ) {
        return; // Never strip potentially meaningful suffixes for new groups.
    }
    foreach ($translations as $lang => $id) {
        if (!in_array($lang, ['uk', 'ru', 'en'], true) || !get_post((int)$id) instanceof WP_Post) {
            continue;
        }
        $claim = WBZX_Zapal_find_shared_post($post->post_type, $canonical, $lang);
        if ($claim && $claim !== (int)$id) {
            return; // URL already belongs to another group of this language.
        }
    }
    foreach ($translations as $lang => $id) {
        if (in_array($lang, ['uk', 'ru', 'en'], true) && WBZX_Zapal_public_slug((int)$id) === '') {
            update_post_meta((int)$id, WBZX_ZAPAL_PUBLIC_SLUG_META, $canonical);
        }
    }
}, 20, 3);

/** Yoast must advertise the same canonical URL as our public permalink. */
add_filter('wpseo_canonical', static function ($canonical) {
    if (!WBZX_Zapal_shared_slugs_ready()) {
        return $canonical;
    }
    if (is_singular(['page', 'products', 'recipes'])) {
        $id = get_queried_object_id();
        if ($id && WBZX_Zapal_public_slug($id)
            && !get_post_meta($id, '_yoast_wpseo_canonical', true)
        ) {
            return get_permalink($id);
        }
    }
    if (is_tax(['cproducts', 'ctags'])) {
        $term = get_queried_object();
        if ($term instanceof WP_Term && WBZX_Zapal_public_slug($term->term_id, true)) {
            $url = get_term_link($term);
            return is_wp_error($url) ? $canonical : $url;
        }
    }
    return $canonical;
}, 99, 1);

// Yoast's sitemap entries may otherwise reuse cached pre-migration indexable URLs.
add_filter('wpseo_sitemap_entry', static function ($entry, $type, $object) {
    if (!WBZX_Zapal_shared_slugs_ready() || !is_array($entry)) {
        return $entry;
    }
    if ($object instanceof WP_Post && WBZX_Zapal_public_slug($object->ID)) {
        $entry['loc'] = get_permalink($object);
    } elseif ($object instanceof WP_Term && WBZX_Zapal_public_slug($object->term_id, true)) {
        $url = get_term_link($object);
        if (!is_wp_error($url)) {
            $entry['loc'] = $url;
        }
    }
    return $entry;
}, 99, 3);

/** Do not let WP's canonical redirect undo an intentional public alias. */
add_filter('redirect_canonical', static function ($redirect, $requested) {
    if (!WBZX_Zapal_shared_slugs_ready()
        || !is_singular(['page', 'products', 'recipes']) && !is_tax(['cproducts', 'ctags'])
    ) {
        return $redirect;
    }
    $expected = '';
    if (is_singular(['page', 'products', 'recipes'])) {
        $id = get_queried_object_id();
        if ($id && WBZX_Zapal_public_slug($id)) {
            $expected = get_permalink($id);
        }
    } else {
        $term = get_queried_object();
        if ($term instanceof WP_Term && WBZX_Zapal_public_slug($term->term_id, true)) {
            $url = get_term_link($term);
            $expected = is_wp_error($url) ? '' : $url;
        }
    }
    if ($expected !== '') {
        $actualPath = wp_parse_url((string)$requested, PHP_URL_PATH);
        $expectedPath = wp_parse_url($expected, PHP_URL_PATH);
        if (is_string($actualPath) && is_string($expectedPath)
            && untrailingslashit($actualPath) === untrailingslashit($expectedPath)
        ) {
            return false; // Already at the intended language-aware public path.
        }
    }
    return $redirect;
}, 99, 2);

/** Redirect now-deleted, unlinked legacy tags to their translated replacements. */
add_action('template_redirect', static function () {
    if (!WBZX_Zapal_shared_slugs_ready() || !is_404() || !empty($_GET)
        || !in_array($_SERVER['REQUEST_METHOD'] ?? '', ['GET', 'HEAD'], true)
    ) {
        return;
    }
    $path = wp_parse_url((string)($_SERVER['REQUEST_URI'] ?? ''), PHP_URL_PATH);
    if (!is_string($path)) {
        return;
    }
    $segments = explode('/', trim($path, '/'));
    if (count($segments) < 2 || $segments[count($segments) - 2] !== 'ctags') {
        return;
    }
    $language = WBZX_Zapal_request_language([]);
    $legacySlug = rawurldecode((string)end($segments));
    $redirects = get_option('zapal_legacy_term_redirects', []);
    $key = 'ctags:' . $legacySlug . ':' . $language;
    $id = is_array($redirects) ? (int)($redirects[$key] ?? 0) : 0;
    if (!$id || !WBZX_Zapal_public_slug($id, true)) {
        return;
    }
    $term = get_term($id, 'ctags');
    if (!$term instanceof WP_Term || pll_get_term_language($id, 'slug') !== $language) {
        return;
    }
    $url = get_term_link($term);
    if (!is_wp_error($url) && is_string($url) && $url !== '') {
        wp_safe_redirect($url, 301, 'ZAPAL');
        exit;
    }
}, 5);

/** Guarded 301 for legacy URLs which still resolve to an existing object. */
add_action('template_redirect', static function () {
    if (!WBZX_Zapal_shared_slugs_ready()
        || is_admin() || is_preview() || is_feed() || is_paged()
        || !in_array($_SERVER['REQUEST_METHOD'] ?? '', ['GET', 'HEAD'], true)
        || !empty($_GET)
    ) {
        return;
    }

    $canonical = '';
    if (is_singular(['page', 'products', 'recipes'])) {
        $id = get_queried_object_id();
        if ($id && WBZX_Zapal_public_slug($id)) {
            $canonical = get_permalink($id);
        }
    } elseif (is_tax(['cproducts', 'ctags'])) {
        $term = get_queried_object();
        if ($term instanceof WP_Term && WBZX_Zapal_public_slug($term->term_id, true)) {
            $link = get_term_link($term);
            $canonical = is_wp_error($link) ? '' : $link;
        }
    }
    if ($canonical === '') {
        return;
    }

    $requested = wp_parse_url((string)($_SERVER['REQUEST_URI'] ?? ''), PHP_URL_PATH);
    $expected = wp_parse_url($canonical, PHP_URL_PATH);
    if (is_string($requested) && is_string($expected)
        && rawurldecode(untrailingslashit($requested)) !== rawurldecode(untrailingslashit($expected))
    ) {
        wp_safe_redirect($canonical, 301, 'ZAPAL');
        exit;
    }
}, 15);
