<?php

$items = is_array($args['items'] ?? null) ? $args['items'] : [];
$class = trim((string)($args['class'] ?? ''));

if (!$items) {
    return;
}

?>
<nav aria-label="<?= esc_attr__('Хлібні крихти', 'zapal') ?>"
     <?php if ($class): ?>class="<?= esc_attr($class) ?>"<?php endif; ?>>
    <?php foreach ($items as $index => $item): ?>
        <?php
        $title = trim((string)($item['title'] ?? ''));
        $url = trim((string)($item['url'] ?? ''));

        if ($title === '') {
            continue;
        }
        ?>

        <?php if ($index > 0): ?>
            <span aria-hidden="true">/</span>
        <?php endif; ?>

        <?php if ($url): ?>
            <a href="<?= esc_url($url) ?>"><?= esc_html($title) ?></a>
        <?php else: ?>
            <strong aria-current="page"><?= esc_html($title) ?></strong>
        <?php endif; ?>
    <?php endforeach; ?>
</nav>
