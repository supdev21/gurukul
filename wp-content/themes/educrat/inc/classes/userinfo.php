<?php 

class Educrat_Apus_Userinfo{

	/**
	 * Constructor 
	 */
	public function __construct() {
		
		add_action( 'wp_ajax_nopriv_apus_ajax_login',  array($this, 'processLogin') );
		add_action( 'wp_ajax_nopriv_apus_ajax_forgotpass',  array($this, 'processForgotPassword') );
		add_action( 'wp_ajax_nopriv_apus_ajax_register',  array($this, 'processRegister') );

		
		add_action( 'cmb2_admin_init', array( $this, 'admin_register_user_profile_metabox') );
		
	}
	
	public function processLogin() {
		// First check the nonce, if it fails the function will break
   		check_ajax_referer( 'ajax-apus-login-nonce', 'security_login' );

   		$info = array();
   		
   		$info['user_login'] = isset($_POST['username']) ? $_POST['username'] : '';
	    $info['user_password'] = isset($_POST['password']) ? $_POST['password'] : '';
	    $info['remember'] = isset($_POST['remember']) ? true : false;

		$user_signon = wp_signon( $info, false );
	    if ( is_wp_error($user_signon) ){
			$result = json_encode(array('loggedin' => false, 'msg' => esc_html__('Wrong username or password. Please try again!!!', 'educrat')));
	    } else {
			wp_set_current_user($user_signon->ID); 
	        $result = json_encode(array('loggedin' => true, 'msg' => esc_html__('Signin successful, redirecting...', 'educrat')));
	    }

   		echo trim($result);
   		die();
	}

	public function processForgotPassword() {
	 	
		// First check the nonce, if it fails the function will break
	    check_ajax_referer( 'ajax-apus-lostpassword-nonce', 'security_lostpassword' );
		
		global $wpdb;
		
		$account = isset($_POST['user_login']) ? $_POST['user_login'] : '';
		
		if( empty( $account ) ) {
			$error = esc_html__( 'Enter an username or e-mail address.', 'educrat' );
		} else {
			if(is_email( $account )) {
				if( email_exists($account) ) {
					$get_by = 'email';
				} else {
					$error = esc_html__( 'There is no user registered with that email address.', 'educrat' );			
				}
			} else if (validate_username( $account )) {
				if( username_exists($account) ) {
					$get_by = 'login';
				} else {
					$error = esc_html__( 'There is no user registered with that username.', 'educrat' );				
				}
			} else {
				$error = esc_html__(  'Invalid username or e-mail address.', 'educrat' );		
			}
		}	
		
		if (empty ($error)) {
			$random_password = wp_generate_password();

			$user = get_user_by( $get_by, $account );
				
			$update_user = wp_update_user( array ( 'ID' => $user->ID, 'user_pass' => $random_password ) );
				
			if( $update_user ) {
				
				$from = get_option('admin_email');
				
				
				$to = $user->user_email;
				$subject = esc_html__( 'Your new password', 'educrat' );
				
				$message = esc_html__( 'Your new password is: ', 'educrat' ) .$random_password;
					
				$headers = sprintf( "From: %s <%s>\r\n Content-type: text/html", get_bloginfo('name'), $from );
				
				$mail = call_user_func( implode('_', array('wp', 'mail') ), $to, $subject, $message, $headers );

				if( $mail ) {
					$success = esc_html__( 'Check your email address for you new password.', 'educrat' );
				} else {
					$error = esc_html__( 'System is unable to send you mail containg your new password.', 'educrat' );						
				}
			} else {
				$error =  esc_html__( 'Oops! Something went wrong while updating your account.', 'educrat' );
			}
		}
	
		if ( ! empty( $error ) ) {
			echo json_encode(array('loggedin'=> false, 'msg'=> $error));
		}
				
		if ( ! empty( $success ) ) {
			echo json_encode(array('loggedin' => true, 'msg'=> $success ));	
		}
		die();
	}

