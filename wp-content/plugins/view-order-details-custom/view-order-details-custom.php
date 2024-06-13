<?php
/*
Plugin Name: View Order Details Custom Page
Description: Creates a Custom Order Details Page in WordPress.
Version: 1.0
Author: Vijay Kumavat
*/



add_action('admin_menu', 'custom_order_details_page_menu');

function custom_order_details_page_menu(){
    add_menu_page(
        'Custom Order Details Page', // Page title
        'Custom Order Details Page', // Menu title
        'manage_options', // Capability required to access the page
        'custom-order-details-page', // Menu slug
        'custom_order_details_page_content' // Callback function to display content
    );
}



function get_learnpress_courses() {
    $args = array(
        'post_type'      => 'lp_course', // Custom post type for LearnPress courses
        'posts_per_page' => -1, // Retrieve all courses
    );

    $query = new WP_Query($args);

    $courses = array();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $courses[] = array(
                'id'   => get_the_ID(),
                'name' => get_the_title(),
                // You can add more course data as needed
            );
        }
        wp_reset_postdata();
    }

    return $courses;
}


function custom_order_details_page_content(){



    // Fetch LearnPress courses
    $courses = get_learnpress_courses();

    ?>
    <div class="wrap">
        <h2>Custom Admin Page</h2>
        <form method="get">
            <label for="course">Filter by Course:</label>
            <select name="course" id="course">
                <option value="">Select Courses</option>
                <!-- Populate options with course data -->
                <?php
                foreach ($courses as $course) {
                    printf('<option value="%d">%s</option>',
                        $course['id'],
                        esc_html($course['name'])
                    );
                }
                ?>
            </select>
            <input type="submit" class="button" value="Filter">
        </form>

        <!-- Display orders -->
        <!-- Your order display code here -->

            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($orders_query->have_posts()) :
                        while ($orders_query->have_posts()) : $orders_query->the_post();
                            $order = wc_get_order($orders_query->post->ID);
                            printf('<tr><td>%d</td><td>%s</td><td>%s</td></tr>',
                                $order->get_id(),
                                $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(),
                                $order->get_formatted_order_total()
                            );
                        endwhile;
                    else :
                        echo '<tr><td colspan="3">No orders found.</td></tr>';
                    endif;
                    ?>
                </tbody>
            </table>





    </div>

    <?php

}





/*

// Callback function to display content
function custom_order_details_page_content(){
    // Fetch LearnPress courses
    $courses = get_learnpress_courses();

    // Get the selected course ID from the URL parameter
    $course_id = isset($_GET['course']) ? intval($_GET['course']) : 0;

    // Fetch WooCommerce orders based on the selected course ID
    $args = array(
        'post_type'   => 'shop_order',
        'post_status' => array_keys(wc_get_order_statuses()), // Get all order statuses
        'meta_query'  => array(
            array(
                'key'     => '_course_id', // Meta key for storing course ID in orders
                'value'   => $course_id,
                'compare' => '='
            )
        )
    );
    $orders_query = new WP_Query($args);

    ?>
    <div class="wrap">
        <h2>Custom Admin Page</h2>
        <form method="get">
            <label for="course">Filter by Course:</label>
            <select name="course" id="course">
                <option value="">All Courses</option>
                <!-- Populate options with course data -->
                <?php
                foreach ($courses as $course) {
                    printf('<option value="%d" %s>%s</option>',
                        $course['id'],
                        selected($course_id, $course['id'], false),
                        esc_html($course['name'])
                    );
                }
                ?>
            </select>
            <input type="submit" class="button" value="Filter">
        </form>

        <!-- Display orders -->
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php

                if ($orders_query->have_posts()) :
                    while ($orders_query->have_posts()) : $orders_query->the_post();
                        $order = wc_get_order($orders_query->post->ID);
                        printf('<tr><td>%d</td><td>%s</td><td>%s</td></tr>',
                            $order->get_id(),
                            $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(),
                            $order->get_formatted_order_total()
                        );
                    endwhile;
                else :
                    echo '<tr><td colspan="3">No orders found.</td></tr>';
                endif;
                ?>
            </tbody>
        </table>
    </div>
    <?php
}


*/ 

/*
// Function to retrieve LearnPress courses
function get_learnpress_courses() {
    $args = array(
        'post_type'      => 'lp_course', // Custom post type for LearnPress courses
        'posts_per_page' => -1, // Retrieve all courses
    );

    $query = new WP_Query($args);

    $courses = array();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $courses[] = array(
                'id'   => get_the_ID(),
                'name' => get_the_title(),
                // You can add more course data as needed
            );
        }
        wp_reset_postdata();
    }

    return $courses;
}

*/



    

?>