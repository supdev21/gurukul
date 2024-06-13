<?php
/*
Plugin Name: Atom Payment Gateway
Plugin URI: http://atomtech.in
Description: Extends WooCommerce 3 by Adding the Paynetz Gateway.
Version: 3.2
Author: Atom Paynetz
Author URI: http://atomtech.in
*/

// Include our Gateway Class and register Payment Gateway with WooCommerce
add_action( 'plugins_loaded', 'woocommerce_atom_init', 0 );
define('IMGDIR', WP_PLUGIN_URL . "/" . plugin_basename(dirname(__FILE__)) . '/assets/img/');
define('SPINNER', WP_PLUGIN_URL . "/" . plugin_basename(dirname(__FILE__)) . '/assets/img/');
require_once 'AtomAES.php';

function woocommerce_atom_init() {
 
    if ( ! class_exists( 'WC_Payment_Gateway' ) ) return; 
   
    class WC_Gateway_Atom extends WC_Payment_Gateway {

        function __construct() {
            global $woocommerce;
            global $wpdb;
            global $atomTokenId;
            global $returnURL;
            global $jsCDN;
            global $payURL;
            global $login_id;
            global $password;
            global $atom_product_id;
            global $req_enc_key;
            global $req_salt_key;
            global $res_enc_key;
            global $res_salt_key;
            $this->id = "atom";
            $this->icon = IMGDIR . 'NTT_Logo.png';
            $this->method_title = __( "NTT DATA Payment Services", 'wc_gateway_atom' );
            $this->method_description = "NTT DATA Payment Services setting page.";
            $this->title = __( "NTT DATA Payment Services", 'wc_gateway_atom' );
            $this->has_fields = false;
            $this->init_form_fields();
            $this->init_settings();
            $payURL 				= $this->settings['atom_domain'];
            $jsCDN = $this->settings['atom_cdn_link'];
            $this->atom_port		= $this->settings['atom_port'];
            $login_id 		= $this->settings['login_id'];
            $password 		= $this->settings['password'];
            $this->description 		= $this->settings['description'];
            $atom_product_id  = $this->settings['atom_prod_id'];
            $req_enc_key = $this->settings['req_enc_key'];
            $req_salt_key = $this->settings['req_salt_key'];
            $this->res_enc_key = $this->settings['res_enc_key'];
            $this->res_salt_key = $this->settings['res_salt_key'];
            $res_enc_key = $this->settings['res_enc_key'];
            $res_salt_key = $this->settings['res_salt_key'];

            add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
            $this->check_atom_response();
            $this->update_transaction_status();

            if(is_checkout()){
                $last_order_id = wc_get_orders(array('limit' => 1, 'return' => 'ids' )); // Get last Order ID 
                $orderID = (string) reset($last_order_id);
    
                // Get the history data
                $order = wc_get_orders($orderID);
                 //echo "<pre>";
                 //print_r($order[0]->data['status']);
            
                $this->notify_url = add_query_arg('wc-api', get_class( $this ), home_url('/'));
                $this->return_url = add_query_arg(array('act' => "ret"), $this->notify_url);
                    
                $ru = wc_get_checkout_url(); 
                $ru = $this->notify_url;        
                $returnURL = $this->notify_url;
            }

        }

        public function init_form_fields() {
            $this->form_fields = array(
                'enabled' => array(
                    'type'             => 'checkbox',
                    'label'         => __('Enable NTT DATA Payment Services Module.', 'wc_gateway_atom'),
                    'default'         => 'no',
                    'description'     => 'Show in the Payment List as a payment option'
                ),
                'title' => array(
                    'title'         => __('Title:', 'wc_gateway_atom'),
                    'type'            => 'text',
                    'default'         => __('NTT DATA Payment Services', 'wc_gateway_atom'),
                    'description'     => __('This controls the title which the user sees during checkout.', 'wc_gateway_atom'),
                    'desc_tip'         => true
                ),
                'description' => array(
                    'title'         => __('Description:', 'wc_gateway_atom'),
                    'type'             => 'textarea',
                    'default'         => __("Pay securely by Credit or Debit Card or Internet Banking through Secure Servers."),
                    'description'     => __('This controls the description which the user sees during checkout.', 'wc_gateway_atom'),
                    'desc_tip'         => true
                ),
                'atom_domain' => array(
                    'title'         => __('Specify Domain', 'wc_gateway_atom'),
                    'type'             => 'text',
                    'description'     => __('Will be provided by Atom Paynetz Team after production movement', 'wc_gateway_atom'),
                    'desc_tip'         => true
                ),
                'atom_cdn_link' => array(
                    'title'         => __('Atom Js CDN Link', 'wc_gateway_atom'),
                    'type'             => 'text',
                    'description'     => __('Will be provided by Atom Paynetz Team', 'wc_gateway_atom'),
                    'desc_tip'         => true
                ),
                'login_id' => array(
                    'title'         => __('Login Id', 'wc_gateway_atom'),
                    'type'             => 'text',
                    'description'     => __('As provided by Atom Paynetz Team', 'wc_gateway_atom'),
                    'desc_tip'         => true
                ),
                'password' => array(
                    'title'         => __('Password', 'wc_gateway_atom'),
                    'type'             => 'password',
                    'description'     => __('As provided by Atom Paynetz Team', 'wc_gateway_atom'),
                    'desc_tip'         => true
                ),
                'atom_prod_id'     => array(
                    'title'         => __('Product ID', 'wc_gateway_atom'),
                    'type'             => 'text',
                    'description'     =>  __('Will be provided by Atom Paynetz Team after production movement', 'wc_gateway_atom'),
                    'desc_tip'         => true
                ),
                'atom_port'     => array(
                    'title'         => __('Port Number', 'wc_gateway_atom'),
                    'type'             => 'text',
                    'description'     =>  __('80 for Test Server & 443 for Production Server', 'wc_gateway_atom'),
                    'desc_tip'         => true
                ),
                'req_enc_key'     => array(
                    'title'         => __('Request Encypriton Key', 'wc_gateway_atom'),
                    'type'             => 'text',
                    'description'     =>  __('Request Encypriton Key, provided by Atom', 'wc_gateway_atom'),
                    'desc_tip'         => true
                ),
                'req_salt_key'     => array(
                    'title'         => __('Request Salt Key', 'wc_gateway_atom'),
                    'type'             => 'text',
                    'description'     =>  __('Request Salt Key, provided by Atom', 'wc_gateway_atom'),
                    'desc_tip'         => true
                ),
                'res_enc_key'     => array(
                    'title'         => __('Response Encypriton Key', 'wc_gateway_atom'),
                    'type'             => 'text',
                    'description'     =>  __('Response Encypriton Key, provided by Atom', 'wc_gateway_atom'),
                    'desc_tip'         => true
                ),
                'res_salt_key'     => array(
                    'title'         => __('Response Salt Key', 'wc_gateway_atom'),
                    'type'             => 'text',
                    'description'     =>  __('Response Salt Key, provided by Atom', 'wc_gateway_atom'),
                    'desc_tip'         => true
                ),
            );
        }

        public function update_transaction_status() {
            global $wpdb;
            $held_duration = get_option( 'woocommerce_hold_stock_minutes' );

            if ( $held_duration < 1 || get_option( 'woocommerce_manage_stock' ) != 'yes' )
                return;

            $date = date("Y-m-d H:i:s", strtotime( '-' . absint( 0 ) . ' MINUTES', current_time( 'timestamp' ) ) );

            $unpaid_orders = $wpdb->get_results( $wpdb->prepare( "
			SELECT posts.ID, postmeta.meta_key, postmeta.meta_value, posts.post_modified
			FROM {$wpdb->posts} AS posts
			RIGHT JOIN {$wpdb->postmeta} AS postmeta ON posts.id=postmeta.post_id
			WHERE 	posts.post_type   IN ('" . implode( "','", wc_get_order_types() ) . "')
			AND 	posts.post_status = 'wc-pending'
			AND 	posts.post_modified + INTERVAL 1 MINUTE < %s
		", $date ) );

//             echo "<pre>";
//             print_r($unpaid_orders);
            
            $pending_array = [];
            foreach($unpaid_orders as $value){
                if($value->meta_key == '_order_total'){
                   array_push($pending_array, $value);
                }
            }
            
            if(!empty($pending_array)){
             
                foreach($pending_array as $val){

                    global $req_enc_key;
                    global $req_salt_key;
                    global $res_enc_key;
                    global $res_salt_key;
                    global $payURL;
                    global $login_id;

                    $mer_txn=$val->ID;
                    $amt=$val->meta_value;
                    $date = date("Y-m-d", strtotime($val->post_modified));
                    $merchant_id = $login_id;

                    $atomenc = new AtomAES();
                    $str = "merchantid=".$merchant_id."&merchanttxnid=".$mer_txn."&amt=".$amt."&tdate=".$date;
                    $encrypted = $atomenc->encryptReQuery($str, $req_enc_key, $req_salt_key);

                    $queryURL = '';

                    if($payURL == 'https://caller.atomtech.in/ots/aipay/auth'){
                       $queryURL = 'https://paynetzuat.atomtech.in/paynetz/vftsv2';
                     }else{
                       $queryURL = 'https://payment.atomtech.in/paynetz/vftsv2';
                    }
                    
//                    echo "<br>";
//                    echo "<br>";
//                    echo $str;

                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                      CURLOPT_URL => $queryURL."?login=".$merchant_id."&encdata=".$encrypted,
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_ENCODING => "",
                      CURLOPT_MAXREDIRS => 10,
                      CURLOPT_TIMEOUT => 30,
                      CURLOPT_PORT => 443,
                      CURLOPT_RETURNTRANSFER => 1,    
                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                      CURLOPT_CUSTOMREQUEST => "POST",
                      CURLOPT_HTTPHEADER => array(
                        "cache-control: no-cache",
                        "content-type: application/x-www-form-urlencoded"
                      ),
                     CURLOPT_USERAGENT =>'woo-commerce plugin',  
                    ));

                    $response = curl_exec($curl);
                    $err = curl_error($curl);
                    if ($err) {
                       echo '<div class="woocommerce-error">Curl error: "'. $err.". Error in gateway credentials.</div>";
                       exit;
                    }
                    curl_close($curl);
                
                    $decrypted = $atomenc->decryptReQuery($response, $res_enc_key, $res_salt_key);
                    $jsonData = json_decode($decrypted, true);
//                    echo "<pre>";
//                    print_r($jsonData);
                    $result_resp= $jsonData[0]['verified'];
                    $unpaid_order=$mer_txn;
                    
//                    echo $result_resp;
                         
                    if ($unpaid_order) {
                        $order = wc_get_order( $unpaid_order );
                        if ( apply_filters( 'woocommerce_cancel_unpaid_order', 'checkout' === get_post_meta( $unpaid_order, '_created_via', true ), $order ) ) {
                            if($result_resp=='SUCCESS'){
                                $order->update_status( 'completed', __( 'Unpaid order completed - time limit reached.', 'woocommerce' ) );
                                echo 'success';
                            }else{
                                write_log('failed to perform requery, status - '. $result_resp);
                            }
                        }
                    }
                }
            } 
        }

        public function check_atom_response(){

            //echo get_class($this);  //  WC_Gateway_Atom
         
             if($_REQUEST['wc-api']== get_class($this)){

                

                    global $woocommerce, $post;
                    global $wpdb, $woocommerce;
                    $atomenc = new AtomAES();
                    
                    $data = $_POST['encData'];
                    $decrypted = $atomenc->decrypt($data, $this->res_enc_key, $this->res_salt_key);
                    $jsonData = json_decode($decrypted, true);

                    $order = new WC_Order($jsonData['payInstrument']['merchDetails']['merchTxnId']);
                    $order_id = $jsonData['payInstrument']['merchDetails']['merchTxnId'];
                    $VERIFIED = $jsonData['payInstrument']['responseDetails']['statusCode'];
                             
                                if($VERIFIED == 'OTS0000') {
                                     $order->payment_complete('completed');
                                     $woocommerce->cart->empty_cart();
                                     wp_safe_redirect($this->get_return_url( $order));
                                   }else {
                                    write_log('Failed Transaction alert');
                                    $order->update_status('failed');
                                    $this->msg['class'] = 'woocommerce-error';
                                    $this->msg['message'] = "<b style='color:red;font-size:20px'>The transaction has been failed or declined.</b>";
                                    $this->msg['order'] = $order; 
                                    do_action( 'woocommerce_set_cart_cookies',  true );
                                    wc_add_notice( __( 'The transaction has been failed or declined.', 'woocommerce' ) ,'error');
                                    wp_safe_redirect($woocommerce->cart->get_cart_url());
                                }
                     exit;		
                  
                }    
            
        }

        function showErrMessage($content){
            $cont = '';
            $cont .= '<div class="woocommerce">';
            $cont .= '<p>'.$this->msg['message'].$content.'</p></div>';
            return $cont;
        }
        
        function showMessage ($content) {
               return '<div class="woocommerce"><div class="'.$this->msg['class'].'">'.$this->msg['message'].'</div></div>'.$content;
        }

    }
    
     if (!function_exists('write_log')) {
    
        function write_log($log) {
            if (true === WP_DEBUG) {
                if (is_array($log) || is_object($log)) {
                    error_log(print_r($log, true));
                } else {
                    error_log($log);
                }
            }
        }
    
    }
    
    if( ! wp_next_scheduled( 'change_quote_hooks' ) ) {
            wp_schedule_event( time(), 'every_two_days', 'change_quote_hooks' );
    }
    
    add_action( 'change_quote_hooks', 'change_quote_function' );

    function change_quote_function() {
      echo "hi";    
    }

	
	
    add_filter('woocommerce_order_button_html', 'disable_place_order_button_html' );
    function disable_place_order_button_html( $button ) {
        
        $chosen_payment_method = WC()->session->get('chosen_payment_method');

        // If the targeted shipping zone is found, disable the button
        if($chosen_payment_method == 'atom') {
            $style = "background-color: #333333;border-color: #333333;color: #ffffff;";
            $text   = apply_filters( 'woocommerce_order_button_text', __( 'Place order', 'woocommerce' ) );
            $button = '<button type="button" name="woocommerce_checkout_place_order" id="custom_Checkout_Button" "'.$style.'" class="button" style="background-color: #333333;border-color: #333333;color: #ffffff;text-align:center">' . $text . '</button>';

        }
        
          ?>
          <script type="text/javascript">
            (function($){
                $('form.checkout').on( 'change', 'input[name^="payment_method"]', function() {
                var t = { updateTimer: !1,  dirtyInput: !1,
                    reset_update_checkout_timer: function() {
                        clearTimeout(t.updateTimer)
                    },  trigger_update_checkout: function() {
                        t.reset_update_checkout_timer(), t.dirtyInput = !1,
                        $(document.body).trigger("update_checkout")
                    }
                };
                    t.trigger_update_checkout();
                });
            })(jQuery);
            </script><?php

        return $button;
    }

    add_filter( 'woocommerce_payment_gateways', 'add_atom_gateway' );
    function add_atom_gateway( $methods ) {
        $methods[] = 'WC_Gateway_Atom';
        return $methods;
    }

    add_action('wp_print_scripts','add_my_plugin_js');
    function add_my_plugin_js(){
     global $jsCDN;   
     wp_register_script('atom_payment', $jsCDN);
     wp_enqueue_script('atom_payment');
     //wp_enqueue_style( 'atom_payment', plugins_url('/assets/css/style.css', __FILE__), false, '1.0.0', 'all');
    }

    add_action('wp_footer', 'checkout_billing_email_js_ajax' );
    function checkout_billing_email_js_ajax() {
        // Only on Checkout
        if( is_checkout() && ! is_wc_endpoint_url() ) :
            global $returnURL;
            global $login_id;
            global $current_user;
                //get user details   
                $current_user	= wp_get_current_user();
                $user_email     = $current_user->user_email;
		        $user_email     = $current_user->billing_email;
                $phone_number   = $current_user->billing_phone;
        ?>

        <script type="text/javascript">
         
        jQuery(function($){
//             console.log("wc_checkout_params.ajax_url");
//             console.log(wc_checkout_params.ajax_url);
            if (typeof wc_checkout_params === 'undefined') 
                return false;
            
            $(document.body).on("click", "#custom_Checkout_Button" ,function(evt) {
                evt.preventDefault();
                $.blockUI({ css: { backgroundColor: 'transparent', color: '#fff', border: 'none'} });  

                var billing_phone = document.getElementById("billing_phone").value;
                //console.log("billing_phone = " + billing_phone);
                var billing_email = document.getElementById("billing_email").value;
                //console.log("billing_email = " + billing_email);
                
                //To get the email address
                if(billing_email == "" || billing_email == null){
                    if("<?= $user_email ?>" == "" || "<?= $user_email ?>" == null) {
                        billing_email = "test@test.com"
                    }
                    else{
                        billing_email = "<?= $user_email ?>"
                    }
                }

                //To get the phone number
                if(billing_phone == "" || billing_phone == null){


                    if("<?= $phone_number ?>" == "" || "<?= $phone_number ?>" == null) {
                        billing_phone = "9999999999"
                    }
                    else{

                        $phonen = "<?= $phone_number ?>"

                        function validating($phonen){
                            if(preg_match('/^[0-9]{10}+$/', $phone)) {
                                
                                billing_phone = "<?= $phone_number ?>"

                            } else {

                                echo "Invalid Phone Number";
                            }

                        }

                        
                    }
                }
				
				//removing + and space from phone number
				var string = billing_phone;
                var newString = string.replace('+','');
                var newString2 = newString.replace(/\s/g,'');
				billing_phone = newString2;
				console.log("Phone no:"+billing_phone);
				
          
                $.ajax({
                    type: 'POST',
                    url: wc_checkout_params.ajax_url,
                    contentType: "application/x-www-form-urlencoded; charset=UTF-8",
                    enctype: 'multipart/form-data',
                    data: {
                        'action': 'ajax_order',
                        'fields': $('form.checkout').serializeArray(),
                        'user_id': <?php echo get_current_user_id(); ?>,
                    },
                    success: function (result) {
//                         console.log("checking atom insta pay = ", result);
                       
                        if(result == '0' || result == ''){
                            console.log("Error from Atom Insta Pay " , result);
                         }else{
                            const options = {
                                "atomTokenId": result,
                                "merchId": "<?= $login_id ?>",
                                "custEmail": billing_email,
                                "custMobile": billing_phone,
                                "returnUrl":"<?= $returnURL ?>"
                            }
// 							console.log(options);
                            let atom = new AtomPaynetz(options,'uat');
                        }
                     $.unblockUI();
                    },
                    error: function(error) {
                        $.unblockUI();
                        $("#custom_Checkout_Button").text('Place Order');
                    }
                });
            });
        });
        </script>
        <?php
        endif;
    }

    add_action('wp_ajax_ajax_order', 'submited_ajax_order_data');
    add_action( 'wp_ajax_nopriv_ajax_order', 'submited_ajax_order_data' );
    function submited_ajax_order_data() {
        if( isset($_POST['fields']) && ! empty($_POST['fields']) ) {

            $order    = new WC_Order();
            $cart     = WC()->cart;
            $checkout = WC()->checkout;
            $data     = [];

            // Loop through posted data array transmitted via jQuery
            foreach( $_POST['fields'] as $values ){
                // Set each key / value pairs in an array
                $data[$values['name']] = $values['value'];

                // TO add the delivery date in metadata
                if($values['name'] == "e_deliverydate"){
                    $order->update_meta_data( 'Delivery Date', $values['value']);
                }
                //end
            }

            $cart_hash          = md5( json_encode( wc_clean( $cart->get_cart_for_session() ) ) . $cart->total );
            $available_gateways = WC()->payment_gateways->get_available_payment_gateways();

            // Loop through the data array
            foreach ( $data as $key => $value ) {
                // Use WC_Order setter methods if they exist
                if ( is_callable( array( $order, "set_{$key}" ) ) ) {
                    $order->{"set_{$key}"}( $value );
                // Store custom fields prefixed with wither shipping_ or billing_
                } elseif ( ( 0 === stripos( $key, 'billing_' ) || 0 === stripos( $key, 'shipping_' ) )
                    && ! in_array( $key, array( 'shipping_method', 'shipping_total', 'shipping_tax' ) ) ) {
                    $order->update_meta_data( '_' . $key, $value );
                }
            }

            $order->set_created_via( 'checkout' );
            $order->set_cart_hash( $cart_hash );
            $order->set_customer_id( apply_filters( 'woocommerce_checkout_customer_id', isset($_POST['user_id']) ? $_POST['user_id'] : '' ) );
            $order->set_currency( get_woocommerce_currency() );
            $order->set_prices_include_tax( 'yes' === get_option( 'woocommerce_prices_include_tax' ) );
            $order->set_customer_ip_address( WC_Geolocation::get_ip_address() );
            $order->set_customer_user_agent( wc_get_user_agent() );
            $order->set_customer_note( isset( $data['order_comments'] ) ? $data['order_comments'] : '' );
            $order->set_payment_method( isset( $available_gateways[ $data['payment_method'] ] ) ? $available_gateways[ $data['payment_method'] ]  : $data['payment_method'] );
            $order->set_shipping_total( $cart->get_shipping_total() );
            $order->set_discount_total( $cart->get_discount_total() );
            $order->set_discount_tax( $cart->get_discount_tax() );
            $order->set_cart_tax( $cart->get_cart_contents_tax() + $cart->get_fee_tax() );
            $order->set_shipping_tax( $cart->get_shipping_tax() );
            $order->set_total( $cart->get_total( 'edit' ) );

            $checkout->create_order_line_items( $order, $cart );
            $checkout->create_order_fee_lines( $order, $cart );
            $checkout->create_order_shipping_lines( $order, WC()->session->get( 'chosen_shipping_methods' ), WC()->shipping->get_packages() );
            $checkout->create_order_tax_lines( $order, $cart );
            $checkout->create_order_coupon_lines( $order, $cart );

            /**
             * Action hook to adjust order before save.
             * @since 3.0.0
             */
            do_action('woocommerce_checkout_create_order', $order, $data);

            // $last_order_id = wc_get_orders(array('limit' => 1, 'return' => 'ids' )); // Get last Order ID 
            // $orderID = (string) reset($last_order_id);

            // Get the history data
            // $orderStatus = wc_get_orders($orderID);
            // if($orderStatus[0]->data['status'] == 'pending'){
               
            //  }else{

            // }

            // Save the order.
            $order_id = $order->save();
            do_action( 'woocommerce_checkout_update_order_meta', $order_id, $data );

            if($order_id){
                write_log("New order has been created for atom insta pay");
                global $login_id;
                global $password;
                global $atom_product_id;
                global $req_enc_key;
                global $req_salt_key;
                global $res_enc_key;
                global $res_salt_key;
                global $payURL;
                global $current_user;  

                $current_user	= wp_get_current_user();
                $current_user_email     = $current_user->user_email;
                $current_phone_number   = $current_user->billing_phone;

                $billing_email     = $order->get_billing_email();
                $billing_number   = $order->get_billing_phone();

                // To get the email address
                if($billing_email == "" || $billing_email == null){
                    if($current_user_email == "" || $current_user_email == null){
                        $user_email = "test@test.com";
                    }
                    else{
                        $user_email = $current_user_email;
                    }
                }
                else{
                    $user_email = $billing_email;
                }

                // To get the phone number
                if($billing_number == "" || $billing_number == null){
                    if($current_phone_number == "" || $current_phone_number == null){
                        $phone_number = "9999999999";
                    }
                    else{
                        $phone_number = $current_phone_number;
                    }
                }
                else{
                    $phone_number = $billing_number;
                }
				
				// to remove the + and space from number
				$phone_number = trim($str,"+");
				$phone_number = preg_replace('/\s+/', '', $phone_number);
    
                $jsondata =  '{ "payInstrument": { "headDetails": { "version": "OTSv1.1", "api": "AUTH", "platform": "FLASH" }, "merchDetails": { "merchId": "'.$login_id.'", "userId": "", "password": "'.$password.'", "merchTxnId": "'.$order_id.'", "merchTxnDate": "'.date("Y-m-d H:m:s").'" }, "payDetails": { "amount": "'.$order->get_total().'", "product": "'.$atom_product_id.'", "custAccNo": "213232323", "txnCurrency": "INR" }, "custDetails": { "custEmail": "'.$user_email.'", "custMobile": "'.$phone_number.'" }, "extras": {"udf1":"","udf2":"","udf3":"","udf4":"","udf5":""} } }';
    
                $atomenc = new AtomAES();
    
                $encData = $atomenc->encrypt($jsondata, $req_enc_key, $req_salt_key);
    
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $payURL,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_SSL_VERIFYHOST => false,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => "encData=".$encData."&merchId=".$login_id,
                    CURLOPT_HTTPHEADER => array(
                    "Content-Type: application/x-www-form-urlencoded"
                    ),
                ));
                
                $response = curl_exec($curl);
              
                write_log("Atom Insta Pay cURL response = ".$response);
             
                $getresp = explode("&", $response); 
    
                $encresp = substr($getresp[1], strpos($getresp[1], "=") + 1);       
    
                $decData = $atomenc->decrypt($encresp, $res_enc_key, $res_salt_key);
    
                if(curl_errno($curl)) {
                    $error_msg = curl_error($curl);
                    $atomTokenId = '0';
                    write_log("Atom Insta Pay cURL error_msg = ". $error_msg);
                }      
    
                if(isset($error_msg)) {
                    $atomTokenId = '0';
                    write_log("Atom Insta Pay cURL error_msg = ". $error_msg);
                }   
    
                curl_close($curl);
                    
                $res = json_decode($decData, true);
    
                if($res){
                    if($res['responseDetails']['txnStatusCode'] == 'OTS0000'){
                        $atomTokenId = $res['atomTokenId'];
                        // $atomTokenId = '0';
                    }else{
                        $atomTokenId = '0';
                    }
                }

             }else{
                $atomTokenId = '0';
                write_log("Order creation failed! ".$order_id);
            }
            echo $atomTokenId;
            
        }
        die();
    }

}
