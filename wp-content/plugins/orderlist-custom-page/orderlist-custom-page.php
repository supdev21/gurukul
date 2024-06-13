<?php
/*
* Plugin Name: Orders List Custom Page
* Description: Creates a Custom Order Details Page in WordPress.
* Version: 1.0
* Author: Vijay Kumavat
*/
function custom_admin_js_enqueue() {
    if (is_admin()) {
        wp_register_script('custom-orderlist-js', plugins_url('/js/custom-orderlist-js.js', __FILE__), array('jquery'), '1.0', true);
        wp_enqueue_script('custom-orderlist-js');
    }
}
add_action('admin_enqueue_scripts', 'custom_admin_js_enqueue');
add_action('admin_menu', 'order_list_custom_page_menu');
function order_list_custom_page_menu(){
    add_menu_page(
        'Order List Custom Admin Page',
        'Order List Custom Admin Page',
        'manage_options',
        'order-list-custom-page',
        'order_list_custom_page_content'
    );
    add_submenu_page(
        'order-list-custom-page',
        'Export Orders Page',
        'Export Orders',
        'manage_options',
        'order-list-custom-page-child',
        'order_list_custom_page_child_content'
    );
    add_submenu_page(
        'order-list-custom-page',
        'Course Wise Search Page',
        'Course Wise Search',
        'manage_options',
        'order_list_child_coursewise',
        'order_list_child_coursewise_content'
    );
}
function order_list_custom_page_content(){
    include_once(plugin_dir_path(__FILE__) . '/list-of-orders-custom-page.php');
} 
function order_list_custom_page_child_content() {
    include_once(plugin_dir_path(__FILE__) . '/orderexport-custom-page.php');
} 
function order_list_child_coursewise_content() {
    include_once(plugin_dir_path(__FILE__) . '/coursewise-list-of-orders-custom-page.php');
}?>
