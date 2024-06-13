<?php

function educrat_child_enqueue_styles() {
	wp_enqueue_style( 'educrat-child-style', get_stylesheet_uri() );
	wp_enqueue_script( 'custom_js', get_theme_file_uri() . '/js/custom.js' );
    wp_enqueue_script( 'custom_sha_js', get_theme_file_uri() . '/js/sha512.min.js' );
}
add_action( 'wp_enqueue_scripts', 'educrat_child_enqueue_styles', 100 );

function enqueue_datepicker() {
    wp_enqueue_script('jquery-ui-datepicker');
    wp_enqueue_style('jquery-style', 'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
}
add_action('wp_enqueue_scripts', 'enqueue_datepicker');

function wpse218049_enqueue_comments_reply() {
    if( is_singular() && comments_open() && ( get_option( 'thread_comments' ) == 1) ) {
        // Load comment-reply.js (into footer)
        //wp_enqueue_script( 'comment-reply', '/wp-includes/js/comment-reply.min.js', array(), false, true );
        wp_deregister_script( 'comment-reply' );
        wp_dequeue_script( 'comment-reply' );
        wp_enqueue_script( 'comment-reply', '/wp-includes/js/comment-reply.js', array(), false, true );
    }
}
add_action('wp_enqueue_scripts', 'wpse218049_enqueue_comments_reply');

/*================ after authenticate user make price to zero ================*/


add_action( 'woocommerce_before_calculate_totals', 'set_free_course_price_to_zero', 1800,2 );
function set_free_course_price_to_zero( $cart ) {

    if (is_admin() && ! defined( 'DOING_AJAX' )) {return;}

    $user_info = WC()->session->get('user_id');

    if ($user_info != 00000 || $user_info != '') {

        $is_special_user = $user_info;

        if ($is_special_user) {

            $offered_course_ids = array(11165,11449); //11431, 11433
            
            $discount = 0;

            $total_discount = 0;

            if(is_user_logged_in()){
                $current_user_id = get_current_user_id();
            }else{
                $current_user_id = 0;
            }
            
            foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) {

                if ( in_array( $cart_item['product_id'], $offered_course_ids ) ) {

                    /*==================================*/

                    $check_course_user = $cart_item['product_id'].'_'.$user_info.'_'.$current_user_id;
                    //print_r ($check_course_user);

                    global $wpdb;

                    /*$que = "TRUNCATE TABLE wp_demat_user_course";

                    $res = $wpdb->get_results($que);

                    die();*/


                    $query = "SELECT * FROM wp_demat_user_course WHERE course_user = '$check_course_user' AND sol_user_id = $current_user_id AND status = 'not_buy_yet'";

                    $results = $wpdb->get_results($query);

                    if ($results) {
                        
                        foreach ($results as $result) {

                            //print_r($result);

                            if($result->status == 'complete'){

                                //do nothing;

                            }else if($result->status == 'not_buy_yet'){

                                $discount = $cart_item['data']->get_price();

                                $total_discount = $total_discount + $discount;
                                
                            }else{

                                //do nothing;
                            }

                        }

                    } else {
                        
                        //do nothing;

                    }

                    /*==================================*/

                }

            }

            $cart->add_fee(__('Discount', 'Definedge Gurukul'), -$total_discount);
        }
    }

}



/*================================*/

/*// Hook into the "woocommerce_thankyou" action
add_action('woocommerce_thankyou', 'update_wp_demat_user_course_table_after_order', 10, 1);
function update_wp_demat_user_course_table_after_order($order_id) {
    global $wpdb;

    // Your custom table name
    $custom_table_name = 'wp_demat_user_course';

    // Get the order object
    //$order = wc_get_order($order_id);

    // Get order data
    //$order_total = $order->get_total();
    //$order_date = $order->get_date_created()->format('Y-m-d H:i:s');
    // Add more data as needed

    // Prepare data for insertion or update
    $data = array(
        'status' => 'complete',
        // Add more columns and data as needed
    );

    // Insert or update data in your custom table
    $wpdb->replace($custom_table_name, $data);
}*/


// Hook into the "woocommerce_thankyou" action
add_action('woocommerce_thankyou', 'update_wp_demat_user_course_table_after_order', 10, 1);

