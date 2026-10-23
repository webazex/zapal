<?php

$content = $args['content'] ?? '';
if (!is_string($content) || trim($content) === '') {
    return;
}

$title = trim((string)get_the_title(get_queried_object_id()));
$type = ($args['type'] ?? '') === 'terms-conditions' ? 'terms-conditions' : 'privacy-policy';

?>
<section class="legal-document legal-document--<?= esc_attr($type) ?>">
    <div class="shell legal-document-shell">
        <?php if ($title !== ''): ?>
            <header class="legal-document-header">
                <h1><?= esc_html($title) ?></h1>
            </header>
        <?php endif; ?>

        <div class="legal-document-body">
            <?= wp_kses_post($content) ?>
        </div>
    </div>
</section>
