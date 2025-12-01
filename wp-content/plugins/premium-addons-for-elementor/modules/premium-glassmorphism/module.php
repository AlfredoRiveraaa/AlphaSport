<?php
/**
 * Class: Module
 * Name: Premium Glassmorphism
 * Slug: premium-glassmorphism
 */

namespace PremiumAddons\Modules\PremiumGlassmorphism;

// Elementor Classes.
use Elementor\Utils;
use Elementor\Icons_Manager;
use Elementor\Control_Media;
use Elementor\Controls_Manager;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // If this file is called directly, abort.
}

/**
 * Class Module For Premium Global Tooltips Addon.
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
		add_action( 'elementor/preview/enqueue_styles', array( $this, 'enqueue_styles' ) );

		add_action( 'elementor/element/section/section_advanced/after_section_end', array( $this, 'register_controls' ), 10 );
		add_action( 'elementor/element/column/section_advanced/after_section_end', array( $this, 'register_controls' ), 10 );
		add_action( 'elementor/element/common/_section_style/after_section_end', array( $this, 'register_controls' ), 10 );

		// Check if scripts should be loaded.
		add_action( 'elementor/frontend/before_render', array( $this, 'check_script_enqueue' ) );

		add_action( 'elementor/element/container/section_layout/after_section_end', array( $this, 'register_controls' ), 10 );
	}

	/**
	 * Enqueue scripts.
	 *
	 * Registers required dependencies for the extension and enqueues them.
	 *
	 * @since 4.11.14
	 * @access public
	 */
	public function enqueue_scripts() {

		if ( ! wp_script_is( 'pa-glass', 'enqueued' ) ) {
			wp_enqueue_script( 'pa-glass' );
		}
	}

	/**
	 * Enqueue styles.
	 *
	 * Registers required dependencies for the extension and enqueues them.
	 *
	 * @since 4.11.14
	 * @access public
	 */
	public function enqueue_styles() {

		if ( ! wp_style_is( 'pa-glass', 'enqueued' ) ) {
			wp_enqueue_style( 'pa-glass' );
		}
	}


	/**
	 * Register Glass Morphism controls.
	 *
	 * @since 1.0.0
	 * @access public
	 * @param object $element for current element.
	 */
	public function register_controls( $element ) {

		$element->start_controls_section(
			'premium_glass_effect',
			array(
				'label' => sprintf( '<i class="pa-extension-icon pa-dash-icon"></i> %s', __( 'Liquid Glass', 'premium-addons-for-elementor' ) ),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			)
		);

		$element->add_control(
			'premium_glass_switcher',
			array(
				'label'        => __( 'Enable Liquid Glass', 'premium-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
			)
		);

		$element->add_control(
			'lq_effect',
			array(
				'label'        => __( 'Liquid Glass Effect', 'premium-addons-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'description'  => sprintf(
					/* translators: 1: `<a>` opening tag, 2: `</a>` closing tag. */
					esc_html__( 'Important: Make sure this element has a semi-transparent background color to see the effect. See all presets from %1$shere%2$s.', 'premium-addons-for-elementor' ),
					'<a href="https://premiumaddons.com/liquid-glass/" target="_blank">',
					'</a>'
				),
				'options'      => array(
					'glass1' => __( 'Preset 01', 'premium-addons-for-elementor' ),
					'glass2' => __( 'Preset 02', 'premium-addons-for-elementor' ),
					'glass3' => apply_filters( 'pa_pro_label', __( 'Preset 03 (Pro)', 'premium-addons-for-elementor' ) ),
					'glass4' => apply_filters( 'pa_pro_label', __( 'Preset 04 (Pro)', 'premium-addons-for-elementor' ) ),
					'glass5' => apply_filters( 'pa_pro_label', __( 'Preset 05 (Pro)', 'premium-addons-for-elementor' ) ),
					'glass6' => apply_filters( 'pa_pro_label', __( 'Preset 06 (Pro)', 'premium-addons-for-elementor' ) ),
				),
				'prefix_class' => 'premium-con-lq__',
				'default'      => 'glass1',
				'label_block'  => true,
				'render_type'  => 'template',
				'condition'    => array(
					'premium_glass_switcher' => 'yes',
				),
			)
		);

		$element->add_control(
			'glass_shadow',
			array(
				'label'        => __( 'Shadow Effect', 'premium-addons-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => array(
					'none'    => __( 'None', 'premium-addons-for-elementor' ),
					'shadow1' => __( 'Shadow 01', 'premium-addons-for-elementor' ),
					'shadow2' => __( 'Shadow 02', 'premium-addons-for-elementor' ),
					'shadow3' => __( 'Shadow 03', 'premium-addons-for-elementor' ),
					'shadow4' => __( 'Shadow 04', 'premium-addons-for-elementor' ),
					'shadow5' => __( 'Shadow 05', 'premium-addons-for-elementor' ),
					'shadow6' => __( 'Shadow 06', 'premium-addons-for-elementor' ),
					'shadow7' => __( 'Shadow 07', 'premium-addons-for-elementor' ),
					'shadow8' => __( 'Shadow 08', 'premium-addons-for-elementor' ),
				),
				'prefix_class' => 'premium-lq__',
				'default'      => 'none',
				'label_block'  => true,
				'render_type'  => 'template',
				'condition'    => array(
					'premium_glass_switcher' => 'yes',
				),
			)
		);

		$element->end_controls_section();
	}

	/**
	 * Check Script Enqueue
	 *
	 * Check if the script files should be loaded.
	 *
	 * @since 4.7.7
	 * @access public
	 *
	 * @param object $element for current element.
	 */
	public function check_script_enqueue( $element ) {

		if ( self::$load_script ) {
			return;
		}

		if ( 'yes' === $element->get_settings_for_display( 'premium_glass_switcher' ) ) {
			$this->enqueue_styles();
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