function update_wp_demat_user_course_table_after_order($order_id) {
    global $wpdb;

    // Retrieve data from the session
    $on_success_order_info = WC()->session->get('new_buy');

    // Your custom table name
    $custom_table_name = $wpdb->prefix . 'demat_user_course';

    // Prepare data for insertion or update
    $data = array(
        'status' => 'complete',
    );

    // Sanitize the input
    $on_success_order_info = sanitize_text_field($on_success_order_info);

    // Check if the row already exists in the table
    $existing_row = $wpdb->get_row($wpdb->prepare("SELECT * FROM $custom_table_name WHERE course_user = %s", $on_success_order_info));

    if ($existing_row) {
        // Update existing row
        $wpdb->update($custom_table_name, $data, array('course_user' => $on_success_order_info));
    } else {
        // Insert new row
        //$wpdb->insert($custom_table_name, array('course_user' => $on_success_order_info, 'status' => 'complete'));
    }
}






/*================ Instructors Page to show only LP-Instructors ================*/

function show_users_lp_function() {

	$admin_args = array(
    'role' => 'lp_teacher',
    'orderby' => 'id',
    'order' => 'ASC'
		);
		$administrators = get_users($admin_args);
		/*echo '<pre>';
		print_r($administrators);
		echo '</pre>';*/
		?>

		<div id="speaker-section-home" class="content-area">
		        <div class="row">
		            <?php if (!empty($administrators)) : ?>
		                <?php foreach ($administrators as $administrator) :
		                	$user_id = $administrator->ID;  
        					$user_avatar = get_avatar($user_id, 'full');  ?>


		                    <div class="col-sm-3 text-center speaker-grid">
		                        <?php echo get_avatar($administrator->ID, 'full'); ?>
		                        <h4><a href="<?php echo home_url(); ?>/instructor/<?php echo $administrator->user_nicename; ?>"><?php echo $administrator->display_name; ?></a></h4>
		                        <p><a href="<?php echo home_url(); ?>/instructor/<?php echo $administrator->user_nicename; ?>">View Profile</a></p>
		                    </div>

		                <?php endforeach; ?>
		            <?php else : ?>
		                <p>No administrators found.</p>
		            <?php endif; ?>
		        </div>
		</div>

		<?php
	
}

add_shortcode('usergridview','show_users_lp_function');

function show_comment_function() {
	return comment_form();
}
add_shortcode('commenTForm','show_comment_function'); 
/*================ Shortcode Function to show course price ================*/

// Add Course Price Shortcode

function learnpress_course_price_shortcode() {

	// Get the current course ID
    $course_id = get_the_ID();
    $user_id = get_current_user_id();

    //$is_enrolled = learn_press_is_user_enrolled( $user_id, $course_id );

	//if( $is_enrolled == 'true' || $is_enrolled = 1 ){

		//return '<p>Hello</p>';

	//}else {

		$course_regular_fee = get_post_meta( $course_id, '_lp_regular_price', true );
		$course_sale_fee = get_post_meta( $course_id, '_lp_sale_price', true );
		return '<div class="custCP">Course price : <s>&nbsp;₹'.$course_regular_fee.' &nbsp;</s>&nbsp; <span>₹'.$course_sale_fee.'.00</span></div>' ; 
	//}
	   
}
add_shortcode( 'course_price_show', 'learnpress_course_price_shortcode' );


/*================ Custom Login-Logout in menu ================*/


add_shortcode( 'custom_login_logout', 'custom_login_logout' );
function custom_login_logout() {
    ob_start();
    if (is_user_logged_in()) : 
    ?>
        <!-- <a class="nav-link" role="button" href="<?php //echo wp_logout_url(get_permalink()); ?>">Logout</a>  -->
    <?php 
    else : 
    ?>
        <a class="nav-link" role="button" href="<?php echo wp_login_url(get_permalink()); ?>">Login</a>
        <?php echo do_shortcode('[openid_connect_generic_login_button]'); ?>
    <?php 
    endif;
 
    return ob_get_clean();
}



/*================ Chekcout page while user not nolog show Login page ================*/

