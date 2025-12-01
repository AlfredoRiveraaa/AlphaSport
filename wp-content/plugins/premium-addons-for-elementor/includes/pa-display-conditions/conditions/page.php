<?php
/**
 * Page Condition Handler.
 */

namespace PremiumAddons\Includes\PA_Display_Conditions\Conditions;

// Elementor Classes.
use Elementor\Controls_Manager;

// PA Classes.
use PremiumAddons\Includes\Helper_Functions;
use PremiumAddons\Includes\Controls\Premium_Post_Filter;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Page
 */
class Page extends Condition {

	/**
	 * Get Controls Options.
	 *
	 * @access public
	 * @since 4.7.0
	 *
	 * @return array|void  controls options
	 */
	public function get_control_options() {

		return array(
			'label'       => __( 'Value', 'premium-addons-pro' ),
			'type'        => Premium_Post_Filter::TYPE,
			'label_block' => true,
			'multiple'    => true,
			'source'      => 'page',
			'condition'   => array(
				'pa_condition_key' => 'page',
			),
		);
	}

	/**
	 * Compare Condition Value.
	 *
	 * @access public
	 * @since 4.7.0
	 *
	 * @param array       $settings       element settings.
	 * @param string      $operator       condition operator.
	 * @param string      $value          condition value.
	 * @param string      $compare_val    compare value.
	 * @param string|bool $tz        time zone.
	 *
	 * @return bool|void
	 */
	public function compare_value( $settings, $operator, $value, $compare_val, $tz ) {

		$current_id = Helper_Functions::pa_get_current_page_id();

		if ( is_array( $value ) && ! empty( $value ) ) {
			foreach ( $value as $index => $page_id ) {

				if ( intval( $page_id ) === $current_id ) {

					if ( 'is' === $operator ) {

						return Helper_Functions::get_final_result( true, $operator );
					}
				}
			}
		}

		return false;
	}
}
