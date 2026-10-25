<?php

$title = trim((string)($args['h2-content'] ?? ''));
$kicker = trim((string)($args['left-small-title'] ?? ''));
$description = trim((string)($args['description'] ?? ''));
$leftText = trim((string)($args['left-bottom-text'] ?? ''));
$rightText = trim((string)($args['right-bottom-text'] ?? ''));

if ($title === '' && $kicker === '' && $description === '' && $leftText === '' && $rightText === '') {
    return;
}

?>
<section class="manifesto" data-header="dark">
    <div class="shell manifesto-grid">
        <?php if ($kicker !== '' || $description !== ''): ?>
            <aside class="aside" data-reveal="">
                <?php if ($kicker !== ''): ?>
                    <div class="kicker"><?= esc_html($kicker) ?></div>
                <?php endif; ?>

                <?php if ($description !== ''): ?>
                    <p><?= nl2br(esc_html($description)) ?></p>
                <?php endif; ?>
            </aside>
        <?php endif; ?>

        <div>
            <?php
            WBZX_Zapal_get_blocks('h2', [
                'content' => $title,
                'class' => 'manifesto-title',
                'reveal' => true,
            ]);
            ?>

            <?php if ($leftText !== '' || $rightText !== ''): ?>
                <div class="manifesto-foot" data-reveal="">
                    <?php if ($leftText !== ''): ?>
                        <p><?= nl2br(esc_html($leftText)) ?></p>
                    <?php endif; ?>

                    <?php if ($rightText !== ''): ?>
                        <p><?= nl2br(esc_html($rightText)) ?></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
