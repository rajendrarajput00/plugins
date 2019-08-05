<div class="qode-membership-dashboard-page">
	<div>
		<form method="post" id="qode-membership-update-profile-form">
			<div class="qode-membership-profile-inputs-holder">
				<h5 class="qode-membership-holder-title"><?php esc_html_e( 'Edit Your Profile', 'qode-membership' ); ?></h5>
				<div class="qode-membership-row clearfix">
					<div class="qode-membership-column">
						<div class="qode-membership-input-holder">
							<label for="first_name"><?php esc_html_e( 'First Name', 'qode-membership' ); ?></label>
							<input class="qode-membership-input" type="text" name="first_name" id="first_name"
							       value="<?php echo $first_name; ?>">
						</div>
					</div>
					<div class="qode-membership-column">
						<div class="qode-membership-input-holder">
							<label for="last_name"><?php esc_html_e( 'Last Name', 'qode-membership' ); ?></label>
							<input class="qode-membership-input" type="text" name="last_name" id="last_name"
							       value="<?php echo $last_name; ?>">
						</div>
					</div>
				</div>
				<div class="qode-membership-row clearfix">
					<div class="qode-membership-column">
						<div class="qode-membership-input-holder">
							<label for="email"><?php esc_html_e( 'Email', 'qode-membership' ); ?></label>
							<input class="qode-membership-input" type="email" name="email" id="email"
							       value="<?php echo $email; ?>">
						</div>
					</div>
					<div class="qode-membership-column">
						<div class="qode-membership-input-holder">
							<label for="url"><?php esc_html_e( 'Website', 'qode-membership' ); ?></label>
							<input class="qode-membership-input" type="text" name="url" id="url" value="<?php echo $website; ?>">
						</div>
					</div>
				</div>
				<div class="qode-membership-row clearfix">
					<div class="qode-membership-column">
						<div class="qode-membership-input-holder">
							<label for="description"><?php esc_html_e( 'Description', 'qode-membership' ); ?></label>
							<input class="qode-membership-input" type="text" name="description" id="description"
							       value="<?php echo $description; ?>">
						</div>
					</div>
					<div class="qode-membership-column">
					</div>
				</div>
			</div>
			<div class="qode-membership-password-inputs-holder">
				<h5 class="qode-membership-holder-title"><?php esc_html_e( 'Manage Your Password', 'qode-membership' ); ?></h5>
				<div class="qode-membership-row clearfix">
					<div class="qode-membership-column">
						<div class="qode-membership-input-holder">
							<label for="password"><?php esc_html_e( 'Password', 'qode-membership' ); ?></label>
							<input class="qode-membership-input" type="password" name="password" id="password" value="">
						</div>
					</div>
					<div class="qode-membership-column">
						<div class="qode-membership-input-holder">
							<label for="password2"><?php esc_html_e( 'Repeat Password', 'qode-membership' ); ?></label>
							<input class="qode-membership-input" type="password" name="password2" id="password2" value="">
						</div>
					</div>
				</div>
			</div>
			<?php
			if ( qode_membership_theme_installed() ) {
				echo qode_get_button_html( array(

                        'text'  => esc_html__( 'UPDATE PROFILE', 'qode-membership' ),
                        'html_type' => 'button',                      
                        'custom_attrs' => array(
							'data-updating-text' => esc_html__('UPDATING PROFILE', 'qode-membership'),
							'data-updated-text' => esc_html__('PROFILE UPDATED', 'qode-membership')
					)                        

                    )   );
			} else {
				echo '<button type="submit">' . esc_html__( 'UPDATE PROFILE', 'qode-membership' ) . '</button>';
			}
			wp_nonce_field( 'qode_validate_edit_profile', 'qode_nonce_edit_profile' )
			?>
		</form>
		<?php
		do_action( 'qode_membership_action_login_ajax_response' );
		?>
	</div>
</div>