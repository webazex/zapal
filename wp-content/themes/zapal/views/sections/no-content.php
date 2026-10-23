<?php ?>
<section class="no-content" data-header="dark" aria-labelledby="no-content-title">
    <div class="no-content__inner">
        <div class="no-content__meta" aria-hidden="true">
            <span>00</span>
            <span><?= esc_html__('Немає контенту', 'zapal') ?></span>
        </div>

        <div class="no-content__content">
            <div class="no-content__kicker">ZAPAL · Professional solutions for HoReCa</div>

            <h2 class="no-content__title" id="no-content-title">
                <?= esc_html__('Контент ще', 'zapal') ?><br>
                <span><?= esc_html__('готується.', 'zapal') ?></span>
            </h2>

            <p class="no-content__text">
                <?= esc_html__('Інформація для цього розділу поки недоступна. Матеріали з’являться після оновлення контенту.', 'zapal') ?>
            </p>
        </div>

        <div class="no-content__footer" aria-hidden="true">
            <span>ZAPAL</span>
            <i></i>
            <span><?= esc_html__('Тимчасова сторінка', 'zapal') ?></span>
        </div>
    </div>
</section>