function show_login_form_on_checkout() {
    if (!is_user_logged_in()) {
        // Display the login form.
         	
        echo '<div class="row">';
        echo '<div class="col-lg-3">';
		echo '</div>';
        echo '<div class="col-lg-6">';
        	woocommerce_login_form();
		echo '</div>';
		echo '<div class="col-lg-3">';
		echo '</div>';
		echo '</div>';
		
		//echo '<div class="woocommerce-info">New User? <a href="'.home_url().'/lp-profile/" class="showregister">Click here to Sign Up</a>	</div>';
        // Optionally, you can display the registration form as well.
        //woocommerce_output_registration_form();
    }
}
add_action('woocommerce_before_checkout_form', 'show_login_form_on_checkout');

/*================ Return to shop page link redirection ================*/

add_filter('woocommerce_return_to_shop_redirect', 'mbies_change_return_shop_url');

function mbies_change_return_shop_url()
{
	return 'http://localhost/defguru/courses/';
}


/*================ Remove Billing Country Required ==================*/

add_filter('woocommerce_billing_fields', 'remove_billing_country_required', 10, 1);
function remove_billing_country_required($fields) {
    $fields['billing_country']['required'] = false;
    return $fields;
}


/*================ Get course related data in lp-teachers dashboard page ==================*/
/*
add_action('wp_ajax_get_course_results', 'get_course_results');
add_action('wp_ajax_nopriv_get_course_results', 'get_course_results');

function get_course_results()
{
    $course_id = $_POST['course_id']; 
    $course_fromDate = $_POST['from_Date']; 
    $course_toDate = $_POST['to_Date']; 


    echo $course_id.'<br>';

    echo $course_fromDate.'<br>';

    echo $course_toDate.'<br>';

    // Retrieve the selected course ID from AJAX request
    // Perform your custom query based on the received course ID
    // Example query (replace this with your actual query)

        global $wpdb;
        $query = $wpdb->prepare("SELECT oi.order_item_name, wo.id, wo.status, wo.total_amount, wo.billing_email, wo.date_created_gmt, wo.date_updated_gmt, woa.first_name, woa.last_name, woa.phone FROM wp_learnpress_order_items AS oi JOIN wp_learnpress_order_itemmeta AS om ON oi.order_item_id = om.learnpress_order_item_id JOIN wp_wc_orders AS wo ON oi.order_id-1 = wo.id JOIN wp_wc_order_addresses AS woa ON woa.order_id = wo.id WHERE oi.item_id = $course_id GROUP BY oi.order_item_id ORDER BY `order_item_id` DESC;");
        $results = $wpdb->get_results($query);
        //echo '<pre>';
        //print_r($results);
        //echo '<pre>';
        // Output the query results in table format
        if ($results) {
            $counter_no = 1;
            echo '<table class="custom-order-statuses order-table table" id="myTable">';
            echo '<thead>';
            echo '<tr>
                    <th>Sr. No.</th>
                    <th>Order ID</th>
                    <th>Course Name</th>
                    <th>Status</th>
                    <th>Total Amount</th>
                    <th>Email Id</th>
                    <th>User Name</th>
                    <th>User Phone</th>
                    <th>Date</th>
                </tr>';
            echo '</thead>';
            echo '<tbody>';
            foreach ($results as $result) {
                echo '<tr class="status_'.$result->status.'">';
                echo '<td>' . esc_html($counter_no) . '</td>';
                echo '<td>' . esc_html($result->id) . '</td>';
                echo '<td>' . esc_html($result->order_item_name) . '</td>';
                echo '<td>' . esc_html($result->status) . '</td>';
                echo '<td>' . esc_html(round($result->total_amount,2)) . '</td>';
                echo '<td>' . esc_html($result->billing_email) . '</td>';
                echo '<td>' . esc_html($result->first_name) .' '.esc_html($result->last_name) .'</td>';
                echo '<td>' . esc_html($result->phone) . '</td>';
                echo '<td>' . esc_html($result->date_updated_gmt) . '</td>';
                echo '</tr>';
                $counter_no++;
            }
            echo '</tbody>';
            echo '</table>';
        } else {
            echo 'No results found.';
        }
    wp_die(); // Always include this line to terminate the script
}

*/

/*================ Get course related data in lp-teachers dashboard page ==================*/

add_action('wp_ajax_get_order_count_results', 'get_order_count_results');
add_action('wp_ajax_nopriv_get_order_count_results', 'get_order_count_results');

