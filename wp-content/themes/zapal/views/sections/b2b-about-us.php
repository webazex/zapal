<?php

$kicker = $args['kicker'] ?? '';
$title = $args['h2-content'] ?? '';
$content = $args['content'] ?? '';

$signatureTitle = $args['signature-title'] ?? '';
$signatureDescription = $args['signature-description'] ?? '';
$signatureLink = is_array($args['signature-link'] ?? null)
    ? $args['signature-link']
    : [];

$linkUrl = $signatureLink['url'] ?? '';
$linkTitle = $signatureLink['title'] ?? '';
$linkTarget = $signatureLink['target'] ?? '';

$hasSignature = $signatureTitle
    || $signatureDescription
    || ($linkUrl && $linkTitle);

?>
<section class="about-b2b" data-header="dark">
    <div class="shell about-block-grid">
        <div>
            <?php if ($kicker): ?>
                <div class="kicker"><?= esc_html($kicker) ?></div>
            <?php endif; ?>

            <?php WBZX_Zapal_get_blocks('h2', ['content' => $title]); ?>
        </div>

        <?php if ($content): ?>
            <div class="about-body">
                <?= wp_kses_post($content) ?>
            </div>
        <?php endif; ?>
    </div>

    <?php if ($hasSignature): ?>
        <div class="shell about-signature">
            <div>ZAPAL</div>

            <?php if ($signatureTitle): ?>
                <strong><?= esc_html($signatureTitle) ?></strong>
            <?php endif; ?>

            <?php if ($signatureDescription): ?>
                <p><?= esc_html($signatureDescription) ?></p>
            <?php endif; ?>

            <?php if ($linkUrl && $linkTitle): ?>
                <a
                    class="btn"
                    href="<?= esc_url($linkUrl) ?>"
                    <?php if ($linkTarget): ?>target="<?= esc_attr($linkTarget) ?>"<?php endif; ?>
                    <?php if ($linkTarget === '_blank'): ?>rel="noopener noreferrer"<?php endif; ?>
                ><?= esc_html($linkTitle) ?></a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</section>
