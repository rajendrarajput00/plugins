<?php

class QodeMembershipLoginRegister extends WP_Widget {
	protected $params;

	public function __construct() {
		parent::__construct(
			'qode_login_register_widget', // Base ID
			'Qode Login',
			array( 'description' => esc_html__( 'Login and register wordpress widget', 'qode-membership' ), )
		);
	}

	public function widget( $args, $instance ) {
		$additional_class = '';
		if(is_user_logged_in()){
			$additional_class .= 'qode-user-logged-in';
		}  else {
            $additional_class .= 'qode-user-not-logged-in';
        }

		echo '<div class="widget qode-login-register-widget '.$additional_class.'">';
		if ( ! is_user_logged_in() ) {
			echo '<a class="qode-login-opener" href="#">';
				if ( qode_membership_theme_installed() ) {
					echo qode_icon_collections()->renderIconHTML( 'dripicons-user', 'dripicons' );
				}
			echo '</a>';
			add_action( 'wp_footer', array( $this, 'qode_membership_render_login_form' ) );

		} else {
			echo qode_membership_get_widget_template_part( 'login-widget', 'login-widget-template' );
		}
		echo '</div>';

	}

	public function qode_membership_render_login_form() {

		//Render modal with login and register forms
		echo qode_membership_get_widget_template_part( 'login-widget', 'login-modal-template' );
	}
}

function qode_membership_login_widget_load() {
	register_widget( 'QodeMembershipLoginRegister' );
}

add_action( 'widgets_init', 'qode_membership_login_widget_load' );