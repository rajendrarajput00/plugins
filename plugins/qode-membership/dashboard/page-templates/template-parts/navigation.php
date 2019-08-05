<ul class="qode-membership-dashboard-nav clearfix">
	<?php
	$nav_items = qode_membership_get_dashboard_navigation_items();
	$user_action = isset($_GET['user-action']) ? $_GET['user-action'] : 'profile';
	foreach ( $nav_items as $nav_item ) { ?>
		<li <?php if($user_action == $nav_item['user_action']){ echo 'class="qode-active-dash"'; } ?>>
			<a href="<?php echo $nav_item['url']; ?>">
				<?php if(isset($nav_item['icon'])){ ?>
					<span class="qode-dash-icon">
						<?php print $nav_item['icon']; ?>
					</span>
				<?php } ?>
				<span class="qode-dash-label">
				    <?php echo $nav_item['text']; ?>
                </span>
			</a>
		</li>
	<?php } ?>
	<li>
		<a href="<?php echo wp_logout_url( home_url( '/' ) ); ?>">
			 <span class="qode-dash-icon">
                '<span class="icon dripicons-exit"></span>
            </span>
			<span class="qode-dash-label">
				<?php esc_html_e( 'Log out', 'qode-membership' ); ?>
			</span>
		</a>
	</li>
</ul>