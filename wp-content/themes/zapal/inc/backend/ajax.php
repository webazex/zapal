<?php
function zapal_ajax_form(){
    check_ajax_referer('zapal-ajax-nonce', 'nonce');
    //something
    wp_die();
}
if(wp_doing_ajax()) {
    add_action('wp_ajax_zapal-form', 'zapal_ajax_form');
    add_action('wp_ajax_nopriv_zapal-form', 'zapal_ajax_form');
}