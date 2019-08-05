<?php
$current_user    = wp_get_current_user();
$name            = $current_user->display_name;
$current_user_id = $current_user->ID;
?>
<div class="qode-logged-in-user">
    <div class="qode-logged-in-user-inner">
        <span class="qode-logged-in-user-icon">
            <?php if ( qode_membership_theme_installed() ) {
                echo qode_icon_collections()->renderIconHTML( 'dripicons-user', 'dripicons' );
            } ?>
        </span>
    </div>
</div>
<ul class="qode-login-dropdown">
	<?php
	$nav_items = qode_membership_get_dashboard_navigation_items();
	foreach ( $nav_items as $nav_item ) { ?>
		<li>
			<a href="<?php echo $nav_item['url']; ?>">
				<?php echo $nav_item['text']; ?>
			</a>
		</li>
	<?php } ?>
	<li>
		<a href="<?php echo wp_logout_url( home_url( '/' ) ); ?>">
			<?php esc_html_e( 'Log Out', 'qode-membership' ); ?>
		</a>
	</li>
</ul>