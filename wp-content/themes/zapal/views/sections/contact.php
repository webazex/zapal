<?php

$title = $args['h2-content'] ?? '';
$kicker = $args['kicker'] ?? '';
$description = $args['descr'] ?? '';
$textUnderForm = $args['text-under-form'] ?? '';
$submitText = $args['btn-submit-txt'] ?? '';
$selectedProductId = absint($args['selected_product_id'] ?? 0);
$products = WBZX_Zapal_get_products([
    'posts_per_page' => -1,
    'orderby' => [
        'menu_order' => 'ASC',
        'title' => 'ASC',
    ],
]);

?>
<section class="contact" data-header="dark" id="contact">
    <div class="shell contact-grid">
        <div data-reveal="">
            <?php if ($kicker): ?>
                <div class="kicker"><?= esc_html($kicker) ?></div>
            <?php endif; ?>

            <?php WBZX_Zapal_get_blocks('h2', ['content' => $title]); ?>

            <?php if ($description): ?>
                <p class="contact-note"><?= esc_html($description) ?></p>
            <?php endif; ?>
        </div>

        <form class="form"
              data-recipient="zapalsauce@gmail.com"
              data-reveal=""
              method="post"
              novalidate="novalidate">
            <div class="field">
                <label for="product"><?= esc_html__('Продукт', 'zapal') ?></label>
                <select id="product" name="product">
                    <?php foreach ($products as $product): ?>
                        <option value="<?= esc_attr($product->ID) ?>"
                            <?= selected($selectedProductId, (int)$product->ID, false) ?>>
                            <?= esc_html($product->post_title) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="field">
                <label for="name"><?= esc_html__("Ім’я", 'zapal') ?></label>
                <input autocomplete="name"
                       id="name"
                       name="name"
                       placeholder="<?= esc_attr__('Ваше ім’я', 'zapal') ?>"
                       required=""/>
            </div>

            <div class="field">
                <label for="phone"><?= esc_html__('Телефон', 'zapal') ?></label>
                <input autocomplete="tel"
                       id="phone"
                       name="phone"
                       placeholder="+380"
                       required=""/>
            </div>

            <div class="field">
                <label for="email">Email</label>
                <input autocomplete="email"
                       id="email"
                       name="email"
                       placeholder="<?= esc_attr__('Необов’язково', 'zapal') ?>"
                       type="email"/>
            </div>

            <div class="field">
                <label for="message"><?= esc_html__('Питання', 'zapal') ?></label>
                <textarea id="message"
                          name="message"
                          placeholder="<?= esc_attr__('Розкажіть, що вас цікавить', 'zapal') ?>"
                          required=""></textarea>
            </div>

            <div class="submit-row">
                <?php if ($textUnderForm): ?>
                    <small><?= esc_html($textUnderForm) ?></small>
                <?php endif; ?>

                <button class="btn" type="submit">
                    <?= esc_html($submitText ?: __('Задати питання', 'zapal')) ?> ↗
                </button>
            </div>
        </form>
    </div>
</section>
