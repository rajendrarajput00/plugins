<div class="qode-social-register-holder">
	<form method="post" class="qode-register-form">
		<fieldset>
			<div>
				<label for="user_register_name" class="qode-membership-lr-label"><?php esc_html_e( 'User Name', 'qode-membership' ) ?></label>
				<input type="text" name="user_register_name" id="user_register_name" placeholder="<?php esc_html_e( 'User Name', 'qode-membership' ) ?>" value="" required
				       pattern=".{3,}" title="<?php esc_html_e( 'Three or more characters', 'qode-membership' ); ?>"/>
			</div>
			<div>
				<label for="user_register_email" class="qode-membership-lr-label"><?php esc_html_e( 'Email', 'qode-membership' ) ?></label>
				<input type="email" name="user_register_email" id="user_register_email" placeholder="<?php esc_html_e( 'Email', 'qode-membership' ) ?>" value="" required />
			</div>
            <div>
	            <label for="user_register_password" class="qode-membership-lr-label"><?php esc_html_e( 'Password', 'qode-membership' ) ?></label>
                <input type="password" name="user_register_password" id="user_register_password" placeholder="<?php esc_html_e('Password','qode-membership') ?>" value="" required />
            </div>
            <div>
	            <label for="user_register_confirm_password" class="qode-membership-lr-label"><?php esc_html_e( 'Repeat Password', 'qode-membership' ) ?></label>
                <input type="password" name="user_register_confirm_password" id="user_register_confirm_password" placeholder="<?php esc_html_e('Repeat Password','qode-membership') ?>" value="" required />
            </div>
			<div class="qode-register-button-holder">
				<?php
				if ( qode_membership_theme_installed() ) {
					echo qode_get_button_html( array(
						'html_type'     => 'button',
						'text'          => esc_html__( 'Register', 'qode-membership' ),
						'custom_class'  => 'qode-qbutton-full-width qode-membership-button'
					) );
				} else {
					echo '<button type="submit">' . esc_html__( 'Register', 'qode-membership' ) . '</button>';
				}
				wp_nonce_field( 'qode-ajax-register-nonce', 'qode-register-security' ); ?>
			</div>
		</fieldset>
	</form>
	<?php
	if(qode_membership_theme_installed()) {
		//if social login enabled add social networks login
		$social_login_enabled = qode_options()->getOptionValue('enable_social_login') == 'yes' ? true : false;
		if($social_login_enabled) { ?>
			<div class="qode-login-form-social-login">
				<div class="qode-login-social-title">
					<?php esc_html_e('Connect with Social Networks', 'qode-membership'); ?>
				</div>
				<div class="qode-login-social-networks">
					<?php do_action('qode_membership_social_network_login'); ?>
				</div>
			</div>
		<?php }
	}
	do_action( 'qode_membership_action_login_ajax_response' );
	?>
	<?php $qode_terms_link = qode_options()->getOptionValue('membership_item_terms_link');	?>
	<?php if($qode_terms_link != '') : ?>
		<div class="qode-membership-lr-terms-holder">
	        <span>
				<?php esc_html_e('By creating an account you are accepting our', 'qode'); ?> <a href="<?php echo wp_kses_post($qode_terms_link)?>"><?php esc_html_e('Terms & Conditions', 'qode'); ?></a>.
	        </span>
		</div>
	<?php endif; ?>
</div>