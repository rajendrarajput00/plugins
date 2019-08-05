<?php
/**
 * Plugin Name: Qode Membership
 * Description: Plugin that adds social login and user dashboard page
 * Author: Qode Themes
 * Version: 1.0.1
 */

require_once 'load.php';

if ( ! function_exists( 'qode_membership_text_domain' ) ) {
	/**
	 * Loads plugin text domain so it can be used in translation
	 */
	function qode_membership_text_domain() {
		load_plugin_textdomain( 'qode-membership', false, QODE_MEMBERSHIP_REL_PATH . '/languages' );
	}

	add_action( 'plugins_loaded', 'qode_membership_text_domain' );
}

if ( ! function_exists( 'qode_membership_scripts' ) ) {
	/**
	 * Loads plugin scripts
	 */
	function qode_membership_scripts() {

		wp_enqueue_style( 'qode_membership_style', plugins_url( QODE_MEMBERSHIP_REL_PATH . '/assets/css/qode-membership.min.css' ) );
		wp_enqueue_style( 'qode_membership_responsive_style', plugins_url( QODE_MEMBERSHIP_REL_PATH . '/assets/css/qode-membership-responsive.min.css' ) );

        //include google+ api
        wp_enqueue_script('qode_membership_google_plus_api', 'https://apis.google.com/js/platform.js', array(), null, false);

		$array_deps = array(
			'underscore',
			'jquery-ui-tabs'
		);

		if ( qode_membership_theme_installed() ) {
			$array_deps[] = 'default';
		}
		wp_enqueue_script( 'qode_membership_script', plugins_url( QODE_MEMBERSHIP_REL_PATH . '/assets/js/qode-membership.min.js' ), $array_deps, false, true );
	}

	add_action( 'wp_enqueue_scripts', 'qode_membership_scripts' );
}