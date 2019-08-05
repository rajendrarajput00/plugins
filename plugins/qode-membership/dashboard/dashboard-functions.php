<?php

if (!function_exists('qode_membership_get_dashboard_page_url')) {
	/**
	 * Function that returns dashboard page url
	 *
	 * @return string
	 */
	function qode_membership_get_dashboard_page_url() {
		$url = '';
		$pages = get_all_page_ids();
		
		foreach ($pages as $page) {
			if (get_post_status( $page ) == 'publish' && get_page_template_slug($page) == 'user-dashboard.php') {
				$url = esc_url(get_the_permalink($page));
				break;
			}
		}

		return $url;
	}
}

if (!function_exists('qode_membership_get_my_account_page_url')) {
	/**
	 * Function that returns my account page url
	 *
	 * @return string
	 */
	function qode_membership_get_my_account_page_url() {

        $url = '';

        if(qode_is_woocommerce_installed()) {
            $my_account_page_id = get_option('woocommerce_myaccount_page_id');

            if(isset($my_account_page_id) && !empty($my_account_page_id)) {
                $url = esc_url(get_permalink($my_account_page_id));
            } else {
                $url = qode_membership_get_dashboard_page_url();
            }
        }
		
		return $url;
	}
}

if (!function_exists('qode_membership_get_redirect_url')) {
    /**
     * Function that returns my account page url
     *
     * @return string
     */
    function qode_membership_get_redirect_url() {

        $url = qode_membership_get_dashboard_page_url();

        if($url == '' && qode_is_woocommerce_installed()) {
            $my_account_page_id = get_option('woocommerce_myaccount_page_id');

            if(isset($my_account_page_id) && !empty($my_account_page_id)) {
                $url = esc_url(get_permalink($my_account_page_id));
            }
        }

        return $url;
    }
}

if ( ! function_exists( 'qode_membership_get_dashboard_template_part' ) ) {
	/**
	 * Loads Dashboard template part.
	 *
	 * @param $template
	 * @param string $slug
	 * @param array $params
	 *
	 * @return string
	 */
	function qode_membership_get_dashboard_template_part( $template, $slug = '', $params = array() ) {

		//HTML Content from template
		$html = '';

		$theme_template_path  = get_template_directory() . '/qode-membership/dashboard/page-templates/template-parts';
		$plugin_template_path = QODE_MEMBERSHIP_ABS_PATH . '/dashboard/page-templates/template-parts';

		if ( $slug !== '' ) {
			$template = "{$template}-{$slug}.php";
		} else {
			$template = "{$template}.php";
		}

		if ( file_exists( $theme_template_path . '/' . $template ) ) {
			$temp_path = $theme_template_path . '/' . $template;
		} else {
			$temp_path = $plugin_template_path . '/' . $template;
		}
		if ( is_array( $params ) && count( $params ) ) {
			extract( $params );
		}

		if ( $temp_path ) {
			ob_start();
			include( $temp_path );
			$html = ob_get_clean();
		}

		return $html;
	}
}

if ( ! function_exists( 'qode_membership_get_dashboard_pages' ) ) {
	/**
	 * Loads dashboard page content based on user action
	 *
	 * @return string
	 */
	function qode_membership_get_dashboard_pages() {

		$action = 'profile';
		if ( isset( $_GET['user-action'] ) ) {
			$action = $_GET['user-action'];
		}

		//Template params
		$params  = array();
		$user_id = get_current_user_id();
		if ( $action == 'profile' || $action == 'edit-profile' ) {
			$params['first_name']  = get_the_author_meta( 'first_name', $user_id );
			$params['last_name']   = get_the_author_meta( 'last_name', $user_id );
			$params['email']       = get_the_author_meta( 'email', $user_id );
			$params['website']     = get_the_author_meta( 'url', $user_id );
			$params['description'] = get_the_author_meta( 'description', $user_id );
			$profile_image         = get_user_meta( $user_id, 'social_profile_image', true );
			if ( $profile_image == '' ) {
				$profile_image = get_avatar( $user_id, 96 );
			} else {
				$profile_image = '<img src="' . esc_url( $profile_image ) . '">';
			}
			$params['profile_image'] = $profile_image;
		}

		//Array of dashboard pages, url - template
		$pages = array(
			'profile'      => qode_membership_get_dashboard_template_part( 'profile', '', $params ),
			'edit-profile' => qode_membership_get_dashboard_template_part( 'edit-profile', '', $params )
		);
		$pages = apply_filters( 'qode_membership_dashboard_pages', $pages );

		//Include template part
		if ( isset( $pages[ $action ] ) ) {
			$html = $pages[ $action ];
		} else {
			$html = $pages['profile'];
		}

		return $html;
	}
}

