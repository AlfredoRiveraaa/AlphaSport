<?php
/**
 * Class: Module
 * Name: Wrapper Link
 * Slug: premium-wrapper-link
 */

namespace PremiumAddons\Modules\PremiumWrapperLink;

// Elementor Classes.
use Elementor\Controls_Manager;

// Premium Addons Classes.
use PremiumAddons\Admin\Includes\Admin_Helper;
use PremiumAddons\Includes\Helper_Functions;
use PremiumAddons\Includes\Controls\Premium_Post_Filter;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // If this file is called directly, abort.
}

/**
 * Class Module For Premium Wrapper Link Addon.
 */
class Module {

	/**
	 * Load Script
	 *
	 * @var $load_script
	 */
	private static $load_script = null;

	/**
	 * Class object
	 *
	 * @var instance
	 */
	private static $instance = null;

	/**
	 * Class Constructor Function.
	 */
	public function __construct() {

		// Enqueue the required JS file.
		add_action( 'elementor/preview/enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// Creates Premium Wrapper Link tab at the end of layout/content tab.
		add_action( 'elementor/element/section/section_layout/after_section_end', array( $this, 'register_controls' ) );
		add_action( 'elementor/element/column/section_advanced/after_section_end', array( $this, 'register_controls' ) );
		add_action( 'elementor/element/common/_section_style/after_section_end', array( $this, 'register_controls' ) );

		add_action( 'elementor/frontend/before_render', array( $this, 'check_script_enqueue' ) );

		add_action( 'elementor/element/container/section_layout/after_section_end', array( $this, 'register_controls' ) );
		add_action( 'elementor/frontend/before_render', array( $this, 'before_render' ), 100 );
	}

	/**
	 * Register Global Tooltip controls.
	 *
	 * @since 1.0.0
	 * @access public
	 * @param object $element for current element.
	 */
	public function register_controls( $element ) {

		$tabs = Controls_Manager::TAB_CONTENT;

		if ( 'section' === $element->get_name() || 'column' === $element->get_name() || 'container' === $element->get_name() ) {
			$tabs = Controls_Manager::TAB_LAYOUT;
		}

		$element->start_controls_section(
			'section_premium_wrapper_link',
			array(
				'label' => sprintf( '<i class="pa-extension-icon pa-dash-icon"></i> %s', __( 'Wrapper Link', 'premium-addons-for-elementor' ) ),
				'tab'   => $tabs,
			)
		);

		$element->add_control(
			'premium_wrapper_link_switcher',
			array(
				'label'        => __( 'Enable Wrapper Link', 'premium-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'prefix_class' => 'premium-wrapper-link-',
			)
		);

		$element->add_control(
			'wrapper_link_notice',
			array(
				'raw'             => __( 'Please note that Wrapper Link works on the frontend.', 'premium-addons-for-elementor' ),
				'type'            => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
				'condition'       => array(
					'premium_wrapper_link_switcher' => 'yes',
				),
			)
		);

		$element->add_control(
			'premium_wrapper_link_selection',
			array(
				'label'       => __( 'Link Type', 'premium-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					'url'  => __( 'URL', 'premium-addons-for-elementor' ),
					'link' => __( 'Existing Page', 'premium-addons-for-elementor' ),
				),
				'default'     => 'url',
				'label_block' => true,
				'condition'   => array(
					'premium_wrapper_link_switcher' => 'yes',
				),
			)
		);

		$element->add_control(
			'premium_wrapper_link',
			array(
				'label'       => __( 'Link', 'premium-addons-for-elementor' ),
				'type'        => Controls_Manager::URL,
				'dynamic'     => array(
					'active' => true,
				),
				'placeholder' => 'https://example.com',
				'condition'   => array(
					'premium_wrapper_link_switcher'  => 'yes',
					'premium_wrapper_link_selection' => 'url',
				),
			)
		);

		$element->add_control(
			'premium_wrapper_existing_link',
			array(
				'label'       => __( 'Existing Page', 'premium-addons-for-elementor' ),
				'type'        => Premium_Post_Filter::TYPE,
				'label_block' => true,
				'multiple'    => false,
				'source'      => array( 'post', 'page' ),
				'condition'   => array(
					'premium_wrapper_link_switcher'  => 'yes',
					'premium_wrapper_link_selection' => 'link',
				),
			)
		);

		$element->end_controls_section();
	}


	/**
	 * Render Wrapper Link output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access public
	 * @param object $element for current element.
	 */
	public function before_render( $element ) {

		$settings = $element->get_settings_for_display();

		if ( 'yes' === $element->get_settings_for_display( 'premium_wrapper_link_switcher' ) ) {

			if ( 'link' === $settings['premium_wrapper_link_selection'] ) {
				$href = get_permalink( $settings['premium_wrapper_existing_link'] );
			} else {
				$href = $settings['premium_wrapper_link']['url'];
			}

			$link_settings = array(
				'type' => $settings['premium_wrapper_link_selection'],
				'link' => $settings['premium_wrapper_link'],
				'href' => esc_url( $href ),
			);

			if ( ! empty( $href ) ) {
				$element->add_render_attribute(
					'_wrapper',
					array(
						'data-premium-element-link' => wp_json_encode( $link_settings ),
						'style'                     => 'cursor: pointer',
					)
				);
			}
		}
	}

	/**
	 * Enqueue scripts.
	 *
	 * Registers required dependencies for the extension and enqueues them.
	 *
	 * @since 1.6.5
	 * @access public
	 */
	public function enqueue_scripts() {

		if ( ! wp_script_is( 'pa-wrapper-link', 'enqueued' ) ) {
			wp_enqueue_script( 'pa-wrapper-link' );
		}
	}

	/**
	 * Check Script Enqueue
	 *
	 * Check if the script files should be loaded.
	 *
	 * @since 4.7.7
	 * @access public
	 */
	public function check_script_enqueue( $element ) {

		if ( self::$load_script ) {
			return;
		}

		if ( 'yes' === $element->get_settings_for_display( 'premium_wrapper_link_switcher' ) ) {

			$this->enqueue_scripts();

			self::$load_script = true;

			remove_action( 'elementor/frontend/before_render', array( $this, 'check_script_enqueue' ) );

		}
	}

	/**
	 * Creates and returns an instance of the class
	 *
	 * @since 4.2.5
	 * @access public
	 *
	 * @return object
	 */
	public static function get_instance() {

		if ( ! isset( self::$instance ) ) {

			self::$instance = new self();

		}

		return self::$instance;
	}
}
