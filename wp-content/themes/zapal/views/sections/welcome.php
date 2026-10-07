<?php
$saugeLink = (get_the_permalink($args['sauge_link']))?? null;
$saugeImg = $args['img'] ?? null;
?>
<section class="hero">
    <div aria-hidden="true" class="hero-word">
        <?php echo $args['background-text']; ?>\
    </div>
    <div class="shell hero-grid">
        <div class="hero-copy">
            <div class="kicker">
                <?php echo $args['left-small-title']; ?>
            </div>
            <h1>
                <?php echo $args['title-h1']; ?>
            </h1>
            <p>
                <?php echo $args['description']; ?>
            </p>
        </div>
        <?php if(!is_null($saugeImg)): ?>
        <a aria-label="<?php _e('Worcester ZAPAL — відкрити продукт', 'zapal');?>" class="hero-product" data-tilt-product=""
           href="<?php echo $saugeLink; ?>">
            <?php if(!is_null($saugeImg)): ?>
            <img alt="<?php echo $saugeImg['alt']; ?>" class="hero-premium-packshot" height="1355"
                 src="<?php echo $saugeImg['url']; ?>" width="573"/>
            <?php endif; ?>
        </a>
        <?php endif; ?>
        <div class="hero-side">
            <div class="hero-stat">
                <?php echo $args['b2b-title']; ?>
            </div>
            <p>
                <?php echo $args['b2b-description']; ?>
            </p>
        </div>
        <div class="hero-bottom">
            <span class="scroll-cue"><i></i> <?php echo $args['left-bottom-text']; ?></span>
            <span><?php echo $args['right-bottom-text']; ?></span>
        </div>
    </div>
</section>