function get_order_count_results()
{
    echo $course_id_select = $_POST['course_id']; 
    echo $from_date_select = $_POST['from_Date'];
    echo $to_date_select = $_POST['to_Date'];
    // Retrieve the selected course ID from AJAX request
    // Perform your custom query based on the received course ID
    // Example query (replace this with your actual query)

        global $wpdb;

        $querycount = $wpdb->prepare("SELECT oi.order_item_name, wo.id, wo.status, wo.total_amount, wo.billing_email, wo.date_created_gmt, wo.date_updated_gmt, woa.first_name, woa.last_name, woa.phone FROM wp_learnpress_order_items AS oi JOIN wp_learnpress_order_itemmeta AS om ON oi.order_item_id = om.learnpress_order_item_id JOIN wp_wc_orders AS wo ON oi.order_id-1 = wo.id JOIN wp_wc_order_addresses AS woa ON woa.order_id = wo.id WHERE oi.item_id = $course_id_select AND DATE(wo.date_created_gmt) BETWEEN '$from_date_select' AND '$to_date_select' AND wo.status = 'wc-completed' GROUP BY oi.order_item_id ORDER BY `order_item_id` DESC;");

        $resultscount = $wpdb->get_results($querycount);
        /*echo '<pre>';
        print_r($resultscount);
        echo '<pre>';*/
        // Output the query results in table format
        if (!$resultscount) {
            echo "Query Error: " . $wpdb->last_error;
            die(); // Terminate script execution
        }


        if ($resultscount) {


            /*
            [order_item_name] => Become a Noiseless Trader
            [id] => 8595
            [status] => trash
            [total_amount] => 1.18000000
            [billing_email] => manthan.khude@definedge.com
            [date_created_gmt] => 2024-01-04 11:27:39
            [date_updated_gmt] => 2024-02-27 07:40:39
            [first_name] => Manthan
            [last_name] => Khude
            [phone] => 09730474658

            */


            $counter_no = 1;
            echo '<table class="custom-order-statuses order-table table" id="myTable">';
            echo '<thead>';
            echo '<tr>
                    <th>Sr. No.</th>
                    <th>Order ID</th>
                    <th>Course Name</th>
                    <th>Status</th>
                    <th>Total Amount</th>
                    <th>Email Id</th>
                    <th>User Name</th>
                    <th>User Phone</th>
                    <th>Date</th>
                </tr>';
            echo '</thead>';
            echo '<tbody>';
            foreach ($resultscount as $result) {
                echo '<tr class="status_'.$result->status.'">';
                echo '<td>' . esc_html($counter_no) . '</td>';
                echo '<td>' . esc_html($result->id) . '</td>';
                echo '<td>' . esc_html($result->order_item_name) . '</td>';
                echo '<td>' . esc_html($result->status) . '</td>';
                echo '<td>' . esc_html(round($result->total_amount,2)) . '</td>';
                echo '<td>' . esc_html($result->billing_email) . '</td>';
                echo '<td>' . esc_html($result->first_name) .' '.esc_html($result->last_name) .'</td>';
                echo '<td>' . esc_html($result->phone) . '</td>';
                echo '<td>' . esc_html($result->date_created_gmt) . '</td>';
                echo '</tr>';
                $counter_no++;
            }
            echo '</tbody>';
            echo '</table>';
            
        } else {
            echo 'No results found.';
        }

    wp_die(); // Always include this line to terminate the script
}
 
