<?php

$productData = is_array($args['data'] ?? null) ? $args['data'] : [];
$techInfo = is_array($productData['tech-info'] ?? null)
    ? $productData['tech-info']
    : [];

$title = $techInfo['h2-content'] ?? '';
$description = trim((string)($techInfo['description'] ?? ''));
$link = is_array($techInfo['link'] ?? null)
    ? $techInfo['link']
    : [];
$techData = is_array($techInfo['tech-data'] ?? null)
    ? $techInfo['tech-data']
    : [];

$linkUrl = trim((string)($link['url'] ?? ''));
$linkTitle = trim((string)($link['title'] ?? ''));
$linkTarget = trim((string)($link['target'] ?? ''));

$stripPrefix = static function (string $value, string $prefix): string {
    $value = trim($value);

    if ($value === '') {
        return '';
    }

    // Editable specifications may already include labels in UA/RU/EN.
    // Strip any of these before rendering the localized heading below.
    $knownPrefixes = [
        'Склад:' => ['Склад:', 'Состав:', 'Ingredients:'],
        'Алергени:' => ['Алергени:', 'Аллергены:', 'Allergens:'],
        'Умови зберігання:' => ['Умови зберігання:', 'Условия хранения:', 'Storage conditions:'],
        'Термін придатності:' => ['Термін придатності:', 'Срок годности:', 'Shelf life:'],
    ];
    $variants = $knownPrefixes[$prefix] ?? [$prefix];
    $patterns = array_map(static fn(string $variant): string => preg_quote($variant, '/'), $variants);
    $pattern = '/^(?:' . implode('|', $patterns) . ')\s*/ui';

    return trim((string)preg_replace($pattern, '', $value, 1));
};

$formatGrams = static function (string $value): string {
    $value = trim($value);

    if ($value === '') {
        return '';
    }

    if (preg_match('/^\d+(?:[.,]\d+)?$/u', $value)) {
        return $value . ' ' . __('г', 'zapal');
    }

    return $value;
};

if (!$title && $description === '' && !$linkUrl && !$techData) {
    return;
}

