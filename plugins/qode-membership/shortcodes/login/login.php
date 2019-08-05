<?php
namespace QodeMembership\Shortcodes\QodeUserLogin;

use QodeMembership\Lib\ShortcodeInterface;

/**
 * Class QodeUserLogin
 */
class QodeUserLogin implements ShortcodeInterface {
	/**
	 * @var string
	 */
	private $base;

	public function __construct() {
		$this->base = 'qode_user_login';

		add_action('vc_before_init', array($this, 'vcMap'));
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
	 * @return string
	 */
	public function render($atts, $content = null) {
		$args = array(
		);

		$params = shortcode_atts($args, $atts);
		extract($params);

		$html = qode_membership_get_shortcode_template_part( 'login', 'login-template', '', $params);

		return $html;
	}

}