/*================ Export order as csv on learnpress profile Dashboard page ==================*/
/*
add_action('wp_ajax_coursewise_export_csv', 'coursewise_export_csv_data');
add_action('wp_ajax_nopriv_coursewise_export_csv', 'coursewise_export_csv_data');

function coursewise_export_csv_data() {

    echo $course_id_selected = $_POST['course_id_csv']; 
    echo $from_date_selected = $_POST['from_Date_csv'];
    echo $to_date_selected = $_POST['to_Date_csv'];

    

    $sql_query_coursewise = "SELECT oi.order_item_name, wo.id, wo.status, wo.total_amount, wo.billing_email, wo.date_created_gmt, wo.date_updated_gmt, woa.first_name, woa.last_name, woa.phone FROM wp_learnpress_order_items AS oi JOIN wp_learnpress_order_itemmeta AS om ON oi.order_item_id = om.learnpress_order_item_id JOIN wp_wc_orders AS wo ON oi.order_id-1 = wo.id JOIN wp_wc_order_addresses AS woa ON woa.order_id = wo.id WHERE oi.item_id = $course_id_selected AND DATE(wo.date_created_gmt) BETWEEN '$from_date_selected' AND '$to_date_selected' AND wo.status = 'wc-completed' GROUP BY oi.order_item_id ORDER BY `order_item_id` DESC";

    // Fetch data from the database
    $results_coursewise = $wpdb->get_results($sql_query_coursewise, ARRAY_A);

    // Output CSV data
    $output_coursewise = fopen('php://output', 'w');
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="exported_course_data.csv"');

    // Add CSV headers
    fputcsv($output_coursewise, array('ID', 'Order Item Names', 'Status', 'Total Amount', 'Customer Email', 'First Name', 'Last Name', 'Phone', 'Date', ));

    // Add data rows 
    foreach ($results_coursewise as $row_coursewise) {       
        fputcsv($output_coursewise, $row_coursewise);
    }

    // Close the file handle
    fclose($output_coursewise);

    exit;

}


*/
/*================Disable Place Order Button on Checkout page if form fields are empty ==================*/
/*
add_action( 'woocommerce_after_checkout_validation', 'misha_one_err', 9999, 2);
 
function misha_one_err( $fields, $errors ){
 
	// if any validation errors
	if( ! empty( $errors->get_error_codes() ) ) {
 
		// remove all of them
		foreach( $errors->get_error_codes() as $code ) {
			
			print_r($code);
			$errors->remove( $code );
		}
 
		// add our custom one
		$errors->add( 'validation', 'Please fill the fields!' );
 
	}
 
}
*/

/*================ To change add to cart text on single product page ==================*/
add_filter( 'woocommerce_product_single_add_to_cart_text', 'woocommerce_custom_single_add_to_cart_text' ); 
function woocommerce_custom_single_add_to_cart_text() {
    return __( 'Enroll Now', 'woocommerce' ); 
}
/*================ To change add to cart text on product archives(Collection) page ==================*/
add_filter( 'woocommerce_product_add_to_cart_text', 'woocommerce_custom_product_add_to_cart_text' );  
function woocommerce_custom_product_add_to_cart_text() {
    return __( 'Enroll Now', 'woocommerce' );
}
/*================ Gusdt user login on course page ==================*/

function enqueue_auto_login_script() {
    if ( is_checkout() && !is_user_logged_in()) {
		wp_enqueue_script('auto-login-guest', get_stylesheet_directory_uri() . '/js/auto-login-guest.js', array('jquery'), '3.8', true, 0);
    }
}

/*================ Custom Shrtcode for  ==================*/

remove_action( 'woocommerce_order_details_after_order_table', 'woocommerce_order_again_button' );


/*================ Export order as csv All ==================*/
function export_csv_data() {
    global $wpdb;

    // Your SQL query and CSV generation code here
    /*$sql_query = "SELECT a.id, a.status, a.customer_id, a.total_amount, GROUP_CONCAT(b.order_item_name SEPARATOR ' | ') AS order_item_names, c.user_email FROM `wp_wc_orders` AS a LEFT JOIN wp_learnpress_order_items AS b ON b.order_id-1 = a.id LEFT JOIN `wp_users` AS c ON c.id = a.customer_id GROUP BY a.id, a.status, a.customer_id, a.total_amount, c.user_email ORDER BY a.id DESC";*/

    $sql_query = "SELECT a.id, a.status, a.customer_id, oa.first_name, oa.last_name, oa.phone, a.total_amount, c.user_email, a.date_created_gmt, GROUP_CONCAT(b.order_item_name SEPARATOR ' | ') AS order_item_names FROM `wp_wc_orders` AS a LEFT JOIN wp_learnpress_order_items AS b ON b.order_id-1 = a.id LEFT JOIN `wp_users` AS c ON c.id = a.customer_id LEFT JOIN `wp_wc_order_addresses` AS oa ON a.id = oa.order_id GROUP BY a.id, a.status, a.customer_id, a.total_amount, c.user_email ORDER BY a.id DESC";

    // Fetch data from the database
    $results = $wpdb->get_results($sql_query, ARRAY_A);

    // Output CSV data
    $output = fopen('php://output', 'w');
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="exported_data.csv"');

    // Add CSV headers
    fputcsv($output, array('ID', 'Status', 'Customer ID', 'First Name', 'Last Name', 'Phone', 'Total Amount', 'Customer Email', 'Date', 'Order Item Names'));

    // Add data rows
    foreach ($results as $row) {
        // Clean up order item names (replace newlines with commas)
        $order_item_names = str_replace("\n", ', ', $row['order_item_names']);
        unset($row['order_item_names']); // Remove redundant column
        $row['order_item_names'] = $order_item_names;

        fputcsv($output, $row);
    }

    // Close the file handle
    fclose($output);

    // Stop further execution
    exit;
}