?>
<section class="product-spec" data-header="dark" id="specification">
    <div class="shell product-spec-grid">
        <div class="product-spec-intro" data-reveal="">
            <div class="kicker">
                <?= esc_html__('Інтерактивна специфікація', 'zapal') ?>
            </div>

            <?php WBZX_Zapal_get_blocks('h2', ['content' => $title]); ?>

            <?php if ($description !== ''): ?>
                <p><?= esc_html($description) ?></p>
            <?php endif; ?>

            <?php if ($linkUrl && $linkTitle): ?>
                <a
                    class="btn spec-request-btn"
                    data-spec-request=""
                    href="<?= esc_url($linkUrl) ?>"
                    <?php if ($linkTarget): ?>target="<?= esc_attr($linkTarget) ?>"<?php endif; ?>
                    <?php if ($linkTarget === '_blank'): ?>rel="noopener noreferrer"<?php endif; ?>
                ><?= esc_html($linkTitle) ?></a>
            <?php endif; ?>
        </div>

        <?php if ($techData): ?>
            <div class="product-spec-list" data-reveal="">
                <?php $visibleIndex = 0; ?>

                <?php foreach ($techData as $row): ?>
                    <?php
                    if (!is_array($row)) {
                        continue;
                    }

                    $layout = $row['acf_fc_layout'] ?? '';
                    $summary = '';
                    $content = '';

                    ob_start();

                    switch ($layout) {
                        case 'ingridients-and-alergens':
                            $summary = __('Склад та алергени', 'zapal');

                            $ingredients = $stripPrefix(
                                (string)($row['ingridients'] ?? ''),
                                'Склад:'
                            );
                            $allergens = $stripPrefix(
                                (string)($row['alergens'] ?? ''),
                                'Алергени:'
                            );
                            ?>
                            <?php if ($ingredients !== ''): ?>
                                <p>
                                    <strong><?= esc_html__('Склад:', 'zapal') ?></strong>
                                    <?= esc_html($ingredients) ?>
                                </p>
                            <?php endif; ?>

                            <?php if ($allergens !== ''): ?>
                                <p>
                                    <strong><?= esc_html__('Алергени:', 'zapal') ?></strong>
                                    <?= esc_html($allergens) ?>
                                </p>
                            <?php endif; ?>
                            <?php
                            break;

                        case 'nutritional-value':
                            $summary = __('Харчова цінність', 'zapal');

                            $grammage = $formatGrams((string)($row['gr'] ?? ''));
                            $table = is_array($row['nutritional-table'] ?? null)
                                ? $row['nutritional-table']
                                : [];
                            ?>
                            <div class="product-spec-nutrition">
                                <?php if ($grammage !== ''): ?>
                                    <p>
                                        <strong>
                                            <?= esc_html(sprintf(
                                                __('На %s продукту:', 'zapal'),
                                                $grammage
                                            )) ?>
                                        </strong>
                                    </p>
                                <?php endif; ?>

                                <?php if ($table): ?>
                                    <dl>
                                        <?php foreach ($table as $item): ?>
                                            <?php
                                            $key = trim((string)($item['key'] ?? ''));
                                            $value = $formatGrams(
                                                (string)($item['val'] ?? '')
                                            );

                                            if ($key === '' && $value === '') {
                                                continue;
                                            }
                                            ?>
                                            <div>
                                                <?php if ($key !== ''): ?>
                                                    <dt><?= esc_html($key) ?></dt>
                                                <?php endif; ?>

                                                <?php if ($value !== ''): ?>
                                                    <dd><?= esc_html($value) ?></dd>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </dl>
                                <?php endif; ?>
                            </div>
                            <?php
                            break;

                        case 'energy-value':
                            $summary = __('Енергетична цінність', 'zapal');

                            $grammage = $formatGrams((string)($row['gr'] ?? ''));
                            $value = trim((string)($row['value'] ?? ''));
                            ?>
                            <?php if ($grammage !== '' || $value !== ''): ?>
                                <p>
                                    <?php if ($grammage !== ''): ?>
                                        <strong>
                                            <?= esc_html(sprintf(
                                                __('На %s:', 'zapal'),
                                                $grammage
                                            )) ?>
                                        </strong>
                                    <?php endif; ?>

                                    <?= esc_html($value) ?>
                                </p>
                            <?php endif; ?>
                            <?php
                            break;

                        case 'use-cases':
                            $summary = __('Сфера застосування в HoReCa', 'zapal');

                            $useText = trim((string)($row['use-text'] ?? ''));
                            ?>
                            <?php if ($useText !== ''): ?>
                                <?= wpautop(esc_html($useText)) ?>
                            <?php endif; ?>
                            <?php
                            break;

                        case 'storage-and-shelf-life':
                            $summary = __('Зберігання та строк придатності', 'zapal');

                            $storages = $stripPrefix(
                                (string)($row['storages'] ?? ''),
                                'Умови зберігання:'
                            );
                            $shelfLife = $stripPrefix(
                                (string)($row['shelf-life'] ?? ''),
                                'Термін придатності:'
                            );
                            ?>
                            <?php if ($storages !== ''): ?>
                                <p>
                                    <strong>
                                        <?= esc_html__('Умови зберігання:', 'zapal') ?>
                                    </strong>
                                    <?= esc_html($storages) ?>
                                </p>
                            <?php endif; ?>

                            <?php if ($shelfLife !== ''): ?>
                                <p>
                                    <strong>
                                        <?= esc_html__('Строк придатності:', 'zapal') ?>
                                    </strong>
                                    <?= esc_html($shelfLife) ?>
                                </p>
                            <?php endif; ?>
                            <?php
                            break;
                    }

                    $content = trim((string)ob_get_clean());

                    if ($summary === '' || $content === '') {
                        continue;
                    }

                    $visibleIndex++;
                    ?>
                    <details<?= $visibleIndex === 1 ? ' open=""' : '' ?>>
                        <summary>
                            <span><?= esc_html($summary) ?></span>
                            <i>+</i>
                        </summary>

                        <div class="product-spec-content">
                            <?= $content ?>
                        </div>
                    </details>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
