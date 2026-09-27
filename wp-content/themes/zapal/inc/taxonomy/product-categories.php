<?php
    register_taxonomy( 'cproducts', [ 'products' ], [
        'label'                 => '', // определяется параметром $labels->name
        'labels'                => [
            'name'              => __('Категорії', 'zapal'),
            'singular_name'     => __('Категорія', 'zapal'),
            'search_items'      =>__('Знайти категорію', 'zapal'),
            'all_items'         => __('Всі категорії', 'zapal'),
            'view_item '        =>__('Перегляд категорій', 'zapal'),
            'parent_item'       => __('Батьківька категорія', 'zapal'),
            'parent_item_colon' => __('Батьківька категорія', 'zapal'),
            'edit_item'         => __('Редагувати категорію', 'zapal'),
            'update_item'       => __('Оновити категорію', 'zapal'),
            'add_new_item'      => __('Додати категорію', 'zapal'),
            'new_item_name'     => __('Назва нової категорії', 'zapal'),
            'menu_name'         => __('Категорії соусів', 'zapal'),
            'back_to_items'     => '← '.__('Назад до категорій соусів', 'zapal'),
        ],
        'description'           => '', // описание таксономии
        'public'                => true,
        // 'publicly_queryable'    => null, // равен аргументу public
        // 'show_in_nav_menus'     => true, // равен аргументу public
        // 'show_ui'               => true, // равен аргументу public
        // 'show_in_menu'          => true, // равен аргументу show_ui
        // 'show_tagcloud'         => true, // равен аргументу show_ui
        // 'show_in_quick_edit'    => null, // равен аргументу show_ui
        'hierarchical'          => true,

        'rewrite'               => true,
        //'query_var'             => $taxonomy, // название параметра запроса
        'capabilities'          => [],
        'meta_box_cb'           => null, // html метабокса. callback: `post_categories_meta_box` или `post_tags_meta_box`. false — метабокс отключен.
        'show_admin_column'     => true, // авто-создание колонки таксы в таблице ассоциированного типа записи. (с версии 3.5)
        'show_in_rest'          => true, // добавить в REST API
        'rest_base'             => null, // $taxonomy
        // '_builtin'              => false,
        //'update_count_callback' => '_update_post_term_count',
    ]);