<div class="qode-social-reset-password-holder">
	<form action="<?php echo site_url( 'wp-login.php?action=lostpassword' ); ?>" method="post" id="qode-lost-password-form" class="qode-reset-pass-form">
		<div>
			<input type="text" name="user_reset_password_login" class="qode-input-field" id="user_reset_password_login" placeholder="<?php esc_html_e( 'Enter username or email', 'qode-membership' ) ?>" value="" size="20" required>
		</div>
		<?php do_action( 'lostpassword_form' ); ?>
		<div class="qode-reset-password-button-holder">
			<?php
			if ( qode_membership_theme_installed() ) {
				echo qode_get_button_v2_html( array(
					'html_type' => 'button',
					'text'      => esc_html__( 'NEW PASSWORD', 'qode-membership' ),
					'type'      => 'solid',
					'size'      => 'small'
				) );
			} else {
				echo '<button type="submit">' . esc_html__( 'NEW PASSWORD', 'qode-membership' ) . '</button>';
			}
			?>
		</div>
	</form>
	<?php do_action( 'qode_membership_action_login_ajax_response' ); ?>
</div>