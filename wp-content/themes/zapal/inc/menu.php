<?php

add_action('after_setup_theme', 'zapal_register_menus');

function zapal_register_menus(): void
{
    register_nav_menus([
        'main' => 'Головне меню',
        'header_cta' => 'Кнопка в шапці (один пункт)',
    ]);
}

/**
 * Display at most one top-level link in the dedicated header CTA location.
 * No menu item is changed or deleted in the WordPress database.
 */
add_filter('wp_nav_menu_objects', 'WBZX_Zapal_limit_header_cta_items', 10, 2);

function WBZX_Zapal_limit_header_cta_items(array $items, $args): array
{
    if (($args->theme_location ?? '') !== 'header_cta') {
        return $items;
    }

    $topLevel = array_values(array_filter(
        $items,
        static fn($item): bool => (int)($item->menu_item_parent ?? 0) === 0
    ));

    return array_slice($topLevel, 0, 1);
}

/**
 * WordPress does not provide a clean pre-save constraint for nav menu size.
 * Alert editors if a language-specific header CTA menu contains extra items.
 */
add_action('admin_notices', 'WBZX_Zapal_header_cta_admin_notice');

function WBZX_Zapal_header_cta_admin_notice(): void
{
    $screen = get_current_screen();
    if (!$screen || $screen->id !== 'nav-menus') {
        return;
    }

    $seen = [];
    foreach ((array)get_nav_menu_locations() as $location => $menuId) {
        $location = (string)$location;
        if ($location !== 'header_cta' && !str_starts_with($location, 'header_cta___')) {
            continue;
        }

        $menuId = (int)$menuId;
        if ($menuId <= 0 || isset($seen[$menuId])) {
            continue;
        }
        $seen[$menuId] = true;

        $items = wp_get_nav_menu_items($menuId, ['post_status' => 'publish,draft']);
        if (!is_array($items) || count($items) <= 1) {
            continue;
        }

        $editUrl = add_query_arg('menu', $menuId, admin_url('nav-menus.php'));
        printf(
            '<div class="notice notice-warning"><p>%s <a href="%s">%s</a></p></div>',
            esc_html__('Меню кнопки в шапці має містити лише один пункт. На сайті буде показано тільки перший пункт верхнього рівня.', 'zapal'),
            esc_url($editUrl),
            esc_html__('Відредагувати меню', 'zapal')
        );
    }
}