add_action('wp_ajax_export_csv', 'export_csv_data');
add_action('wp_ajax_nopriv_export_csv', 'export_csv_data');



/*================ Export order as csv course wise ==================*/
function export_csv_data_new() {

    $aaaa = $_POST['idcourse'];
    $bbbb = explode('_',$aaaa);
    $selected_course_new = $bbbb[0];
    $statusorder_new = $_POST['statusorder'];
    $fromdate_select_new = $_POST['DateNewform'];
    $todate_select_new = $_POST['DateNewto'];

    global $wpdb;

    $sql_query = "SELECT wo.id, oi.order_item_name, wo.status, wo.total_amount, woa.first_name, woa.last_name, wo.billing_email, woa.phone, wo.date_created_gmt FROM wp_learnpress_order_items AS oi JOIN wp_learnpress_order_itemmeta AS om ON oi.order_item_id = om.learnpress_order_item_id JOIN wp_wc_orders AS wo ON oi.order_id-1 = wo.id JOIN wp_wc_order_addresses AS woa ON woa.order_id = wo.id WHERE oi.item_id = $selected_course_new AND DATE(wo.date_created_gmt) BETWEEN '$fromdate_select_new' AND '$todate_select_new' AND wo.status = '$statusorder_new' GROUP BY oi.order_item_id ORDER BY `order_item_id` DESC";

    // Fetch data from the database
    $results = $wpdb->get_results($sql_query, ARRAY_A);

    // Output CSV data
    $output = fopen('php://output', 'w');
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="exported_data.csv"');

    // Add CSV headers
    fputcsv($output, array('ID', 'Course Names', 'Status', 'Total Amount', 'First Name', 'Last Name', 'Customer Email', 'Phone', 'Date'));

    // Add data rows
    foreach ($results as $row) {
        
        fputcsv($output, $row);
    }

    // Close the file handle
    fclose($output);

    // Stop further execution
    exit;
}

add_action('wp_ajax_export_csv_new', 'export_csv_data_new');
add_action('wp_ajax_nopriv_export_csv_new', 'export_csv_data_new');

/*================ Custom Shrtcode for display course grid ==================*/
/*
function popular_courses_shortcode($atts) {
    $atts = shortcode_atts(
        array(
            'number' => 3, // Number of popular courses to display
            'order'  => 'desc', // Order by popularity (you can change this based on your criteria)
        ),
        $atts,
        'popular_courses'
    );

    $args = array(
        'post_type'      => 'lp_course',
        'posts_per_page' => $atts['number'],
        //'orderby'        => 'meta_value_num',
        //'meta_key'       => 'your_custom_field', // Replace with your criteria (e.g., sales count, ratings)
        'order'          => $atts['order'],
    );

    $popular_courses = new WP_Query($args);

    ob_start();

    if ($popular_courses->have_posts()) :
        while ($popular_courses->have_posts()) : $popular_courses->the_post();

            // Fetching Thumbnail Image
            $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'thumbnail');

            // Fetching Regular and Sale Prices
            $course = LP()->global['course'];
            $regular_price = $course->get_price_html();
            $sale_price = $course->get_sale_price_html();

    ?>
            <div class="course-item">
                <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php the_title(); ?>" />

                <h2><?php the_title(); ?></h2>

                <div class="course-content">
                    <?php the_content(); ?>
                </div>

                <p>Regular Price: <?php echo esc_html($regular_price); ?></p>
                
                <?php if (!empty($sale_price)) : ?>
                    <p>Sale Price: <?php echo esc_html($sale_price); ?></p>
                <?php endif; ?>
            </div>
    <?php
        endwhile;
    else :
        echo esc_html__('No popular courses found', 'text-domain');
    endif;

    wp_reset_postdata();

    return ob_get_clean();
}

add_shortcode('popular_courses', 'popular_courses_shortcode');
*/


