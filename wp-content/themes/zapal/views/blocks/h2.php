<?php

$content = WBZX_Zapal_heading_content($args['content'] ?? '');
$class = trim((string)($args['class'] ?? ''));
$reveal = !empty($args['reveal']);

if ($content === '') {
    return;
}
?>
<h2<?php if ($class): ?> class="<?= esc_attr($class) ?>"<?php endif; ?><?php if ($reveal): ?> data-reveal=""<?php endif; ?>><?= $content ?></h2>