if ( ! function_exists( 'qode_membership_get_dashboard_navigation_items' ) ) {
	/**
	 * Function that returns dashboard navigation items
	 *
	 * @return array|mixed|void
	 */
	function qode_membership_get_dashboard_navigation_items() {

		$dashboard_url = qode_membership_get_dashboard_page_url();
		$account_url = qode_membership_get_my_account_page_url();
		
		$items         = array(
			'profile'      => array(
				'url'			=> esc_url($dashboard_url),
				'text'			=> esc_html__( 'Profile', 'qode-membership'),
				'user_action'	=> 'profile',
				'icon'			=> apply_filters('qode_membership_dashboard_profile_icon', '<span class="icon dripicons-user"></span>')
			),
			'edit-profile' => array(
				'url'			=> esc_url(add_query_arg( array( 'user-action' => 'edit-profile' ), $dashboard_url)),
				'text'			=> esc_html__( 'Edit Profile', 'qode-membership'),
				'user_action'	=> 'edit-profile',
				'icon'			=> apply_filters('qode_membership_dashboard_edit_profile_icon', '<span class="icon dripicons-gear"></span>')
			)
		);
		
		$items = apply_filters('qode_membership_dashboard_navigation_pages', $items, $dashboard_url);

		return $items;
	}
}

if (!function_exists('qode_membership_get_woo_membership_profile_key')) {
    function qode_membership_get_woo_membership_profile_key() {
        $profile_key = 'qode_membership_profile';
        return apply_filters('qode_membership_dashboard_profile_key', $profile_key);
    }
}

if (!function_exists('qode_membership_get_woo_membership_profile_value')) {
    function qode_membership_get_woo_membership_profile_value() {
        $profile_value = esc_html__('Membership Profile', 'qode-membership');
        return apply_filters('qode_membership_dashboard_profile_value', $profile_value);
    }
}

if (!function_exists('qode_membership_extend_woo_navigation')) {
    function qode_membership_extend_woo_navigation($navigation) {

        $navigation_new = array();
        if(qode_membership_get_dashboard_page_url() !== '') {
            $navigation_new[qode_membership_get_woo_membership_profile_key()] = qode_membership_get_woo_membership_profile_value();
        }

        return array_merge($navigation_new, $navigation);
    }

    add_filter('woocommerce_account_menu_items', 'qode_membership_extend_woo_navigation');
}

if (!function_exists('qode_membership_set_woo_navigation_membership_profile')) {
    function qode_membership_set_woo_navigation_membership_profile($url, $endpoint) {
        if($endpoint == qode_membership_get_woo_membership_profile_key()) {
            return qode_membership_get_dashboard_page_url();
        }
        else {
            return $url;
        }
    }

    add_filter('woocommerce_get_endpoint_url', 'qode_membership_set_woo_navigation_membership_profile', 10, 2);
}

if ( ! function_exists( 'qode_membership_update_user_profile' ) ) {

	function qode_membership_update_user_profile() {

		if ( empty( $_POST ) || ! isset( $_POST ) ) {
			qode_membership_ajax_response( 'error', esc_html__( 'All fields are empty', 'qode-membership' ) );
		} else {
			$dashboard_url = qode_membership_get_dashboard_page_url();
			parse_str( $_POST['data'], $update_data );

			//Check nonce
			if ( wp_verify_nonce( $update_data['qode_nonce_edit_profile'], 'qode_validate_edit_profile' ) ) {

				$user_id = get_current_user_id();
				if ( $user_id ) {

					//Update password
					if ( ! empty( $update_data['password'] ) ) {
						if ( $update_data['password'] === $update_data['password2'] ) {
							wp_update_user( array(
								'ID'        => $user_id,
								'user_pass' => esc_attr( $update_data['password'] )
							) );
						} else {
							qode_membership_ajax_response( 'error', esc_html__( 'Passwords don\'t match', 'qode-membership' ) );
						}
					}

					//Update email
					if ( ! empty( $update_data['email'] ) && filter_var( $update_data['email'], FILTER_VALIDATE_EMAIL ) ) {
						wp_update_user( array( 'ID' => $user_id, 'user_email' => esc_attr( $update_data['email'] ) ) );
					} else {
						qode_membership_ajax_response( 'error', esc_html__( 'Error. Please insert valid email', 'qode-membership' ) );
					}

					//Update Website
					wp_update_user( array( 'ID' => $user_id, 'user_url' => esc_url( $update_data['url'] ) ) );

					//Update user meta
					update_user_meta( $user_id, 'first_name', $update_data['first_name'] );
					update_user_meta( $user_id, 'last_name', $update_data['last_name'] );
					update_user_meta( $user_id, 'description', $update_data['description'] );

					qode_membership_ajax_response( 'success', esc_html__( 'Your profile is updated', 'qode-membership' ), $dashboard_url );

				} else {
					qode_membership_ajax_response( 'error', esc_html__( 'You are unauthorized to perform this action.', 'qode-membership' ) );
				}

			} else {
				qode_membership_ajax_response( 'error', esc_html__( 'Error.', 'qode-membership' ) );
			}
		}
	}

	add_action( 'wp_ajax_qode_membership_update_user_profile', 'qode_membership_update_user_profile' );
}

if (!function_exists('qode_tabs_over_title')){
    function qode_tabs_over_title($classes){

		if(qode_membership_theme_installed()) {
			if (qode_options()->getOptionValue('enable_navigation_over_title') == 'yes') {
				$classes[] = 'qode-membership-navigation-over-title';
			}

			return $classes;
		}
    }
    add_filter('body_class','qode_tabs_over_title');
}