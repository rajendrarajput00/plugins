<?php

if(!function_exists('qode_membership_add_reset_password_shortcodes')) {
    function qode_membership_add_reset_password_shortcodes($shortcodes_class_name) {
        $shortcodes = array(
            'QodeMembership\Shortcodes\QodeUserResetPassword\QodeUserResetPassword'
        );

        $shortcodes_class_name = array_merge($shortcodes_class_name, $shortcodes);

        return $shortcodes_class_name;
    }

    add_filter('qode_membership_filter_add_vc_shortcode', 'qode_membership_add_reset_password_shortcodes');
}