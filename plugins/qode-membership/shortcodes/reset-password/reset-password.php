<?php
namespace QodeMembership\Shortcodes\QodeUserResetPassword;

use QodeMembership\Lib\ShortcodeInterface;
/**
 * Class QodeUserResetPassword
 */
class QodeUserResetPassword implements ShortcodeInterface {
	/**
	 * @var string
	 */
	private $base;

	public function __construct() {
		$this->base = 'qode_user_reset_password';

		add_action( 'vc_before_init', array( $this, 'vcMap' ) );
	}

	/**
	 * Returns base for shortcode
	 * @return string
	 */
	public function getBase() {
		return $this->base;
	}

	/**
	 * Maps shortcode to Visual Composer
	 *
	 * @see vc_map
	 */
	public function vcMap() {
	}

	/**
	 * Renders shortcodes HTML
	 *
	 * @param $atts array of shortcode params
	 * @param $content string shortcode content
	 *
	 * @return string
	 */
	public function render( $atts, $content = null ) {

		$args = array();

		$params = shortcode_atts( $args, $atts );
		extract( $params );

		$html = qode_membership_get_shortcode_template_part( 'reset-password', 'reset-password-template', '', $params );

		return $html;
	}

}