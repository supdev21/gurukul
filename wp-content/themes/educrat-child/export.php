<?php
/**
 * The template for Custom Dashboard Speakers
 *
 * @package WordPress
 * @subpackage Educrat
 * @since Educrat 1.0
 */
/*
*Template Name: Custom Dashboard Speakers
*/

get_header();

// Load WordPress
require_once('wp-load.php');

// Connect to the WordPress database
global $wpdb;


$course_id_select = $_POST['course_selected_Export'];
$from_date_select = $_POST['fromDateExport'];
$to_date_select = $_POST['toDateExport'];

//global $wpdb;
$quueerryy = "SELECT oi.order_item_name, wo.id, wo.status, wo.total_amount, wo.billing_email, wo.date_created_gmt, wo.date_updated_gmt, woa.first_name, woa.last_name, woa.phone FROM wp_learnpress_order_items AS oi JOIN wp_learnpress_order_itemmeta AS om ON oi.order_item_id = om.learnpress_order_item_id JOIN wp_wc_orders AS wo ON oi.order_id-1 = wo.id JOIN wp_wc_order_addresses AS woa ON woa.order_id = wo.id WHERE oi.item_id = $course_id_select AND DATE(wo.date_created_gmt) BETWEEN '$from_date_select' AND '$to_date_select' AND wo.status = 'wc-completed' GROUP BY oi.order_item_id ORDER BY `order_item_id` DESC";
 
$results = $wpdb->get_results($query, ARRAY_A);

// Set CSV headers
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="exported_data.csv"');

// Open file handle to standard output
$fp = fopen('php://output', 'w');

// Output CSV column headers
fputcsv($fp, array_keys($results[0]));

// Output each row of data
foreach ($results as $row) {
    fputcsv($fp, $row);
}

// Close file handle
fclose($fp);

// Exit to prevent WordPress from rendering anything else
exit();

get_footer(); 
?>

