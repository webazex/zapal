<?php

/**
 * Editorial content for sauce categories.
 * The normal WordPress term description remains the source of the main text.
 */
add_action('acf/init', function (): void {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group([
        'key' => 'group_zapal_category_editorial_20261110',
        'title' => 'Оформлення категорії',
        'fields' => [
            [
                'key' => 'field_zapal_category_image_20261110',
                'label' => 'Зображення категорії',
                'name' => 'zapal_category_image',
                'type' => 'image',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'library' => 'all',
                'instructions' => 'Велике зображення поруч з описом категорії. Для перекладів без окремого зображення використовується українська версія.',
            ],
            [
                'key' => 'field_zapal_category_note_title_20261110',
                'label' => 'Заголовок додаткового блоку',
                'name' => 'zapal_category_note_title',
                'type' => 'text',
                'instructions' => 'Наприклад: Національний рекорд України. Залиште порожнім, якщо блок не потрібен.',
            ],
            [
                'key' => 'field_zapal_category_note_text_20261110',
                'label' => 'Текст додаткового блоку',
                'name' => 'zapal_category_note_text',
                'type' => 'textarea',
                'rows' => 3,
                'new_lines' => '',
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'taxonomy',
                    'operator' => '==',
                    'value' => 'cproducts',
                ],
            ],
        ],
        'active' => true,
    ]);
});
