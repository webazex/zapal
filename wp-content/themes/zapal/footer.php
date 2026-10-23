<footer class="site-footer">
    <div class="shell site-footer-inner">
        <span>© <?= esc_html(wp_date('Y')) ?> ZAPAL</span>

        <?php if (has_nav_menu('footer')): ?>
            <nav class="site-footer-nav" aria-label="<?= esc_attr__('Навігація футера', 'zapal') ?>">
                <?php
                wp_nav_menu([
                    'theme_location' => 'footer',
                    'container' => false,
                    'menu_class' => 'site-footer-menu',
                    'fallback_cb' => false,
                    'depth' => 1,
                    'items_wrap' => '<ul class="%2$s">%3$s</ul>',
                ]);
                ?>
            </nav>
        <?php endif; ?>

        <a class="site-credit"
           href="https://latul.website/"
           rel="noopener"
           target="_blank">
            Design &amp; development <strong>ANTON LATUL</strong><i>↗</i>
        </a>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
