<?php
/**
 * Returning User Condition Handler.
 */

namespace PremiumAddons\Includes\PA_Display_Conditions\Conditions;

// Elementor Classes.
use Elementor\Controls_Manager;

// PA Classes.
use PremiumAddons\Includes\Helper_Functions;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Return_Visitor
 */
class Return_Visitor extends Condition {

	/**
	 * Get Controls Options.
	 *
	 * @access public
	 * @since 4.9.21
	 *
	 * @return array|void  controls options
	 */
	public function get_control_options() {

		return array(
			'label'       => __( 'Value', 'premium-addons-for-elementor' ),
			'type'        => Controls_Manager::SELECT,
			'options'     => array(
				'return' => __( 'Returning User', 'premium-addons-for-elementor' ),
			),
			'default'     => 'return',
			'label_block' => true,
			'condition'   => array(
				'pa_condition_key' => 'return_visitor',
			),
		);
	}

	/**
	 * Compare Condition Value.
	 *
	 * @access public
	 * @since 4.9.21
	 *
	 * @param array       $settings      element settings.
	 * @param string      $operator      condition operator.
	 * @param string      $value         condition value.
	 * @param string      $compare_val   condition value.
	 * @param string|bool $tz        time zone.

	 * @return bool|void
	 */
	public function compare_value( $settings, $operator, $value, $compare_val, $tz ) {

		if ( ! isset( $_COOKIE['PaVisitorData'] ) && ! isset( $_COOKIE['PaNewVisitor'] ) ) {

			wp_add_inline_script(
				'elementor-frontend',
				'jQuery( window ).on( "elementor/frontend/init", function() {

					var currentTime = new Date().getTime();

					var paSecure = ( document.location.protocol === "https:" ) ? "secure" : "";
					var visitDate = new Date( currentTime + 1000 * 86400 * 365 ).toGMTString();
					var visitDateExpire = new Date( currentTime + 86400 * 1000 ).toGMTString();

					document.cookie = "PaVisitorData=enabled;expires=" + visitDate + "SameSite=Strict;" + paSecure;
					document.cookie = "PaNewVisitor=enabled;expires=" + visitDateExpire + "SameSite=Strict;" + paSecure;

				}); '
			);

		}

		$condition_result = isset( $_COOKIE['PaVisitorData'] ) && isset( $_COOKIE['PaNewVisitor'] );

		return Helper_Functions::get_final_result( $condition_result, $operator );
	}
}