/*

function all_courses_shortcode($atts) {
    $atts = shortcode_atts(
        array(
            'number' => 3, // Number of courses to display
        ),
        $atts,
        'all_courses'
    );

    $args = array(
        'post_type'      => 'lp_course',
        'posts_per_page' => $atts['number'],
        'orderby'        => 'date', // Order by date in descending order
        'order'          => 'desc',
    );

    $all_courses = new WP_Query($args);

    ob_start();

    if ($all_courses->have_posts()) :
        while ($all_courses->have_posts()) : $all_courses->the_post();

            // Fetching Thumbnail Image
            $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'thumbnail');

            // Fetching Regular and Sale Prices
            $course = LP()->global['course'];
            $regular_price = $course->get_price_html();
            $sale_price = $course->get_sale_price_html();

    ?>
            <div class="course-item">
                <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php the_title(); ?>" />

                <h2><?php the_title(); ?></h2>

                <div class="course-content">
                    <?php the_content(); ?>
                </div>

                <p>Regular Price: <?php echo esc_html($regular_price); ?></p>
                
                <?php if (!empty($sale_price)) : ?>
                    <p>Sale Price: <?php echo esc_html($sale_price); ?></p>
                <?php endif; ?>
            </div>
    <?php
        endwhile;
    else :
        echo esc_html__('No courses found', 'text-domain');
    endif;

    wp_reset_postdata();

    return ob_get_clean();
}

add_shortcode('all_courses', 'all_courses_shortcode');


*/

/*================ Custom Shrtcode for display course grid ==================*/




function all_courses_shortcode($atts) {
    $atts = shortcode_atts(
        array(
            'number' => 6, // Number of popular courses to display
            'order'  => 'desc', // Order by popularity (you can change this based on your criteria)
            'orderby'        => 'meta_value_num',
            'meta_key'       => 'your_custom_field', // Replace with your criteria (e.g., sales count, ratings)
        ),
        $atts,
        'all_courses'
    );

    $args = array(
        'post_type'      => 'lp_course',
        'posts_per_page' => $atts['number'],
        //'orderby'        => 'meta_value_num',
        //'meta_key'       => 'your_custom_field', // Replace with your criteria (e.g., sales count, ratings)
        'order'          => $atts['order'],
    );

    $all_courses = new WP_Query($args);

    ob_start();

?>

<div class="row">
<?php
    if ($all_courses->have_posts()) :
        while ($all_courses->have_posts()) : $all_courses->the_post();
            // Fetching Thumbnail Image
            $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'medium');
            
            $course_ids = get_the_ID();

            // Get LearnPress course data
            $course = learn_press_get_course($course_ids);

            $regular_pricefee  = get_post_meta( $course_ids, '_lp_regular_price', true );
            $course_salefee = get_post_meta( $course_ids, '_lp_sale_price', true );

            // Getting Permalink
            $permalink = get_permalink();

            // Getting Course Author
            $author_id = get_post_field('post_author', $course_ids);
            // Get author data using standard WordPress functions
            $author_data = get_userdata($author_id);

            if ($author_data) {
                $author_name = $author_data->display_name;
                $author_avatar = get_avatar($author_id, 32); // Adjust the size as needed
                $author_slug = $author_data->user_login;
            }

            $total_course_hours = get_post_meta($course_ids, 'total_course_hours', true);
            $total_chapters = get_post_meta($course_ids, 'total_chapters', true);
            $course_language = get_post_meta($course_ids, 'course_language', true);

            

