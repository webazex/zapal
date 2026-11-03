<?php
/**
 * ZAPAL administrative access policy.
 *
 * Editors manage site content and classic navigation menus. Maintenance,
 * theme/plugin editors and code changes remain with the designated owner.
 */

/**
 * The maintenance account must be an administrator with this exact email.
 * Do not treat the role alone as sufficient authorization.
 */
function WBZX_Zapal_is_maintenance_admin(WP_User $user): bool
{
    return $user->exists()
        && in_array('administrator', (array)$user->roles, true)
        && strcasecmp((string)$user->user_email, 'support@latul.website') === 0;
}

/**
 * Only the classic WordPress menu editor and its own AJAX endpoints may use
 * edit_theme_options on behalf of an Editor. The full capability would also
 * grant access to unrelated customization screens.
 */
function WBZX_Zapal_is_nav_menu_request(): bool
{
    if (!is_admin()) {
        return false;
    }

    global $pagenow;

    if ($pagenow === 'nav-menus.php') {
        return true;
    }

    if ($pagenow !== 'admin-ajax.php' || !wp_doing_ajax()) {
        return false;
    }

    $action = $_REQUEST['action'] ?? '';
    if (!is_string($action)) {
        return false;
    }

    // WordPress core AJAX operations used by nav-menus.php.
    return in_array(sanitize_key(wp_unslash($action)), [
        'add-menu-item',
        'menu-get-metabox',
        'menu-locations-save',
        'menu-quick-search',
    ], true);
}

/**
 * Enforce owner-only code and maintenance capabilities server-side.
 * A hidden admin menu is not an authorization boundary.
 */
function WBZX_Zapal_admin_capabilities(array $allcaps, array $caps, array $args, WP_User $user): array
{
    if (!$user->exists() || WBZX_Zapal_is_maintenance_admin($user)) {
        return $allcaps;
    }

    $ownerOnly = [
        'edit_themes',
        'edit_plugins',
        'edit_files',
        'switch_themes',
        'install_themes',
        'upload_themes',
        'update_themes',
        'delete_themes',
        'install_plugins',
        'upload_plugins',
        'update_plugins',
        'delete_plugins',
        'activate_plugins',
    ];

    foreach ($ownerOnly as $capability) {
        $allcaps[$capability] = false;
    }

    // Grant the menu-editing capability only for the exact requested cap
    // and only inside the classic menu-management screen / AJAX handlers.
    if (
        in_array('editor', (array)$user->roles, true)
        && ($args[0] ?? '') === 'edit_theme_options'
        && WBZX_Zapal_is_nav_menu_request()
    ) {
        $allcaps['edit_theme_options'] = true;
    }

    return $allcaps;
}
add_filter('user_has_cap', 'WBZX_Zapal_admin_capabilities', 20, 4);

/**
 * Core hides Appearance > Menus from Editors because it uses the broader
 * edit_theme_options capability. Expose a direct link to the same native
 * nav-menus.php screen without granting permanent theme-management rights.
 */
function WBZX_Zapal_editor_menu_link(): void
{
    $user = wp_get_current_user();
    if (!in_array('editor', (array)$user->roles, true)) {
        return;
    }

    add_menu_page(
        __('Меню', 'zapal'),
        __('Меню', 'zapal'),
        'edit_posts',
        'nav-menus.php',
        '',
        'dashicons-menu',
        61
    );
}
add_action('admin_menu', 'WBZX_Zapal_editor_menu_link', 20);

// Updates are intentionally manual: only the designated administrator
// may approve/install plugin or theme updates in WordPress. This also
// overrides any per-plugin/theme auto-update settings in the database.
add_filter('auto_update_plugin', '__return_false', PHP_INT_MAX);
add_filter('auto_update_theme', '__return_false', PHP_INT_MAX);
