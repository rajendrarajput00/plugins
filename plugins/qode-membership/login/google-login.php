<?php
/**
 * Functions for Google login
 */

if ( ! function_exists( 'qode_membership_get_google_social_login' ) ) {
	/**
	 * Render form for google login
	 */
	function qode_membership_get_google_social_login() {

		$social_login_enabled = qode_options()->getOptionValue( 'enable_social_login' ) == 'yes' ? true : false;
		$google_login_enabled = qode_options()->getOptionValue( 'enable_google_social_login' ) == 'yes' ? true : false;
		$enabled              = ( $social_login_enabled && $google_login_enabled ) ? true : false;

		if ( ! is_user_logged_in() && $enabled ) {

			$html = '<form class="qode-google-login-holder">'
			        . wp_nonce_field( 'qode_validate_googleplus_login', 'qode_nonce_google_login_'.rand(), true, false ) .
					'<button type="submit" class="qode-google-login"><i class="fa fa-google-plus"></i></button>' .
			        '</form>';
			print $html;

		}

	}

	add_action( 'qode_membership_social_network_login', 'qode_membership_get_google_social_login' );

}

if ( ! function_exists( 'qode_membership_check_google_user' ) ) {
	/**
	 * Function for getting google user data.
	 * Checks for user mail and register or log in user
	 */
	function qode_membership_check_google_user() {

		if ( isset( $_POST['response'] ) ) {
			$response            = $_POST['response'];
			$user_email          = $response['email'];
			$network             = 'googleplus';
			$response['network'] = $network;
			$nonce               = $response['nonce'];

			if ( email_exists( $user_email ) ) {
				//User already exist, log in user
				qode_membership_login_user_from_social_network( $user_email, $nonce, $network );
			} else {
				//Register new user
				qode_membership_register_user_from_social_network( $response );
			}
			$url = qode_membership_get_dashboard_page_url();
			if ( $url == '' ) {
				$url = esc_url( home_url( '/' ) );
			}
			qode_membership_ajax_response( 'success', esc_html__( 'Login successful, redirecting...', 'qode-membership' ), $url );
		}
		wp_die();

	}

	add_action( 'wp_ajax_qode_membership_check_google_user', 'qode_membership_check_google_user' );
	add_action( 'wp_ajax_nopriv_qode_membership_check_google_user', 'qode_membership_check_google_user' );

}