?>  
            <div class="col-lg-1 col-md-1 col-sm-12"> </div>
            <div class="col-lg-4 col-md-4 col-sm-12">
                <div class="course-item grid-view">

                    
                        <div class="courseBox">
                            <a href="<?php echo esc_url($permalink); ?>">
                                <img class="courseBanner" src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php the_title(); ?>" />
                            </a>
                            
                            <div class="course-inner-box">
                                <a href="<?php echo esc_url($permalink); ?>">
                                <h2 class="courseheading"><?php the_title(); ?></h2>
                                <?php if(!empty($total_course_hours) && !empty($total_chapters) && !empty($course_language)) : ?>
                                <div class="courseMeta">
                                    <span><i class="fas fa-clock"></i><?php echo esc_html($total_course_hours); ?></span>
                                    <span><i class="fas fa-file"></i><?php echo esc_html($total_chapters); ?></span>
                                    <span><i class="fas fa-language"></i><?php echo esc_html($course_language); ?></span>
                                </div>
                                <?php else : ?>
                                <div class="courseMeta">
                                    <span>Coming Soon...</span>
                                </div>
                                <?php endif; ?>
                    
                                <div class="coursedetail">

                                    <div class="courseTeacher">
                                        <a href="<?php echo home_url(); ?>/lp-profile/<?php echo esc_html($author_slug); ?>">
                                        <p><span><?php echo $author_avatar; ?></span> <?php echo esc_html($author_name); ?></p>
                                        </a>
                                    </div>
                                    <div class="courseFeeDetail">

                                        <?php if (!empty($course_salefee)) : ?>
                                            <p class="courseFee"><s>₹<?php echo esc_html($regular_pricefee); ?></s><span>&nbsp;&nbsp; ₹<?php echo esc_html(round($course_salefee,2)); ?></span> </p>
                                        <?php else : ?>
                                            <p class="courseFee"><?php if (!empty($regular_pricefee) && !empty($course_salefee) ): ?>₹<?php endif; ?><?php echo esc_html($regular_pricefee); ?></p>
                                        <?php  endif; ?>

                                    </div>

                                </div>
                                </a>
                            </div>
                            
                        
                        </div>

                    

                    <!-- 
                    <pre>
                    <?php //print_r($course); ?>
                    <?php //print_r($author_data); ?>
                    </pre> 
                    -->

                </div>
            </div>
            <div class="col-lg-1 col-md-1 col-sm-12"> </div>
        
<?php
        endwhile;
    else :
        echo esc_html__('No course found', 'text-domain');
    endif;

?>
</div>

<?php

    wp_reset_postdata();

    return ob_get_clean();
}

add_shortcode('all_courses', 'all_courses_shortcode');






// Add this code on your child theme functions.php file

add_filter( 'learn-press/profile-tabs', 'reptro_learn_press_profile_tabs' );
function reptro_learn_press_profile_tabs( $tabs ){
    if( function_exists('LP') ){
        $settings = LP()->settings;
        $profile = LP_Profile::instance();
        $user    = $profile->get_user();
        $role    = $user->get_role();

        if( $role == 'lp_teacher' ){
            $tabs['reptro_custom'] = array(
                'title'    => esc_html__( 'Custom Tab', 'reptro' ),
                'slug'     => $settings->get( 'profile_endpoints.profile-custom', 'custom' ),
                'callback' => 'reptro_tab_custom',
                'priority' => 50
            );
        }
    }

    return $tabs;
}

function reptro_tab_custom(){
    ?>
    <h3>Custom Tab</h3>
    <p>Add your content here.</p>
    <?php
}



// Add the following code to your theme's functions.php file or a custom plugin

add_action('user_register', 'capture_learnpress_user_data', 10, 1);

function capture_learnpress_user_data($user_id) {
    // Check if LearnPress is active
    if (class_exists('LearnPress')) {
        // Get user data by user ID
        $user_data = get_userdata($user_id);

        // Extract relevant user information
        $user_info = array(
            'user_id'       => $user_id,
            'username'      => $user_data->user_login,
            'email'         => $user_data->user_email,
            'display_name'  => $user_data->display_name,
            // Add more fields as needed
        );

        // Convert the user data to JSON format
        $json_data = json_encode($user_info);

        // Log or process the JSON data as needed
        error_log("LearnPress User Registration JSON Data: " . $json_data);

        // You can also save the JSON data to a file, database, or send it to an external API

        // Example: Save JSON data to a file
        file_put_contents('/userdata/user_registration_data.json', $json_data);
    }
}
