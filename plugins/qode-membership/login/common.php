<?php
/**
 * Common functions for all social networks
 */

if ( ! function_exists( 'qode_membership_set_social_vars' ) ) {
	/**
	 * Save social variables for later use in js files
	 */
	function qode_membership_set_social_vars() {

		if ( !qode_membership_theme_installed() ) {
			return;
		}
		$social_login_enabled = qode_options()->getOptionValue( 'enable_social_login' ) == 'yes' ? true : false;
		if ( $social_login_enabled ) {

			$social_variables = array(
				'facebookAppId'  => qode_options()->getOptionValue( 'enable_facebook_social_login' ) == 'yes' ? qode_options()->getOptionValue( 'enable_facebook_login_fbapp_id' ) : null,
				'googleClientId' => qode_options()->getOptionValue( 'enable_google_social_login' ) == 'yes' ? qode_options()->getOptionValue( 'enable_google_login_client_id' ) : null
			);

			wp_localize_script( 'qode_membership_script', 'qodeSocialLoginVars', array(
				'social' => $social_variables
			) );
		}

	}

	add_action( 'wp_enqueue_scripts', 'qode_membership_set_social_vars', 11 );

}

if ( ! function_exists( 'qode_membership_login_user_from_social_network' ) ) {
	/**
	 * Login user from social network
	 *
	 * @param $email
	 * @param $nonce
	 * @param $network
	 */
	function qode_membership_login_user_from_social_network( $email, $nonce, $network ) {

		$user = get_user_by( 'email', $email );

		if ( ! is_wp_error( $user ) ) {
			if ( wp_verify_nonce( $nonce, 'qode_validate_' . $network . '_login' ) ) {
				wp_set_current_user( $user->ID, $user->user_login );
				wp_set_auth_cookie( $user->ID );
				do_action( 'wp_login', $user->user_login );
			}
		} else {
			esc_html_e('Not valid user', 'qode-membership');
		}

	}

}

if ( ! function_exists( 'qode_membership_register_user_from_social_network' ) ) {
	/**
	 * Register facebook user
	 *
	 * @param $params - parameters for logging in
	 */
	function qode_membership_register_user_from_social_network( $params ) {

		$nicename      = $params['name'];
		$email         = $params['email'];
		$password      = $params['id'];
		$network       = $params['network'];
		$username      = str_replace( '-', '_', sanitize_title( $params['name'] ) ) . '_' . $network;
		$link          = isset( $params['link'] ) ? $params['link'] : '';
		$profile_image = isset( $params['image'] ) ? $params['image'] : '';
		$nonce = $params['nonce'];

		$password = qode_membership_generate_password( $password, $username );

		if ( wp_verify_nonce( $nonce, 'qode_validate_' . $network . '_login' ) ) {

			$userdata = array(
				'user_login'   => $username,
				'display_name' => $nicename,
				'user_email'   => $email,
				'user_pass'    => $password,
				'role'         => 'subscriber' //Change if you have another default user role
			);

			$user_id = wp_insert_user( $userdata );
			add_user_meta( $user_id, 'social_profile_image', $profile_image, true );
			update_user_meta( $user_id, $network, $link );

			//On success
			if ( ! is_wp_error( $user_id ) ) {
				qode_membership_login_user_from_social_network( $email, $nonce, $network );
			} else {
				echo esc_html( $user_id->get_error_message() );
			}

		}

	}

}

if ( ! function_exists( 'qode_membership_generate_password' ) ) {
	/**
	 * Generate password for user
	 *
	 * @param $str1
	 * @param $str2
	 *
	 * @return array|string
	 */
	function qode_membership_generate_password( $str1, $str2 ) {

		$str1 = str_split( $str1 );
		$str2 = str_split( $str2 );

		$password = array_merge( $str1, $str2 );
		shuffle( $password );
		$password = implode( '', $password );

		return $password;
	}

}