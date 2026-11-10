/**
 * Set the saved theme before body rendering; default stays dark.
 * Loaded in the document head only while the ACF theme switch is enabled.
 */
(function () {
    var root = document.documentElement;
    var themeColor = document.querySelector('meta[name="theme-color"]');

    function syncThemeColor() {
        if (themeColor) {
            themeColor.content = root.getAttribute('data-theme') === 'dark'
                ? '#0f0e0d'
                : '#f4f0e7';
        }
    }

    try {
        var storedTheme = window.localStorage.getItem('zapal-theme');
        if (storedTheme === 'light') {
            root.setAttribute('data-theme', 'light');
        } else if (storedTheme !== 'dark') {
            window.localStorage.setItem('zapal-theme', 'dark');
        }
    } catch (error) {
        // A blocked storage API must not interrupt page rendering.
    }

    syncThemeColor();
    if ('MutationObserver' in window) {
        new MutationObserver(syncThemeColor).observe(root, {
            attributes: true,
            attributeFilter: ['data-theme']
        });
    }
}());
