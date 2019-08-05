<div class="qode-social-login-holder">
    <div class="qode-social-login-holder-inner">
        <form method="post" class="qode-login-form">
            <?php
            $redirect = '';
            if ( isset( $_GET['redirect_uri'] ) ) {
                $redirect = $_GET['redirect_uri'];
            } ?>
            <fieldset>
                <div>
	                <label for="user_login_name" class="qode-membership-lr-label"><?php esc_html_e( 'User Name', 'qode-membership' ) ?></label>
                    <input type="text" name="user_login_name" id="user_login_name" placeholder="<?php esc_html_e( 'User Name', 'qode-membership' ) ?>" value="" required pattern=".{3,}" title="<?php esc_html_e( 'Three or more characters', 'qode-membership' ); ?>"/>
                </div>
                <div>
	                <label for="user_login_password" class="qode-membership-lr-label"><?php esc_html_e( 'Password', 'qode-membership' ) ?></label>
                    <input type="password" name="user_login_password" id="user_login_password" placeholder="<?php esc_html_e( 'Password', 'qode-membership' ) ?>" value="" required/>
                </div>
                <div class="qode-lost-pass-remember-holder clearfix">
                    <span class="qode-login-remember">
                        <input name="rememberme" value="forever" id="rememberme" type="checkbox"/>
                        <label for="rememberme" class="qode-checbox-label"><?php esc_html_e( 'Remember me', 'qode-membership' ) ?></label>
                    </span>
	                <a href="<?php echo wp_lostpassword_url(); ?>" class="qode-login-action-btn" data-el="#qode-reset-pass-content" data-title="<?php esc_html_e( 'Forgot Password?', 'qode-membership' ); ?>"><?php esc_html_e( 'Forgot password?', 'qode-membership' ); ?></a>
                </div>
                <input type="hidden" name="redirect" id="redirect" value="<?php echo esc_url( $redirect ); ?>">
                <div class="qode-login-button-holder">
                    <?php
                    if ( qode_membership_theme_installed() ) {
                        echo qode_get_button_html( array(
                            'html_type'     => 'button',
                            'text'          => esc_html__( 'Log In', 'qode-membership' ),
                            'custom_class'  => 'qode-qbutton-full-width qode-membership-button'
                        ) );
                    } else {
                        echo '<button type="submit">' . esc_html__( 'LOGIN', 'qode-membership' ) . '</button>';
                    }
                    ?>
                    <?php wp_nonce_field( 'qode-ajax-login-nonce', 'qode-login-security' ); ?>
                </div>
            </fieldset>
        </form>
    </div>
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
</div>