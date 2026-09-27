<?php
    register_post_type( 'products', [
        'label'  => null,
        'labels' => [
            'name'               => __('Товари', 'zapal'), // основное название для типа записи
            'singular_name'      => __('Товар', 'zapal'), // название для одной записи этого типа
            'add_new'            => __('Додати товар', 'zapal'), // для добавления новой записи
            'add_new_item'       => __('Створення товару', 'zapal'), // заголовка у вновь создаваемой записи в админ-панели.
            'edit_item'          => __('Редагувати товар', 'zapal'), // для редактирования типа записи
            'new_item'           => __('Новий товар', 'zapal'), // текст новой записи
            'view_item'          => __('Перегляд товар', 'zapal'), // для просмотра записи этого типа.
            'search_items'       => __('Знайти товар', 'zapal'), // для поиска по этим типам записи
            'not_found'          => __('Товар відсутній', 'zapal'), // если в результате поиска ничего не было найдено
            'not_found_in_trash' => __('Товар відсутній у видалених', 'zapal'), // если не было найдено в корзине
            'parent_item_colon'  => '', // для родителей (у древовидных типов)
            'menu_name'          => __('Соуси', 'zapal'), // название меню
        ],
        'description'            => '',
        'public'                 => true,
        // 'publicly_queryable'  => null, // зависит от public
        // 'exclude_from_search' => null, // зависит от public
        // 'show_ui'             => null, // зависит от public
        // 'show_in_nav_menus'   => null, // зависит от public
        'show_in_menu'           => null, // показывать ли в меню админки
        // 'show_in_admin_bar'   => null, // зависит от show_in_menu
        'show_in_rest'        => null, // добавить в REST API. C WP 4.7
        'rest_base'           => null, // $post_type. C WP 4.7
        'menu_position'       => 5,
        'menu_icon'           => null,
        //'capability_type'   => 'post',
        //'capabilities'      => 'post', // массив дополнительных прав для этого типа записи
        //'map_meta_cap'      => null, // Ставим true чтобы включить дефолтный обработчик специальных прав
        'hierarchical'        => false,
        'supports'            => [ 'title', 'editor', 'thumbnail', 'custom-fields', 'page-attributes','post-formats'], // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
        'taxonomies'          => ['cproducts'],
        'has_archive'         => false,
        'rewrite'             => true,
        'query_var'           => true,
    ] );