	public function registration_validation( $username, $email, $password, $confirmpassword ) {
		global $reg_errors;
		$reg_errors = new WP_Error;
		
		if ( empty( $username ) || empty( $password ) || empty( $email ) || empty( $confirmpassword ) ) {
		    $reg_errors->add('field', esc_html__( 'Required form field is missing', 'educrat' ) );
		}

		if ( 4 > strlen( $username ) ) {
		    $reg_errors->add( 'username_length', esc_html__( 'Username too short. At least 4 characters is required', 'educrat' ) );
		}

		if ( username_exists( $username ) ) {
	    	$reg_errors->add('user_name', esc_html__( 'That username already exists!', 'educrat' ) );
		}

		if ( ! validate_username( $username ) ) {
		    $reg_errors->add( 'username_invalid', esc_html__( 'The username you entered is not valid', 'educrat' ) );
		}

		if ( 5 > strlen( $password ) ) {
	        $reg_errors->add( 'password', esc_html__( 'Password length must be greater than 5', 'educrat' ) );
	    }

	    if ( $password != $confirmpassword ) {
	        $reg_errors->add( 'password', esc_html__( 'Password must be equal Confirm Password', 'educrat' ) );
	    }

	    if ( !is_email( $email ) ) {
		    $reg_errors->add( 'email_invalid', esc_html__( 'Email is not valid', 'educrat' ) );
		}

		if ( email_exists( $email ) ) {
		    $reg_errors->add( 'email', esc_html__( 'Email Already in use', 'educrat' ) );
		}
	}

	public function complete_registration($username, $password, $email) {
        $userdata = array(
	        'user_login' => $username,
	        'user_email' => $email,
	        'user_pass' => $password,
        );
        return wp_insert_user( $userdata );
	}

	public function processRegister() {
		global $reg_errors;
		check_ajax_referer( 'ajax-apus-register-nonce', 'security_register' );
        $this->registration_validation( $_POST['username'], $_POST['email'], $_POST['password'], $_POST['confirmpassword'] );
        if ( 1 > count( $reg_errors->get_error_messages() ) ) {
	        $username = sanitize_user( $_POST['username'] );
	        $email = sanitize_email( $_POST['email'] );
	        $password = esc_attr( $_POST['password'] );
	 		
	        $user_id = $this->complete_registration($username, $password, $email);
	        if ( ! is_wp_error( $user_id ) ) {

	        	$jsondata = array('loggedin' => true, 'msg' => esc_html__( 'You have registered, redirecting ...', 'educrat' ) );
	        	$info['user_login'] = $username;
			    $info['user_password'] = $password;
			    $info['remember'] = 1;
				
				wp_signon( $info, false );
	        } else {
		        $jsondata = array('loggedin' => false, 'msg' => esc_html__( 'Register user error!', 'educrat' ) );
		    }
	    } else {
	    	$jsondata = array('loggedin' => false, 'msg' => implode(', <br>', $reg_errors->get_error_messages()) );
	    }
	    echo json_encode($jsondata);
	    exit;
	}

	public static function admin_register_user_profile_metabox() {
		$prefix = '_user_';
		
		$fields = array();
		if ( educrat_is_learnpress_activated() ) {
			$fields[] = array(
                'name'              => esc_html__( 'LearnPress Job Title', 'educrat' ),
                'id'                => $prefix . 'job_title',
                'type'              => 'text',
			);
		}

		$socials = self::tutor_socials_profile();

		if ( educrat_is_tutor_activated() ) {
			foreach ($socials as $k => $v) {
				$fields[] = array(
	                'name'              => $v,
	                'id'                => $prefix . 'tutor_'. $k,
	                'type'              => 'text',
				);
			}
		}

		$fields = apply_filters('educrat-get-user-profile-fields-admin', $fields);

		$cmb_user = new_cmb2_box( array(
			'id'               => $prefix . 'edit',
			'title'            => esc_html__( 'User Profile', 'educrat' ),
			'object_types'     => array( 'user' ),
			'show_names'       => true,
			'new_user_section' => 'add-new-user',
			'fields' => $fields
		) );
	}

	public static function tutor_socials_profile() {
		$socials = apply_filters(
			'educrat-tutor-social-profiles',
			array(
				'facebook' => esc_html__( 'Tutor Facebook Profile', 'educrat' ),
				'twitter' => esc_html__( 'Tutor Twitter Profile', 'educrat' ),
				'youtube' => esc_html__( 'Tutor Youtube Profile', 'educrat' ),
				'linkedin' => esc_html__( 'Tutor Linkedin Profile', 'educrat' ),
			)
		);
		return $socials;
	}
}

new Educrat_Apus_Userinfo();