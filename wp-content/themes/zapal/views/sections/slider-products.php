<?php
//$products = WBZX_Zapal_get_products();
WBZX_Zapal_get_selected_products();
?>
<section class="menu-section" id="products">
    <div class="shell section-head" data-reveal="">
        <div>
            <div class="kicker">Асортимент</div>
            <h2>Tasting<br/>menu</h2>
        </div>
        <p>
            Основна лінійка: Worcester, Carolina Gold, Pan Asian (Корейський Пан), TEXAS BBQ і SRIRACHA.
            Окремо - ELIXIR, надгострі соуси та напівфабрикати для професійної кухні.
        </p>
    </div>
    <div class="product-rail-wrap">
        <div aria-label="Продукти ZAPAL" class="product-rail" data-product-rail="">
            <?php
            if (!empty($products)):
                foreach ($products as $product):?>
            <a class="product-card" href="product-worcester.html">
                <img alt="Worcester ZAPAL — B2B фасування" class="card-bg" src="assets/img/final/central-worcester.webp"/>
                <img alt="" class="card-bg card-alt" loading="lazy" src="assets/img/recipes-final/worcester-ribeye.webp"/>
                <span class="num">01 / UNIVERSAL</span>
                <div class="card-bottom">
                    <h3>Worcester</h3>
                    <p>Легендарна глибина смаку та справжнє умамі.</p>
                    <span class="circle-link">↗</span>
                </div>
            </a>
            <?php endforeach; endif; ?>
        </div>
    </div>
    <div class="shell rail-controls">
        <div class="rail-hint">Гортайте продукти</div>
        <div aria-hidden="true" class="rail-progress"><i data-rail-progress=""></i></div>
        <div aria-label="Навігація продуктами" class="rail-buttons">
            <button aria-label="Попередні продукти" class="rail-button" data-rail-prev="" type="button">←</button>
            <button aria-label="Наступні продукти" class="rail-button" data-rail-next="" type="button">→</button>
        </div>
    </div>
</section>
