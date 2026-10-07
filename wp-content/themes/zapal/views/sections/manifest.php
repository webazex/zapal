<?php
$titleSetting = (!empty($args['h2-content'])) ? $args['h2-content'] : false;
$isColored = $titleSetting !== false && boolval($titleSetting['color-status']);
?>
<section class="manifesto" data-header="dark">
    <div class="shell manifesto-grid">
        <aside class="aside" data-reveal="">
            <div class="kicker"><?php echo $args['left-small-title']; ?></div>
            <p><?php echo $args['description']; ?></p>
        </aside>
        <div>
            <?php if($titleSetting !== false): ?>
            <h2 class="manifesto-title" data-reveal="">
                <?php echo $titleSetting['h2-title']; ?>
                <?php if($isColored): ?>
                    <span class="serif" style="color: <?php echo $titleSetting['color']; ?>">
                        <?php echo $titleSetting['colored-text']; ?>
                    </span>
                <?php endif; ?>
            </h2>
            <?php endif; ?>
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