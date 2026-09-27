<?php
add_action( 'after_setup_theme', 'zapal_register_menus' );

function zapal_register_menus() {
    register_nav_menu( 'main', 'Головне меню' );
}