<?php
/**
 * Functions for Facebook login
 */

if ( ! function_exists( 'qode_membership_get_facebook_social_login' ) ) {
	/**
	 * Render form for facebook login
	 */
	function qode_membership_get_facebook_social_login() {

		$social_login_enabled   = qode_options()->getOptionValue( 'enable_social_login' ) == 'yes' ? true : false;
		$facebook_login_enabled = qode_options()->getOptionValue( 'enable_facebook_social_login' ) == 'yes' ? true : false;
		$enabled                = ( $social_login_enabled && $facebook_login_enabled ) ? true : false;

		if ( ! is_user_logged_in() && $enabled ) {

			$html = '<form class="qode-facebook-login-holder">'
			        . wp_nonce_field( 'qode_validate_facebook_login', 'qode_nonce_facebook_login_'.rand(), true, false ) .
				 '<button type="submit" class="qode-facebook-login"><i class="fa fa-facebook"></i></button>'.
			        '</form>';
			echo $html;

		}

	}

	add_action( 'qode_membership_social_network_login', 'qode_membership_get_facebook_social_login' );

}

if ( ! function_exists( 'qode_membership_check_facebook_user' ) ) {
	/**
	 * Function for getting facebook user data.
	 * Checks for user mail and register or log in user
	 */
	function qode_membership_check_facebook_user() {

		if ( isset( $_POST['response'] ) ) {
			$response            = $_POST['response'];
			$user_email          = $response['email'];
			$network             = 'facebook';
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

	add_action( 'wp_ajax_qode_membership_check_facebook_user', 'qode_membership_check_facebook_user' );
	add_action( 'wp_ajax_nopriv_qode_membership_check_facebook_user', 'qode_membership_check_facebook_user' );

}