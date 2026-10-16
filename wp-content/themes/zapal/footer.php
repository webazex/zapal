<footer class="site-footer">
    <div class="shell site-footer-inner">
        <span>© <?= esc_html(wp_date('Y')) ?> ZAPAL</span>

        <?php $privacyUrl = get_privacy_policy_url(); ?>
        <?php if ($privacyUrl): ?>
            <a href="<?= esc_url($privacyUrl) ?>">
                <?= esc_html__('Політика конфіденційності', 'zapal') ?>
            </a>
        <?php endif; ?>

        <span>Professional Solutions for HoReCa</span>

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
