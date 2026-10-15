<?php
$title = $args['h2-content'] ?? '';
?>
<section class="manifesto" data-header="dark">
    <div class="shell manifesto-grid">
        <aside class="aside" data-reveal="">
            <div class="kicker"><?php echo $args['left-small-title']; ?></div>
            <p><?php echo $args['description']; ?></p>
        </aside>
        <div>
            <?php
            WBZX_Zapal_get_blocks('h2', [
                'content' => $title,
                'class' => 'manifesto-title',
                'reveal' => true,
            ]);
            ?>
            <div class="manifesto-foot" data-reveal="">
                <p>
                    <?php echo $args['left-bottom-text']; ?>
                </p>
                <p>
                    <?php echo $args['right-bottom-text']; ?>
                </p>
            </div>
        </div>
    </div>
</section>
