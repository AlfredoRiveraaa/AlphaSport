<?php
/**
 * Class: Premium_Icon_list
 * Name: Bullet List
 * Slug: premium-addon-icon-list
 */

namespace PremiumAddons\Widgets;

// Elementor Classes.
use Elementor\Plugin;
use Elementor\Icons_Manager;
use Elementor\Control_Media;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;


// PremiumAddons Classes.
use PremiumAddons\Admin\Includes\Admin_Helper;
use PremiumAddons\Includes\Helper_Functions;
use PremiumAddons\Includes\Controls\Premium_Post_Filter;
use PremiumAddons\Includes\Controls\Premium_Background;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // If this file is called directly, abort.
}

/**
 * Class Premium_Icon_List
 */
class Premium_Icon_List extends Widget_Base {

	/**
	 * Check if the icon draw is enabled.
	 *
	 * @since 4.9.26
	 * @access private
	 *
	 * @var bool
	 */
	private $is_draw_enabled = null;

	/**
	 * Check Icon Draw Option.
	 *
	 * @since 4.9.26
	 * @access public
	 */
	public function check_icon_draw() {

		if ( null === $this->is_draw_enabled ) {
			$this->is_draw_enabled = Admin_Helper::check_svg_draw( 'premium-icon-list' );
		}

		return $this->is_draw_enabled;
	}

	/**
	 * Retrieve Widget Name.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function get_name() {
		return 'premium-icon-list';
	}

	/**
	 * Retrieve Widget Title.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function get_title() {
		return __( 'Bullet List', 'premium-addons-for-elementor' );
	}

	/**
	 * Retrieve Widget Dependent CSS.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array CSS style handles.
	 */
	public function get_style_depends() {
		return array(
			'pa-glass',
			'premium-addons',
		);
	}

	/**
	 * Retrieve Widget Dependent JS.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array JS script handles.
	 */
	public function get_script_depends() {

		$is_edit = Helper_Functions::is_edit_mode();

		$scripts = array();

		if ( $is_edit ) {

			$draw_scripts = $this->check_icon_draw() ? array( 'pa-tweenmax', 'pa-motionpath' ) : array();

			$scripts = array_merge( $draw_scripts, array( 'lottie-js', 'pa-glass' ) );

		} else {
			$settings = $this->get_settings();

			if ( ! empty( $settings['list'] ) ) {
				foreach ( $settings['list'] as $item ) {
					if ( 'yes' === $item['draw_svg'] ) {
						array_push( $scripts, 'pa-tweenmax', 'pa-motionpath' );

						$draw_js = true;
					}

					if ( 'lottie' === $item['icon_type'] ) {
						$scripts[] = 'lottie-js';

						$lottie_js = true;
					}

					if ( isset( $draw_js ) && isset( $lottie_js ) ) {
						break;
					}
				}
			}

			if ( 'none' !== $settings['items_lq_effect'] ) {
				$scripts[] = 'pa-glass';
			}
		}

		$scripts[] = 'premium-addons';

		return $scripts;
	}

	/**
	 * Retrieve Widget Icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string widget icon.
	 */
	public function get_icon() {
		return 'pa-icon-list';
	}

	/**
	 * Retrieve Widget Categories.
	 *
	 * @since 1.5.1
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'premium-elements' );
	}

	/**
	 * Retrieve Widget Keywords.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget keywords.
	 */
	public function get_keywords() {
		return array( 'pa', 'premium', 'premium bullet list', 'icon', 'feature', 'list' );
	}

	protected function is_dynamic_content(): bool {
		return false;
	}

	/**
	 * Retrieve Widget Support URL.
	 *
	 * @access public
	 *
	 * @return string support URL.
	 */
	public function get_custom_help_url() {
		return 'https://premiumaddons.com/support/';
	}

	public function has_widget_inner_wrapper(): bool {
		return ! Helper_Functions::check_elementor_experiment( 'e_optimized_markup' );
	}

	/**
	 * Register Bullet List controls.
	 *
	 * @since 4.0.0
	 * @access protected
	 */
	public function register_controls() { // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore

		$draw_icon = $this->check_icon_draw();

		$this->add_list_items_controls( $draw_icon );

		$this->add_display_options_controls();

		$this->add_random_badge_controls();

		$this->add_help_section_controls();

		Helper_Functions::register_papro_promotion_controls( $this, 'bullet' );

		$this->register_style_controls( $draw_icon );
	}

	/**
	 * Register Style Control
	 *
	 * @since 4.0.0
	 * @access private
	 */
	private function register_style_controls( $draw_icon ) {

		$this->add_general_style_controls();
		$this->add_bullet_style_controls( $draw_icon );
		$this->add_title_style_controls();
		$this->add_desc_style_controls();
		$this->add_badge_style_controls();
		$this->add_divider_style_controls();
		$this->add_connector_style_controls();
	}

	private function add_list_items_controls( $draw_icon ) {

		$this->start_controls_section(
			'list_items_section',
			array(
				'label' => __( 'List Items', 'premium-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$demo = Helper_Functions::get_campaign_link( 'https://premiumaddons.com/elementor-bullet-list-widget/', 'bullet', 'wp-editor', 'demo' );
		Helper_Functions::add_templates_controls( $this, 'bullet-list', $demo );

		$repeater_list = new REPEATER();

		$repeater_list->add_control(
			'list_title',
			array(
				'label'       => __( 'Title', 'premium-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'List Title', 'premium-addons-for-elementor' ),
				'dynamic'     => array( 'active' => true ),
				'label_block' => true,
			)
		);

		$repeater_list->add_control(
			'list_desc',
			array(
				'label'       => __( 'Description', 'premium-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'dynamic'     => array( 'active' => true ),
				'label_block' => true,
			)
		);

		$repeater_list->add_control(
			'show_icon',
			array(
				'label'        => __( 'Show Bullet', 'premium-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$common_conditions = array(
			'show_icon' => 'yes',
		);

		$repeater_list->add_control(
			'icon_type',
			array(
				'label'       => __( 'Bullet Type', 'premium-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'icon',
				'render_type' => 'template',
				'options'     => array(
					'icon'   => __( 'Icon', 'premium-addons-for-elementor' ),
					'image'  => __( 'Image', 'premium-addons-for-elementor' ),
					'lottie' => __( 'Lottie Animation', 'premium-addons-for-elementor' ),
					'text'   => __( 'Text', 'premium-addons-for-elementor' ),
					'svg'    => __( 'SVG Code', 'premium-addons-for-elementor' ),
				),
				'condition'   => $common_conditions,
			)
		);

		$repeater_list->add_control(
			'premium_icon_list_font_updated',
			array(
				'label'     => __( 'Icon', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => array(
					'value'   => 'fas fa-star',
					'library' => 'fa-solid',
				),
				'condition' => array_merge(
					$common_conditions,
					array(
						'icon_type' => 'icon',
					)
				),
			)
		);

		$repeater_list->add_control(
			'custom_image',
			array(
				'label'     => __( 'Custom Image', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::MEDIA,
				'dynamic'   => array( 'active' => true ),
				'default'   => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'condition' => array_merge(
					$common_conditions,
					array(
						'icon_type' => 'image',
					)
				),
			)
		);

		$repeater_list->add_control(
			'list_text_icon',
			array(
				'label'     => __( 'Text', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'New', 'premium-addons-for-elementor' ),
				'dynamic'   => array( 'active' => true ),
				'condition' => array_merge(
					$common_conditions,
					array(
						'icon_type' => 'text',
					)
				),
			)
		);

		$repeater_list->add_control(
			'custom_svg',
			array(
				'label'       => __( 'SVG Code', 'premium-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'description' => 'You can use these sites to create SVGs: <a href="https://danmarshall.github.io/google-font-to-svg-path/" target="_blank">Google Fonts</a> and <a href="https://boxy-svg.com/" target="_blank">Boxy SVG</a>',
				'condition'   => array_merge(
					$common_conditions,
					array(
						'icon_type' => 'svg',
					)
				),
			)
		);

		$repeater_list->add_control(
			'lottie_url',
			array(
				'label'       => __( 'Animation JSON URL', 'premium-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array( 'active' => true ),
				'description' => 'Get JSON code URL from <a href="https://lottiefiles.com/" target="_blank">here</a>',
				'label_block' => true,
				'render_type' => 'template',
				'condition'   => array(
					'show_icon' => 'yes',
					'icon_type' => 'lottie',
				),
			)
		);

		$repeater_list->add_control(
			'draw_svg',
			array(
				'label'       => __( 'Draw Icon', 'premium-addons-for-elementor' ),
				'type'        => Controls_Manager::SWITCHER,
				'description' => __( 'Enable this option to make the icon drawable. See ', 'premium-addons-for-elementor' ) . '<a href="https://www.youtube.com/watch?v=ZLr0bRe0RAY" target="_blank">tutorial</a>',
				'classes'     => $draw_icon ? '' : 'editor-pa-control-disabled',
				'condition'   => array_merge(
					$common_conditions,
					array(
						'icon_type' => array( 'icon', 'svg' ),
						'premium_icon_list_font_updated[library]!' => 'svg',
					)
				),
			)
		);

		$animation_conds = array(
			'terms' => array(
				array(
					'name'  => 'show_icon',
					'value' => 'yes',
				),
				array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'  => 'icon_type',
							'value' => 'lottie',
						),
						array(
							'terms' => array(
								array(
									'relation' => 'or',
									'terms'    => array(
										array(
											'name'  => 'icon_type',
											'value' => 'icon',
										),
										array(
											'name'  => 'icon_type',
											'value' => 'svg',
										),
									),
								),
								array(
									'name'  => 'draw_svg',
									'value' => 'yes',
								),
							),
						),
					),
				),
			),
		);

		if ( $draw_icon ) {
			$repeater_list->add_control(
				'path_width',
				array(
					'label'     => __( 'Path Thickness', 'premium-addons-for-elementor' ),
					'type'      => Controls_Manager::SLIDER,
					'range'     => array(
						'px' => array(
							'min'  => 0,
							'max'  => 50,
							'step' => 0.1,
						),
					),
					'condition' => array_merge(
						$common_conditions,
						array(
							'icon_type' => array( 'icon', 'svg' ),
						)
					),
					'selectors' => array(
						'{{WRAPPER}} {{CURRENT_ITEM}} .premium-bullet-list-wrapper svg *' => 'stroke-width: {{SIZE}}',
					),
				)
			);

			$repeater_list->add_control(
				'svg_sync',
				array(
					'label'     => __( 'Draw All Paths Together', 'premium-addons-for-elementor' ),
					'type'      => Controls_Manager::SWITCHER,
					'condition' => array_merge(
						$common_conditions,
						array(
							'icon_type' => array( 'icon', 'svg' ),
							'draw_svg'  => 'yes',
						)
					),
				)
			);

			$repeater_list->add_control(
				'frames',
				array(
					'label'       => __( 'Speed', 'premium-addons-for-elementor' ),
					'type'        => Controls_Manager::NUMBER,
					'description' => __( 'Larger value means longer animation duration.', 'premium-addons-for-elementor' ),
					'default'     => 5,
					'min'         => 1,
					'max'         => 100,
					'condition'   => array_merge(
						$common_conditions,
						array(
							'icon_type' => array( 'icon', 'svg' ),
							'draw_svg'  => 'yes',
						)
					),
				)
			);

		} else {

			Helper_Functions::get_draw_svg_notice(
				$repeater_list,
				'bullet',
				array_merge(
					$common_conditions,
					array(
						'icon_type' => array( 'icon', 'svg' ),
						'premium_icon_list_font_updated[library]!' => 'svg',
					)
				)
			);

		}

		$repeater_list->add_control(
			'lottie_loop',
			array(
				'label'        => __( 'Loop', 'premium-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'default'      => 'true',
				'render_type'  => 'template',
				'conditions'   => $animation_conds,
			)
		);

		if ( $draw_icon ) {
			$repeater_list->add_control(
				'svg_notice',
				array(
					'raw'             => __( 'Loop and Speed options are overridden when Draw SVGs in Sequence option is enabled.', 'premium-addons-for-elementor' ),
					'type'            => Controls_Manager::RAW_HTML,
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
					'condition'       => array_merge(
						$common_conditions,
						array(
							'icon_type' => array( 'icon', 'svg' ),
							'draw_svg'  => 'yes',
						)
					),
				)
			);
		}

		$repeater_list->add_control(
			'lottie_reverse',
			array(
				'label'        => __( 'Reverse', 'premium-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'render_type'  => 'template',
				'conditions'   => $animation_conds,
			)
		);

		if ( $draw_icon ) {
			$repeater_list->add_control(
				'start_point',
				array(
					'label'       => __( 'Start Point (%)', 'premium-addons-for-elementor' ),
					'type'        => Controls_Manager::SLIDER,
					'description' => __( 'Set the point that the SVG should start from.', 'premium-addons-for-elementor' ),
					'default'     => array(
						'unit' => '%',
						'size' => 0,
					),
					'condition'   => array_merge(
						$common_conditions,
						array(
							'icon_type'       => array( 'icon', 'svg' ),
							'draw_svg'        => 'yes',
							'lottie_reverse!' => 'true',
						)
					),
				)
			);

			$repeater_list->add_control(
				'end_point',
				array(
					'label'       => __( 'End Point (%)', 'premium-addons-for-elementor' ),
					'type'        => Controls_Manager::SLIDER,
					'description' => __( 'Set the point that the SVG should end at.', 'premium-addons-for-elementor' ),
					'default'     => array(
						'unit' => '%',
						'size' => 0,
					),
					'condition'   => array_merge(
						$common_conditions,
						array(
							'icon_type'      => array( 'icon', 'svg' ),
							'draw_svg'       => 'yes',
							'lottie_reverse' => 'true',
						)
					),

				)
			);

			$repeater_list->add_control(
				'svg_hover',
				array(
					'label'        => __( 'Only Play on Hover', 'premium-addons-for-elementor' ),
					'type'         => Controls_Manager::SWITCHER,
					'return_value' => 'true',
					'condition'    => array_merge(
						$common_conditions,
						array(
							'icon_type' => array( 'icon', 'svg' ),
							'draw_svg'  => 'yes',
						)
					),
				)
			);

			$repeater_list->add_control(
				'svg_yoyo',
				array(
					'label'     => __( 'Yoyo Effect', 'premium-addons-for-elementor' ),
					'type'      => Controls_Manager::SWITCHER,
					'condition' => array_merge(
						$common_conditions,
						array(
							'icon_type'   => array( 'icon', 'svg' ),
							'draw_svg'    => 'yes',
							'lottie_loop' => 'true',
						)
					),
				)
			);

		}

		$repeater_list->add_control(
			'show_list_link',
			array(
				'label'        => __( 'Link', 'premium-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
			)
		);

		$repeater_list->add_control(
			'link_select',
			array(
				'label'       => __( 'Link/URL', 'premium-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					'url'           => __( 'URL', 'premium-addons-for-elementor' ),
					'existing_page' => __( 'Existing Page', 'premium-addons-for-elementor' ),
				),
				'default'     => 'url',
				'label_block' => true,
				'condition'   => array(
					'show_list_link' => 'yes',
				),
			)
		);

		$repeater_list->add_control(
			'link',
			array(
				'label'       => __( 'URL', 'premium-addons-for-elementor' ),
				'type'        => Controls_Manager::URL,
				'dynamic'     => array( 'active' => true ),
				'placeholder' => 'https://premiumaddons.com/',
				'label_block' => true,
				'condition'   => array(
					'link_select'    => 'url',
					'show_list_link' => 'yes',
				),
			)
		);

		$repeater_list->add_control(
			'existing_page',
			array(
				'label'       => __( 'Existing Page', 'premium-addons-for-elementor' ),
				'type'        => Premium_Post_Filter::TYPE,
				'label_block' => true,
				'multiple'    => false,
				'source'      => array( 'post', 'page' ),
				'condition'   => array(
					'link_select'    => 'existing_page',
					'show_list_link' => 'yes',
				),
			)
		);

		$repeater_list->add_control(
			'show_badge',
			array(
				'label'        => __( 'Badge', 'premium-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'separator'    => 'before',
			)
		);

		$repeater_list->add_control(
			'badge_title',
			array(
				'label'     => __( 'Badge Text', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'New', 'premium-addons-for-elementor' ),
				'condition' => array(
					'show_badge' => 'yes',
				),
			)
		);

		$repeater_list->add_control(
			'badge_text_color',
			array(
				'label'     => __( 'Text Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .premium-bullet-list-badge span' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'show_badge' => 'yes',
				),
			)
		);

		$repeater_list->add_control(
			'badge_background_color',
			array(
				'label'     => __( 'Background', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .premium-bullet-list-badge span' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'show_badge' => 'yes',
				),
			)
		);

		$repeater_list->add_control(
			'show_global_style',
			array(
				'label'        => __( 'Override Global Style', 'premium-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
			)
		);

		$repeater_list->add_control(
			'list_box_size',
			array(
				'label'      => __( 'Size', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', '%', 'vw', 'custom' ),
				'range'      => array(
					'px' => array(
						'min' => 5,
						'max' => 200,
					),
					'em' => array(
						'min' => 5,
						'max' => 30,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .premium-bullet-text, {{WRAPPER}} {{CURRENT_ITEM}} .premium-bullet-list-wrapper i, {{WRAPPER}} {{CURRENT_ITEM}} .premium-bullet-list-wrapper .premium-bullet-list-icon-text p' => 'font-size: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} {{CURRENT_ITEM}} .premium-bullet-list-wrapper svg, {{WRAPPER}} {{CURRENT_ITEM}} .premium-bullet-list-wrapper img' => 'width: {{SIZE}}{{UNIT}} !important; height: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} {{CURRENT_ITEM}}' => '--pa-bullet-hv-size: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'show_global_style' => 'yes',
					'icon_type!'        => 'svg',
				),
			)
		);

		$repeater_list->add_responsive_control(
			'svg_icon_width',
			array(
				'label'      => __( 'Width', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', '%', 'vw', 'custom' ),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 600,
					),
					'em' => array(
						'min' => 1,
						'max' => 30,
					),
				),
				'default'    => array(
					'size' => 100,
					'unit' => 'px',
				),
				'condition'  => array(
					'show_global_style' => 'yes',
					'icon_type'         => 'svg',
				),
				'selectors'  => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .premium-bullet-list-wrapper svg' => 'width: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} {{CURRENT_ITEM}}' => '--pa-bullet-hv-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$repeater_list->add_responsive_control(
			'svg_icon_height',
			array(
				'label'      => __( 'Height', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'custom' ),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 300,
					),
					'em' => array(
						'min' => 1,
						'max' => 30,
					),
				),
				'condition'  => array(
					'show_global_style' => 'yes',
					'icon_type'         => 'svg',
				),
				'selectors'  => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .premium-bullet-list-wrapper svg' => 'height: {{SIZE}}{{UNIT}} !important',
					'{{WRAPPER}} {{CURRENT_ITEM}}' => '--pa-svg-bullet-h: {{SIZE}}{{UNIT}};',
				),
			)
		);

		if ( $draw_icon ) {
			$repeater_list->add_control(
				'svg_color',
				array(
					'label'     => __( 'After Draw Fill Color', 'premium-addons-for-elementor' ),
					'type'      => Controls_Manager::COLOR,
					'global'    => false,
					'condition' => array(
						'show_icon' => 'yes',
						'icon_type' => array( 'icon', 'svg' ),
						'draw_svg'  => 'yes',
					),
				)
			);
		}

		$repeater_list->start_controls_tabs( 'colors_style_tabs' );

		$repeater_list->start_controls_tab(
			'color_normal_state',
			array(
				'label'     => __( 'Normal', 'premium-addons-for-elementor' ),
				'condition' => array(
					'show_global_style' => 'yes',
				),
			)
		);

		$repeater_list->add_control(
			'icon_color',
			array(
				'label'     => __( 'Icon/Text Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .premium-bullet-list-wrapper i' => 'color: {{VALUE}}',
					'{{WRAPPER}} {{CURRENT_ITEM}} .premium-drawable-icon *, {{WRAPPER}} {{CURRENT_ITEM}} svg:not([class*="premium-"])' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .premium-bullet-list-blur:hover {{CURRENT_ITEM}} .premium-bullet-list-wrapper i, {{WRAPPER}} .premium-bullet-list-blur:hover {{CURRENT_ITEM}} .premium-bullet-list-wrapper svg' => 'text-shadow: 0 0 3px {{VALUE}}',
				),
				'condition' => array(
					'show_icon'         => 'yes',
					'icon_type'         => array( 'icon', 'svg' ),
					'show_global_style' => 'yes',
				),
			)
		);

		if ( $draw_icon ) {
			$repeater_list->add_control(
				'stroke_color',
				array(
					'label'     => __( 'Stroke Color', 'premium-addons-for-elementor' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#61CE70',
					'condition' => array(
						'show_icon'         => 'yes',
						'icon_type'         => array( 'icon', 'svg' ),
						'show_global_style' => 'yes',
					),
					'selectors' => array(
						'{{WRAPPER}} {{CURRENT_ITEM}} .premium-drawable-icon *, {{WRAPPER}} {{CURRENT_ITEM}} svg:not([class*="premium-"])' => 'stroke: {{VALUE}};',
					),
				)
			);
		}

		$repeater_list->add_control(
			'icon_bg',
			array(
				'label'     => __( 'Icon Background', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .premium-bullet-list-wrapper i ,
					 {{WRAPPER}} {{CURRENT_ITEM}} .premium-bullet-list-wrapper svg,
					  {{WRAPPER}} {{CURRENT_ITEM}} .premium-bullet-list-wrapper img ,
					   {{WRAPPER}} {{CURRENT_ITEM}} .premium-bullet-list-icon-text p' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'show_icon'         => 'yes',
					'show_global_style' => 'yes',
				),
			)
		);

		$repeater_list->add_control(
			'text_icon_color',
			array(
				'label'     => __( 'Icon/Text Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}  .premium-bullet-list-icon-text p' => 'color: {{VALUE}}',
					'{{WRAPPER}} .premium-bullet-list-blur:hover {{CURRENT_ITEM}} .premium-bullet-list-icon-text p' => 'text-shadow: 0 0 3px {{VALUE}};',
				),
				'condition' => array(
					'show_icon'         => 'yes',
					'icon_type'         => 'text',
					'show_global_style' => 'yes',
				),
			)
		);

		$repeater_list->add_control(
			'background_text_icon_color',
			array(
				'label'     => __( 'Icon/Text Background', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}  .premium-bullet-list-icon-text p' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'show_icon'         => 'yes',
					'icon_type'         => 'text',
					'show_global_style' => 'yes',
				),
			)
		);

		$repeater_list->add_control(
			'title_list_color',
			array(
				'label'     => __( 'Title Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .premium-bullet-text' => 'color: {{VALUE}}',
					'{{WRAPPER}} .premium-bullet-list-blur:hover {{CURRENT_ITEM}} .premium-bullet-text' => 'text-shadow: 0 0 3px {{VALUE}};',
				),
				'condition' => array(
					'show_global_style' => 'yes',
				),
			)
		);

		$repeater_list->add_control(
			'desc_color',
			array(
				'label'     => __( 'Description Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .premium-bullet-list-desc' => 'color: {{VALUE}}',
					'{{WRAPPER}} .premium-bullet-list-blur:hover {{CURRENT_ITEM}} .premium-bullet-list-desc' => 'text-shadow: 0 0 3px {{VALUE}};',
				),
				'condition' => array(
					'show_global_style' => 'yes',
					'list_desc!'        => '',
				),
			)
		);

		$repeater_list->add_control(
			'background_color',
			array(
				'label'     => __( 'Background', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}.premium-bullet-list-content' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'show_global_style' => 'yes',
				),
			)
		);

		$repeater_list->end_controls_tab();

		$repeater_list->start_controls_tab(
			'color_hover_state',
			array(
				'label'     => __( 'Hover', 'premium-addons-for-elementor' ),
				'condition' => array(
					'show_global_style' => 'yes',
				),
			)
		);

		$repeater_list->add_control(
			'icon_hover_color',
			array(
				'label'     => __( 'Icon/Text Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}.premium-bullet-list-content:hover .premium-bullet-list-wrapper i' => 'color: {{VALUE}}',
					'{{WRAPPER}} {{CURRENT_ITEM}}.premium-bullet-list-content:hover .premium-drawable-icon *, {{WRAPPER}} {{CURRENT_ITEM}}.premium-bullet-list-content:hover svg:not([class*="premium-"])' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .premium-bullet-list-blur {{CURRENT_ITEM}}.premium-bullet-list-content:hover .premium-bullet-list-wrapper i, {{WRAPPER}} .premium-bullet-list-blur {{CURRENT_ITEM}}.premium-bullet-list-content:hover .premium-bullet-list-wrapper svg' => 'text-shadow: none !important; color: {{VALUE}} !important',
				),
				'condition' => array(
					'show_icon'         => 'yes',
					'icon_type'         => array( 'icon', 'svg' ),
					'show_global_style' => 'yes',
				),
			)
		);

		if ( $draw_icon ) {
			$repeater_list->add_control(
				'stroke_color_hover',
				array(
					'label'     => __( 'Stroke Color', 'premium-addons-for-elementor' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#61CE70',
					'condition' => array(
						'show_icon'         => 'yes',
						'icon_type'         => array( 'icon', 'svg' ),
						'show_global_style' => 'yes',
					),
					'selectors' => array(
						'{{WRAPPER}} {{CURRENT_ITEM}}.premium-bullet-list-content:hover .premium-drawable-icon *, {{WRAPPER}} {{CURRENT_ITEM}}.premium-bullet-list-content:hover svg:not([class*="premium-"])' => 'stroke: {{VALUE}};',
					),
				)
			);
		}

		$repeater_list->add_control(
			'text_icon_hover_color',
			array(
				'label'     => __( 'Icon/Text Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}.premium-bullet-list-content:hover .premium-bullet-list-icon-text p' => 'color: {{VALUE}}',
					'{{WRAPPER}} .premium-bullet-list-blur {{CURRENT_ITEM}}.premium-bullet-list-content:hover .premium-bullet-list-icon-text p' => 'text-shadow: none !important; color: {{VALUE}} !important;',
				),
				'condition' => array(
					'show_icon'         => 'yes',
					'icon_type'         => 'text',
					'show_global_style' => 'yes',
				),
			)
		);

		$repeater_list->add_control(
			'background_text_icon_hover_color',
			array(
				'label'     => __( 'Icon/Text Background ', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_PRIMARY,
				),
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}.premium-bullet-list-content:hover .premium-bullet-list-icon-text p' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'show_icon'         => 'yes',
					'icon_type'         => 'text',
					'show_global_style' => 'yes',
				),
			)
		);

		$repeater_list->add_control(
			'title_hover_color',
			array(
				'label'     => __( 'Title Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}.premium-bullet-list-content:hover .premium-bullet-text' => 'color: {{VALUE}}',
					'{{WRAPPER}} .premium-bullet-list-blur:hover {{CURRENT_ITEM}}.premium-bullet-list-content:hover .premium-bullet-text' => 'text-shadow: none !important; color: {{VALUE}} !important;',
				),
				'condition' => array(
					'show_global_style' => 'yes',
				),
			)
		);

		$repeater_list->add_control(
			'desc_color_hov',
			array(
				'label'     => __( 'Description Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}.premium-bullet-list-content:hover .premium-bullet-list-desc' => 'color: {{VALUE}}',
					'{{WRAPPER}} .premium-bullet-list-blur {{CURRENT_ITEM}}.premium-bullet-list-content:hover .premium-bullet-list-desc' => 'text-shadow: 0 0 3px {{VALUE}}; color: {{VALUE}} !important;',
				),
				'condition' => array(
					'show_global_style' => 'yes',
					'list_desc!'        => '',
				),
			)
		);

		$repeater_list->add_control(
			'background_hover_color',
			array(
				'label'     => __( 'Background', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}.premium-bullet-list-content:hover' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'show_global_style' => 'yes',
				),
			)
		);

		$repeater_list->end_controls_tab();

		$repeater_list->end_controls_tabs();

		$this->add_control(
			'list',
			array(
				'label'       => __( 'List Items', 'premium-addons-for-elementor' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater_list->get_controls(),
				'render_type' => 'template',
				'default'     => array(
					array(
						'list_title'                     => 'List Title #1',
						'premium_icon_list_font_updated' => array(
							'value'   => 'fas fa-star',
							'library' => 'fa-solid',
						),
					),
					array(
						'list_title'                     => 'List Title  #2',
						'premium_icon_list_font_updated' => array(
							'value'   => 'far fa-smile',
							'library' => 'fa-regular',
						),

					),
					array(
						'list_title'                     => 'List Title  #3',
						'premium_icon_list_font_updated' => array(
							'value'   => 'fa fa-check',
							'library' => 'fa-solid',
						),
					),
				),
				'title_field' => '<# if ( "icon" === icon_type ) { #> {{{ elementor.helpers.renderIcon( this, premium_icon_list_font_updated, {}, "i", "panel" ) }}}<#} else if( "text" === icon_type ) { #> {{list_text_icon}} <# } else if( "image" === icon_type) {#> <img class="editor-pa-img" src="{{custom_image.url}}"><# } #> {{{ list_title }}}',
			)
		);

		if ( $draw_icon ) {
			$this->add_control(
				'draw_svgs_sequence',
				array(
					'label'        => __( 'Draw SVGs In Sequence', 'premium-addons-for-elementor' ),
					'type'         => Controls_Manager::SWITCHER,
					'prefix_class' => 'pa-svg-draw-seq-',
					'render_type'  => 'template',
				)
			);

			$this->add_control(
				'draw_svgs_loop',
				array(
					'label'        => __( 'Loop', 'premium-addons-for-elementor' ),
					'type'         => Controls_Manager::SWITCHER,
					'prefix_class' => 'pa-svg-draw-loop-',
					'render_type'  => 'template',
					'condition'    => array(
						'draw_svgs_sequence' => 'yes',
					),
				)
			);

			$this->add_control(
				'frames',
				array(
					'label'       => __( 'Speed', 'premium-addons-for-elementor' ),
					'type'        => Controls_Manager::NUMBER,
					'description' => __( 'Larger value means longer animation duration.', 'premium-addons-for-elementor' ),
					'default'     => 5,
					'min'         => 1,
					'max'         => 100,
					'condition'   => array(
						'draw_svgs_sequence' => 'yes',
					),
				)
			);

			$this->add_control(
				'svg_yoyo',
				array(
					'label'        => __( 'Yoyo Effect', 'premium-addons-for-elementor' ),
					'type'         => Controls_Manager::SWITCHER,
					'prefix_class' => 'pa-svg-draw-yoyo-',
					'render_type'  => 'template',
					'condition'    => array(
						'draw_svgs_sequence' => 'yes',
						'draw_svgs_loop'     => 'yes',
					),
				)
			);
		}

		$this->end_controls_section();
	}

	private function add_display_options_controls() {

		$this->start_controls_section(
			'display_options_section',
			array(
				'label' => __( 'Display Options', 'premium-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'list_overflow',
			array(
				'label'       => __( 'List Overflow', 'premium-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					'visible' => __( 'Visible', 'premium-addons-for-elementor' ),
					'hidden'  => __( 'Hidden', 'premium-addons-for-elementor' ),
				),
				'default'     => 'hidden',
				'render_type' => 'template',
				'selectors'   => array(
					'{{WRAPPER}} .premium-bullet-list-content' => 'overflow: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'overflow_render_notice',
			array(
				'raw'             => __( 'Please note that bullet connector option only appears when overflow set to visible.', 'premium-addons-for-elementor' ),
				'type'            => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			)
		);

		$this->add_responsive_control(
			'layout_type',
			array(
				'label'        => __( 'Layout Type', 'premium-addons-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => array(
					'row'    => __( 'Inline', 'premium-addons-for-elementor' ),
					'column' => __( 'Block', 'premium-addons-for-elementor' ),
				),
				'prefix_class' => 'premium%s-type-',
				'render_type'  => 'ui',
				'default'      => 'column',
				'selectors'    => array(
					'{{WRAPPER}} .premium-bullet-list-box ' => 'flex-direction: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'premium_icon_list_align',
			array(
				'label'       => __( 'Alignment', 'premium-addons-for-elementor' ),
				'type'        => Controls_Manager::CHOOSE,
				'render_type' => 'template',
				'options'     => array(
					'flex-start' => array(
						'title' => __( 'Left', 'premium-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'     => array(
						'title' => __( 'Center', 'premium-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'flex-end'   => array(
						'title' => __( 'Right', 'premium-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'selectors'   => array(
					'{{WRAPPER}} .premium-bullet-list-content, {{WRAPPER}} .premium-bullet-list-box' => 'justify-content: {{VALUE}};',
					'{{WRAPPER}} .premium-bullet-list-divider, {{WRAPPER}} .premium-bullet-list-wrapper-top' => 'align-self: {{VALUE}};',
					'{{WRAPPER}}' => '--pa-bullet-align: {{VALUE}}',
				),
				'toggle'      => false,
				'default'     => 'flex-start',
				'condition'   => array(
					'hover_effect_type!' => 'translate-bullet',
				),
			)
		);

		$this->add_responsive_control(
			'text_align',
			array(
				'label'       => __( 'Text Alignment', 'premium-addons-for-elementor' ),
				'type'        => Controls_Manager::CHOOSE,
				'description' => __( 'Aligns the <b>title and description</b> inside each list item.', 'premium-addons-for-elementor' ),
				'options'     => array(
					'start'  => array(
						'title' => __( 'Left', 'premium-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'premium-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'end'    => array(
						'title' => __( 'Right', 'premium-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'selectors'   => array(
					'{{WRAPPER}} .premium-bullet-list-content .premium-bullet-list-text-wrapper > span' => 'align-self: {{VALUE}}; text-align: {{VALUE}};',
				),
				'toggle'      => false,
				'default'     => 'start',
			)
		);

		$this->add_control(
			'icon_postion',
			array(
				'label'       => __( 'Bullet Position', 'premium-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					'row'         => __( 'Before', 'premium-addons-for-elementor' ),
					'column'      => __( 'Top', 'premium-addons-for-elementor' ),
					'row-reverse' => __( 'After', 'premium-addons-for-elementor' ),
				),
				'render_type' => 'template',
				'default'     => 'row',
				'selectors'   => array(
					'{{WRAPPER}} .premium-bullet-list-text' => 'display:flex;flex-direction: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'top_icon_align',
			array(
				'label'     => __( 'Bullet Alignment', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start' => array(
						'title' => __( 'Left', 'premium-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'     => array(
						'title' => __( 'Center', 'premium-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'flex-end'   => array(
						'title' => __( 'Right', 'premium-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'toggle'    => false,
				'selectors' => array(
					'{{WRAPPER}} .premium-bullet-list-wrapper-top'      => 'align-self: {{VALUE}} !important',
				),
				'condition' => array(
					'icon_postion' => 'column',
				),
			)
		);

		$this->add_responsive_control(
			'inline_icon_align',
			array(
				'label'     => __( 'Bullet Alignment', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start' => array(
						'title' => __( 'Top', 'premium-addons-for-elementor' ),
						'icon'  => 'eicon-arrow-up',
					),
					'center'     => array(
						'title' => __( 'Center', 'premium-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-justify',
					),
					'flex-end'   => array(
						'title' => __( 'Bottom', 'premium-addons-for-elementor' ),
						'icon'  => 'eicon-arrow-down',
					),
				),
				'default'   => 'center',
				'toggle'    => false,
				'selectors' => array(
					'{{WRAPPER}} .premium-bullet-list-wrapper' => 'align-self: {{VALUE}};',
				),
				'condition' => array(
					'icon_postion!' => 'column',
				),
			)
		);

		$this->add_responsive_control(
			'badge_align_h',
			array(
				'label'     => __( 'Badge Alignment', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'8' => array(
						'title' => __( 'Right', 'premium-addons-for-elementor' ),
						'icon'  => 'fas fa-long-arrow-alt-right',
					),
					'3' => array(
						'title' => __( 'Left', 'premium-addons-for-elementor' ),
						'icon'  => 'fas fa-long-arrow-alt-left',
					),
				),
				'default'   => '8',
				'toggle'    => false,
				'selectors' => array(
					'{{WRAPPER}} .premium-bullet-list-text' => 'order:5 ;',
					'{{WRAPPER}} .premium-bullet-list-badge' => 'order:{{VALUE}} ;',
				),
				'separator' => 'after',
			)
		);

		$this->add_control(
			'show_divider',
			array(
				'label'        => __( 'Divider', 'premium-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'show_connector',
			array(
				'label'        => __( 'Bullet Connector', 'premium-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'condition'    => array(
					'layout_type'        => 'column',
					'icon_postion!'      => 'column',
					'hover_effect_type!' => 'grow',
					'list_overflow'      => 'visible',
				),
			)
		);

		$this->add_control(
			'premium_icon_list_animation_switcher',
			array(
				'label' => __( 'Animation', 'premium-addons-for-elementor' ),
				'type'  => Controls_Manager::SWITCHER,
			)
		);

		$this->add_control(
			'premium_icon_list_animation',
			array(
				'label'              => __( 'Entrance Animation', 'premium-addons-for-elementor' ),
				'type'               => Controls_Manager::ANIMATION,
				'default'            => '',
				'label_block'        => true,
				'frontend_available' => true,
				'render_type'        => 'template',
				'condition'          => array(
					'premium_icon_list_animation_switcher' => 'yes',
				),
			)
		);

		$this->add_control(
			'premium_icon_list_animation_duration',
			array(
				'label'     => __( 'Animation Duration', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => array(
					'slow' => __( 'Slow', 'premium-addons-for-elementor' ),
					''     => __( 'Normal', 'premium-addons-for-elementor' ),
					'fast' => __( 'Fast', 'premium-addons-for-elementor' ),
				),
				'condition' => array(
					'premium_icon_list_animation_switcher' => 'yes',
					'premium_icon_list_animation!'         => '',
				),
			)
		);

		$this->add_control(
			'premium_icon_list_animation_delay',
			array(
				'label'              => __( 'Animation Delay in Between (s)', 'premium-addons-for-elementor' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 0,
				'step'               => 0.1,
				'condition'          => array(
					'premium_icon_list_animation_switcher' => 'yes',
					'premium_icon_list_animation!'         => '',
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'hover_effect_type',
			array(
				'label'       => __( 'Hover Effect', 'premium-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'description' => __( 'Please note that the <b>Translate Bullet</b> effect will not be applied if the bullet position is <b>top</b>.', 'premium-addons-for-elementor' ),
				'options'     => array(
					'none'             => __( 'None', 'premium-addons-for-elementor' ),
					'blur'             => __( 'Blur', 'premium-addons-for-elementor' ),
					'grow'             => __( 'Grow', 'premium-addons-for-elementor' ),
					'linear gradient'  => __( 'Text Gradient', 'premium-addons-for-elementor' ),
					'show-bullet'      => __( 'Slide Bullet', 'premium-addons-for-elementor' ),
					'translate-bullet' => __( 'Translate Bullet', 'premium-addons-for-elementor' ),
				),
				'render_type' => 'template',
				'default'     => 'none',
			)
		);

		$this->add_control(
			'show_bullet_transition',
			array(
				'label'     => __( 'Transition (S)', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::NUMBER,
				'step'      => 0.1,
				'default'   => 0.7,
				'condition' => array(
					'hover_effect_type' => array( 'show-bullet', 'translate-bullet' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .pa-show-bullet, {{WRAPPER}} .pa-translate-bullet' => 'transition-duration: {{VALUE}}s;',
				),
			)
		);

		$this->add_control(
			'show_bullet_ease',
			array(
				'label'     => __( 'Easing', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'cubic-bezier(.135,.9,.15,1)',
				'options'   => array(
					'cubic-bezier(.135,.9,.15,1)' => __( 'Default', 'premium-addons-for-elementor' ),
					'linear'                      => __( 'Linear', 'premium-addons-for-elementor' ),
					'ease'                        => __( 'Ease', 'premium-addons-for-elementor' ),
					'ease-in'                     => __( 'Ease In', 'premium-addons-for-elementor' ),
					'ease-out'                    => __( 'Ease Out', 'premium-addons-for-elementor' ),
					'ease-in-out'                 => __( 'Ease In Out', 'premium-addons-for-elementor' ),
				),
				'condition' => array(
					'hover_effect_type' => 'show-bullet',
				),
				'selectors' => array(
					'{{WRAPPER}} .pa-show-bullet' => 'transition-timing-function: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Premium_Background::get_type(),
			array(
				'name'      => 'gradient_color',
				'types'     => array( 'gradient' ),
				'condition' => array(
					'hover_effect_type' => 'linear gradient',
				),
				'selector'  => '{{WRAPPER}} a[data-text]::before, {{WRAPPER}} .premium-bullet-list-gradient-effect span::before',
			)
		);

		$this->end_controls_section();
	}

	private function add_random_badge_controls() {

		$this->start_controls_section(
			'random_badges_section',
			array(
				'label' => __( 'Random Badges', 'premium-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$rbadges_repeater = new REPEATER();

		$rbadges_repeater->add_control(
			'badge_title',
			array(
				'label'   => __( 'Badge Text', 'premium-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'New', 'premium-addons-for-elementor' ),
			)
		);

		$rbadges_repeater->add_control(
			'badge_text_color',
			array(
				'label'     => __( 'Text Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{CURRENT_ITEM}}.premium-bullet-list-badge span' => 'color: {{VALUE}} !important',
				),
			)
		);

		$rbadges_repeater->add_control(
			'badge_background_color',
			array(
				'label'     => __( 'Background', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{CURRENT_ITEM}}.premium-bullet-list-badge span' => 'background-color: {{VALUE}} !important',
				),
			)
		);

		$rbadges_repeater->add_control(
			'rbadge_selector',
			array(
				'label'       => __( 'CSS Selector', 'premium-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => __( 'Use this option to add the CSS selector of the parent element that will be used to search for bullet items in it. This will help you to apply random badges on other bullet list items on the page.', 'premium-addons-for-elementor' ),
			)
		);

		$rbadges_repeater->add_control(
			'rbadge_min',
			array(
				'label'       => __( 'Minimum Randomness', 'premium-addons-for-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => __( 'The minimum number of times that this badge should be applied.', 'premium-addons-for-elementor' ),
				'default'     => 3,
				'min'         => 1,
			)
		);

		$rbadges_repeater->add_control(
			'rbadge_max',
			array(
				'label'       => __( 'Maximum Randomness', 'premium-addons-for-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => __( 'The maximum number of times that this badge should be applied.', 'premium-addons-for-elementor' ),
				'default'     => 5,
				'min'         => 1,
			)
		);

		$this->add_control(
			'rbadges_repeater',
			array(
				'label'              => __( 'Random Badges', 'premium-addons-for-elementor' ),
				'type'               => Controls_Manager::REPEATER,
				'fields'             => $rbadges_repeater->get_controls(),
				'render_type'        => 'template',
				'title_field'        => '{{{ badge_title }}}',
				'prevent_empty'      => false,
				'frontend_available' => true,
			)
		);

		$this->end_controls_section();
	}

	private function add_help_section_controls() {
		$this->start_controls_section(
			'section_pa_docs',
			array(
				'label' => __( 'Help & Docs', 'premium-addons-for-elementor' ),
			)
		);

		$docs = array(
			'https://premiumaddons.com/docs/icon-list-widget-tutorial/' => __( 'Getting started »', 'premium-addons-for-elementor' ),
			'https://www.youtube.com/watch?v=MPeXJiZ14sI' => __( 'Check the video tutorial »', 'premium-addons-for-elementor' ),
		);

		$doc_index = 1;
		foreach ( $docs as $url => $title ) {

			$doc_url = Helper_Functions::get_campaign_link( $url, 'bullet-widget', 'wp-editor', 'get-support' );

			$this->add_control(
				'doc_' . $doc_index,
				array(
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => sprintf( '<a href="%s" target="_blank">%s</a>', $doc_url, $title ),
					'content_classes' => 'editor-pa-doc',
				)
			);

			++$doc_index;

		}

		$this->end_controls_section();
	}

	private function add_general_style_controls() {
		$this->start_controls_section(
			'list_style_section',
			array(
				'label' => __( 'General', 'premium-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'list_items_color',
			array(
				'label'     => __( 'Background Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					' {{WRAPPER}} .premium-bullet-list-content' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'list_items_hover_color',
			array(
				'label'     => __( 'Hover Background Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .premium-bullet-list-content:hover ' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'items_lq_effect',
			array(
				'label'       => __( 'Liquid Glass Effect', 'premium-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'description' => sprintf(
					/* translators: 1: `<a>` opening tag, 2: `</a>` closing tag. */
					esc_html__( 'Important: Make sure this element has a semi-transparent background color to see the effect. See all presets from %1$shere%2$s.', 'premium-addons-for-elementor' ),
					'<a href="https://premiumaddons.com/liquid-glass/" target="_blank">',
					'</a>'
				),
				'options'     => array(
					'none'   => __( 'None', 'premium-addons-for-elementor' ),
					'glass1' => __( 'Preset 01', 'premium-addons-for-elementor' ),
					'glass2' => __( 'Preset 02', 'premium-addons-for-elementor' ),
					'glass3' => apply_filters( 'pa_pro_label', __( 'Preset 03 (Pro)', 'premium-addons-for-elementor' ) ),
					'glass4' => apply_filters( 'pa_pro_label', __( 'Preset 04 (Pro)', 'premium-addons-for-elementor' ) ),
					'glass5' => apply_filters( 'pa_pro_label', __( 'Preset 05 (Pro)', 'premium-addons-for-elementor' ) ),
					'glass6' => apply_filters( 'pa_pro_label', __( 'Preset 06 (Pro)', 'premium-addons-for-elementor' ) ),
				),
				'default'     => 'none',
				'label_block' => true,
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'list_items_shadow',
				'selector' => '{{WRAPPER}} .premium-bullet-list-content',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'list_items_shadow_hover',
				'label'    => 'Hover Box Shadow',
				'selector' => '{{WRAPPER}} .premium-bullet-list-content:hover',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'list_item_border',
				'label'    => __( 'Border', 'premium-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .premium-bullet-list-content ',
			)
		);

		$this->add_responsive_control(
			'list_item_border_radius',
			array(
				'label'      => __( 'Border Radius', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'custom' ),
				'default'    => array(
					'top'    => 0,
					'right'  => 0,
					'bottom' => 0,
					'left'   => 0,
				),
				'selectors'  => array(
					'{{WRAPPER}} .premium-bullet-list-content ' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'list_item_margin',
			array(
				'label'      => __( 'Margin', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .premium-bullet-list-content ' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'list_items_padding',
			array(
				'label'      => __( 'Padding', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .premium-bullet-list-content ' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'list_padding',
			array(
				'label'      => __( 'List Padding', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .premium-bullet-list-box ' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	private function add_bullet_style_controls( $draw_icon ) {
		$this->start_controls_section(
			'icon_style_section',
			array(
				'label' => __( 'Bullet', 'premium-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'icon_render_notice',
			array(
				'raw'  => __( 'Options below will be applied on items with no style options set individually from the repeater.', 'premium-addons-for-elementor' ),
				'type' => Controls_Manager::RAW_HTML,
			)
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'      => __( 'Size', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'vw', 'custom' ),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 200,
					),
					'em' => array(
						'min' => 1,
						'max' => 30,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .premium-bullet-list-wrapper i, {{WRAPPER}} .premium-bullet-list-text p, {{WRAPPER}} .premium-bullet-text' => 'font-size: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .premium-bullet-list-wrapper svg, {{WRAPPER}} .premium-bullet-list-wrapper img' => 'width: {{SIZE}}{{UNIT}} !important; height: {{SIZE}}{{UNIT}} !important',
					'{{WRAPPER}}' => '--pa-bullet-hv-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$selector       = $draw_icon ? '{{WRAPPER}} .premium-drawable-icon *, ' : '';
		$hover_selector = $draw_icon ? '{{WRAPPER}} .premium-bullet-list-content:hover .premium-drawable-icon *, ' : '';

		$this->add_control(
			'icon_color',
			array(
				'label'     => __( 'Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_PRIMARY,
				),
				'selectors' => array(
					'{{WRAPPER}} .premium-bullet-list-wrapper i, {{WRAPPER}} .premium-bullet-list-icon-text p' => 'color: {{VALUE}}',
					$selector . '{{WRAPPER}} svg:not([class*="premium-"])' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .premium-bullet-list-blur:hover .premium-bullet-list-wrapper i, {{WRAPPER}} .premium-bullet-list-blur:hover .premium-bullet-list-wrapper svg, {{WRAPPER}} .premium-bullet-list-blur:hover .premium-bullet-list-wrapper .premium-bullet-list-icon-text p' => 'text-shadow: 0 0 3px {{VALUE}};',
				),
			)
		);

		if ( $draw_icon ) {
			$this->add_control(
				'stroke_color',
				array(
					'label'     => __( 'Stroke Color', 'premium-addons-for-elementor' ),
					'type'      => Controls_Manager::COLOR,
					'global'    => array(
						'default' => Global_Colors::COLOR_PRIMARY,
					),
					'selectors' => array(
						'{{WRAPPER}} .premium-drawable-icon *, {{WRAPPER}} svg:not([class*="premium-"])' => 'stroke: {{VALUE}};',
					),
				)
			);
		}

		$this->add_control(
			'icon_hover_color',
			array(
				'label'     => __( 'Hover Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_PRIMARY,
				),
				'selectors' => array(
					'{{WRAPPER}} .premium-bullet-list-content:hover .premium-bullet-list-wrapper i, {{WRAPPER}} .premium-bullet-list-content:hover .premium-bullet-list-icon-text p' => 'color: {{VALUE}}',
					'{{WRAPPER}} .premium-bullet-list-blur .premium-bullet-list-content:hover .premium-bullet-list-wrapper i, {{WRAPPER}} .premium-bullet-list-blur .premium-bullet-list-content:hover  .premium-bullet-list-icon-text p' => 'text-shadow: none !important; color: {{VALUE}} !important;',
					$hover_selector . '{{WRAPPER}} .premium-bullet-list-content:hover svg:not([class*="premium-"])' => 'fill: {{VALUE}};',
				),
			)
		);

		if ( $draw_icon ) {
			$this->add_control(
				'stroke_color_hover',
				array(
					'label'     => __( 'Hover Stroke Color', 'premium-addons-for-elementor' ),
					'type'      => Controls_Manager::COLOR,
					'global'    => array(
						'default' => Global_Colors::COLOR_PRIMARY,
					),
					'selectors' => array(
						'{{WRAPPER}} .premium-bullet-list-content:hover .premium-drawable-icon *, {{WRAPPER}} .premium-bullet-list-content:hover svg:not([class*="premium-"])' => 'stroke: {{VALUE}};',
					),
				)
			);
		}

		$this->add_control(
			'background_render_notice',
			array(
				'raw'       => __( 'Background Color options below will be applied on all bullet types.', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::RAW_HTML,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'icon_background_color',
			array(
				'label'     => __( 'Background', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .premium-bullet-list-wrapper i , {{WRAPPER}} .premium-bullet-list-wrapper svg, {{WRAPPER}} .premium-bullet-list-wrapper img , {{WRAPPER}} .premium-bullet-list-icon-text p' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'icon_background_hover_color',
			array(
				'label'     => __( 'Hover Background', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .premium-bullet-list-content:hover .premium-bullet-list-wrapper i,{{WRAPPER}} .premium-bullet-list-content:hover .premium-bullet-list-wrapper svg ,{{WRAPPER}} .premium-bullet-list-content:hover .premium-bullet-list-wrapper img ,  {{WRAPPER}} .premium-bullet-list-content:hover  .premium-bullet-list-icon-text p' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'typo_text_render_notice',
			array(
				'raw'       => __( 'Typography option below will be applied on text.', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::RAW_HTML,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'text_icon_typography',
				'selector' => ' {{WRAPPER}} .premium-bullet-list-icon-text p',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'border',
				'selector'  => '{{WRAPPER}} .premium-bullet-list-content .premium-bullet-list-wrapper i , {{WRAPPER}} .premium-bullet-list-content .premium-bullet-list-wrapper svg , {{WRAPPER}} .premium-bullet-list-content .premium-bullet-list-wrapper img ,{{WRAPPER}} .premium-bullet-list-content .premium-bullet-list-wrapper .premium-bullet-list-icon-text p',
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'icon_border_radius',
			array(
				'label'      => __( 'Border Radius', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .premium-bullet-list-content .premium-bullet-list-wrapper i ,{{WRAPPER}} .premium-bullet-list-content .premium-bullet-list-wrapper .premium-bullet-list-icon-text p, {{WRAPPER}} .premium-bullet-list-content .premium-bullet-list-wrapper svg , {{WRAPPER}} .premium-bullet-list-content .premium-bullet-list-wrapper img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'icon_margin',
			array(
				'label'      => __( 'Margin', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .premium-bullet-list-wrapper ' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->add_responsive_control(
			'icon_padding',
			array(
				'label'      => __( 'Padding', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .premium-bullet-list-content .premium-bullet-list-wrapper i,{{WRAPPER}} .premium-bullet-list-content .premium-bullet-list-wrapper .premium-bullet-list-icon-text p , {{WRAPPER}} .premium-bullet-list-content .premium-bullet-list-wrapper svg , {{WRAPPER}} .premium-bullet-list-content .premium-bullet-list-wrapper img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	private function add_title_style_controls() {
		$this->start_controls_section(
			'title_style_section',
			array(
				'label' => __( 'Title', 'premium-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'title_render_notice',
			array(
				'raw'  => __( 'Options below will be applied on items with no style options set individually from the repeater.', 'premium-addons-for-elementor' ),
				'type' => Controls_Manager::RAW_HTML,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'list_title_typography',
				'selector' => '{{WRAPPER}} .premium-bullet-text',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				),

			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => __( 'Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_PRIMARY,
				),
				'selectors' => array(
					' {{WRAPPER}} .premium-bullet-text' => 'color: {{VALUE}}',
					' {{WRAPPER}} .premium-bullet-list-blur:hover .premium-bullet-text' => 'text-shadow: 0 0 3px {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'title_hover_color',
			array(
				'label'     => __( 'Hover Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_PRIMARY,
				),
				'selectors' => array(
					'{{WRAPPER}} .premium-bullet-list-content:hover .premium-bullet-text' => 'color: {{VALUE}}',
					'{{WRAPPER}} .premium-bullet-list-blur .premium-bullet-list-content:hover .premium-bullet-text' => 'text-shadow: none !important; color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_group_control(
			Group_Control_text_Shadow::get_type(),
			array(
				'name'     => 'list_title_shadow',
				'selector' => '{{WRAPPER}} .premium-bullet-text',
			)
		);

		$this->add_responsive_control(
			'list_title_margin',
			array(
				'label'      => __( 'Margin', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .premium-bullet-list-text ' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	private function add_desc_style_controls() {

		$this->start_controls_section(
			'description_style_section',
			array(
				'label' => __( 'Description', 'premium-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'desc_render_notice',
			array(
				'raw'  => __( 'Options below will be applied on items with no style options set individually from the repeater.', 'premium-addons-for-elementor' ),
				'type' => Controls_Manager::RAW_HTML,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'list_desc_typography',
				'selector' => '{{WRAPPER}} .premium-bullet-list-desc',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				),

			)
		);

		$this->add_control(
			'desc_color',
			array(
				'label'     => __( 'Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_TEXT,
				),
				'selectors' => array(
					'{{WRAPPER}} .premium-bullet-list-desc' => 'color: {{VALUE}}',
					'{{WRAPPER}} .premium-bullet-list-blur:hover .premium-bullet-list-desc' => 'text-shadow: 0 0 3px {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'desc_hover_color',
			array(
				'label'     => __( 'Hover Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .premium-bullet-list-content:hover .premium-bullet-list-desc' => 'color: {{VALUE}}',
					'{{WRAPPER}} .premium-bullet-list-blur .premium-bullet-list-content:hover .premium-bullet-list-desc' => 'text-shadow: none !important; color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_responsive_control(
			'desc_margin',
			array(
				'label'      => __( 'Margin', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .premium-bullet-list-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	private function add_badge_style_controls() {
		$this->start_controls_section(
			'badge_style_section',
			array(
				'label' => __( 'Badge', 'premium-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'badge_title_typography',
				'selector' => ' {{WRAPPER}} .premium-bullet-list-badge span',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				),
			)
		);

		$this->add_control(
			'badge_color_render_notice',
			array(
				'raw'       => __( 'Color options below will be applied on badge with no style options set individually from the repeater.', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::RAW_HTML,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'badge_text_style_color',
			array(
				'label'     => __( 'Text Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_PRIMARY,
				),
				'selectors' => array(
					'{{WRAPPER}} .premium-bullet-list-badge span' => 'color: {{VALUE}}',
				),
				'default'   => '#fff',
			)
		);

		$this->add_control(
			'badge_background_style_color',
			array(
				'label'     => __( 'Background', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_PRIMARY,
				),
				'default'   => '#6ec1e4',
				'selectors' => array(
					'{{WRAPPER}} .premium-bullet-list-badge span' => 'background-color: {{VALUE}}',
				),
				'separator' => 'after',
			)
		);

		$this->add_responsive_control(
			'badge_border_radius',
			array(
				'label'      => __( 'Badge Radius', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'custom' ),
				'default'    => array(
					'top'    => 2,
					'right'  => 2,
					'bottom' => 2,
					'left'   => 2,
				),
				'selectors'  => array(
					'{{WRAPPER}} .premium-bullet-list-badge span ' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_text_Shadow::get_type(),
			array(
				'name'     => 'Badge_text_shadow',
				'selector' => '{{WRAPPER}} .premium-bullet-list-badge span',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'Badge_box_shadow',
				'selector' => '{{WRAPPER}} .premium-bullet-list-badge span',
			)
		);

		$this->add_responsive_control(
			'Badge_margin',
			array(
				'label'      => __( 'Margin', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'vw', 'custom' ),
				'default'    => array(
					'top'    => 0,
					'right'  => 0,
					'bottom' => 0,
					'left'   => 5,
				),
				'selectors'  => array(
					'{{WRAPPER}} .premium-bullet-list-badge ' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'Badge_padding',
			array(
				'label'      => __( 'Padding', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'vw', 'custom' ),
				'default'    => array(
					'top'    => 2,
					'right'  => 5,
					'bottom' => 2,
					'left'   => 5,
				),
				'selectors'  => array(
					'{{WRAPPER}} .premium-bullet-list-badge span ' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	private function add_divider_style_controls() {
		$this->start_controls_section(
			'divider_style_section',
			array(
				'label'     => __( 'Divider', 'premium-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_divider' => 'yes',
				),
			)
		);

		$this->add_control(
			'list_divider_type',
			array(
				'label'     => __( 'Divider Style', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'solid'  => __( 'Solid', 'premium-addons-for-elementor' ),
					'double' => __( 'Double', 'premium-addons-for-elementor' ),
					'dotted' => __( 'Dotted', 'premium-addons-for-elementor' ),
					'dashed' => __( 'Dashed', 'premium-addons-for-elementor' ),
					'groove' => __( 'Groove', 'premium-addons-for-elementor' ),
				),
				'default'   => 'solid',
				'selectors' => array(
					'{{WRAPPER}} .premium-bullet-list-divider:not(:last-child):after' => 'border-top-style: {{VALUE}};',
					'{{WRAPPER}} .premium-bullet-list-divider-inline:not(:last-child):after' => 'border-left-style: {{VALUE}};',
				),
				'condition' => array(
					'show_divider' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'list_divider_width',
			array(
				'label'       => __( ' Width', 'premium-addons-for-elementor' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( 'px', 'em', 'vw', 'custom' ),
				'range'       => array(
					'px' => array(
						'min' => 0,
						'max' => 600,
					),
					'em' => array(
						'min' => 0,
						'max' => 30,
					),
				),
				'label_block' => true,
				'selectors'   => array(
					'{{WRAPPER}} .premium-bullet-list-divider:not(:last-child):after' => 'width:{{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .premium-bullet-list-divider-inline:not(:last-child):after ' => 'border-left-width: {{SIZE}}{{UNIT}};',
				),
				'condition'   => array(
					'show_divider' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'list_divider_height',
			array(
				'label'       => __( 'Height', 'premium-addons-for-elementor' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( 'px', 'em', 'custom' ),
				'range'       => array(
					'px' => array(
						'min' => 0,
						'max' => 600,
					),
					'em' => array(
						'min' => 0,
						'max' => 30,
					),
				),
				'label_block' => true,
				'selectors'   => array(
					'{{WRAPPER}} .premium-bullet-list-divider:not(:last-child):after ' => 'border-top-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .premium-bullet-list-divider-inline:not(:last-child):after' => 'height: {{SIZE}}{{UNIT}};',
				),
				'condition'   => array(
					'show_divider' => 'yes',
				),
			)
		);

		$this->add_control(
			'list_divider_color',
			array(
				'label'     => __( 'Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_SECONDARY,
				),
				'default'   => '#ddd',
				'selectors' => array(
					'{{WRAPPER}} .premium-bullet-list-divider:not(:last-child):after ' => 'border-top-color: {{VALUE}};',
					'{{WRAPPER}} .premium-bullet-list-divider-inline:not(:last-child):after ' => 'border-left-color: {{VALUE}};',
				),
				'condition' => array(
					'show_divider' => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	private function add_connector_style_controls() {
		$this->start_controls_section(
			'connector_style_section',
			array(
				'label'     => __( 'Connector', 'premium-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'layout_type'        => 'column',
					'icon_postion!'      => 'column',
					'show_connector'     => 'yes',
					'hover_effect_type!' => 'grow',
					'list_overflow'      => 'visible',
				),
			)
		);

		$this->add_control(
			'icon_connector_type',
			array(
				'label'     => __( 'Style', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'solid'  => __( 'Solid', 'premium-addons-for-elementor' ),
					'double' => __( 'Double', 'premium-addons-for-elementor' ),
					'dotted' => __( 'Dotted', 'premium-addons-for-elementor' ),
					'dashed' => __( 'Dashed', 'premium-addons-for-elementor' ),
					'groove' => __( 'Groove', 'premium-addons-for-elementor' ),
				),
				'default'   => 'solid',
				'selectors' => array(
					'{{WRAPPER}} li.premium-bullet-list-content:not(:last-of-type) .premium-bullet-list-connector .premium-icon-connector-content:after ' => 'border-right-style: {{VALUE}};',
				),
				'condition' => array(
					'show_connector' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'icon_connector_width',
			array(
				'label'       => __( ' Width', 'premium-addons-for-elementor' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( 'px', 'em', 'custom' ),
				'range'       => array(
					'px' => array(
						'min' => 0,
						'max' => 600,
					),
					'em' => array(
						'min' => 0,
						'max' => 30,
					),
				),
				'label_block' => true,
				'selectors'   => array(
					'{{WRAPPER}} li.premium-bullet-list-content:not(:last-of-type) .premium-bullet-list-connector .premium-icon-connector-content:after' => 'border-right-width: {{SIZE}}{{UNIT}};',
				),
				'condition'   => array(
					'show_connector' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'icon_connector_height',
			array(
				'label'       => __( 'Height', 'premium-addons-for-elementor' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( 'px', 'em', 'custom' ),
				'default'     => array(
					'unit' => 'px',
					'size' => 28,
				),
				'range'       => array(
					'px' => array(
						'min' => 0,
						'max' => 600,
					),
					'em' => array(
						'min' => 0,
						'max' => 30,
					),
				),
				'label_block' => true,
				'selectors'   => array(
					'{{WRAPPER}} li.premium-bullet-list-content:not(:last-of-type) .premium-bullet-list-connector .premium-icon-connector-content:after' => 'height: {{SIZE}}{{UNIT}};',
				),
				'condition'   => array(
					'show_connector' => 'yes',
				),
			)
		);

		$this->add_control(
			'icon_connector_color',
			array(
				'label'     => __( 'Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_SECONDARY,
				),
				'default'   => '#ddd',
				'selectors' => array(
					'{{WRAPPER}} li.premium-bullet-list-content:not(:last-of-type) .premium-bullet-list-connector .premium-icon-connector-content:after' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'show_connector' => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render Bullet List output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 3.21.2
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();

		$id                = $this->get_id();
		$blur_trans_effect = in_array( $settings['hover_effect_type'], array( 'blur', 'translate-bullet' ), true );
		$animation_switch  = $settings['premium_icon_list_animation_switcher'];
		$draw_icon         = $this->check_icon_draw();
		$delay             = 0;

		$this->add_render_attribute( 'box', 'class', 'premium-bullet-list-box' );

		$this->add_render_attribute( 'title_wrapper', 'class', 'premium-bullet-list-text-wrapper' );

		if ( $blur_trans_effect ) {
			$this->add_render_attribute( 'box', 'class', 'premium-bullet-list-' . $settings['hover_effect_type'] );
		}

		if ( 'yes' === $animation_switch ) {

			$animation_class = $settings['premium_icon_list_animation'];

			if ( '' !== $settings['premium_icon_list_animation_duration'] ) {
				$animation_dur = 'animated-' . $settings['premium_icon_list_animation_duration'];
			} else {
				$animation_dur = 'animated-';
			}

			$this->add_render_attribute(
				'box',
				'data-list-animation',
				array(
					$animation_class,
					$animation_dur,
				)
			);
		}

		if ( $draw_icon && 'yes' === $settings['draw_svgs_sequence'] ) {
			$this->add_render_attribute( 'box', 'data-speed', $settings['frames'] );
		}

		?>
			<ul <?php echo wp_kses_post( $this->get_render_attribute_string( 'box' ) ); ?>>
		<?php

		if ( $settings['list'] ) {
			foreach ( $settings['list'] as $index => $item ) {

				$text_icon = $this->get_repeater_setting_key( 'list_text_icon', 'list', $index );

				$text_badge = $this->get_repeater_setting_key( 'badge_title', 'list', $index );

				$this->add_inline_editing_attributes( $text_icon, 'basic' );

				$this->add_inline_editing_attributes( $text_badge, 'basic' );

				$item_link = 'link_' . $index;

				if ( 'yes' === $item['show_list_link'] ) {

					$link_url = ( 'url' === $item['link_select'] ) ? $item['link'] : get_permalink( $item['existing_page'] );

					$this->add_render_attribute(
						$item_link,
						array(
							'class'      => 'premium-bullet-list-link',
							'aria-label' => $item['list_title'],
						)
					);

					if ( 'url' === $item['link_select'] ) {
						$this->add_link_attributes( $item_link, $link_url );
					} else {
						$this->add_render_attribute( $item_link, 'href', $link_url );
					}
				}

				$animation_key = 'icon_lottie_' . $index;

				if ( 'icon' === $item['icon_type'] || 'svg' === $item['icon_type'] ) {

					$this->add_render_attribute( $animation_key, 'class', 'premium-drawable-icon' );

					if ( 'yes' === $item['draw_svg'] ) {

						$this->add_render_attribute(
							$animation_key,
							array(
								'class'            => array( 'premium-svg-drawer', 'elementor-invisible' ),
								'data-svg-reverse' => $item['lottie_reverse'],
								'data-svg-loop'    => $item['lottie_loop'],
								'data-svg-hover'   => $item['svg_hover'],
								'data-svg-sync'    => $item['svg_sync'],
								'data-svg-fill'    => $item['svg_color'],
								'data-svg-frames'  => $item['frames'],
								'data-svg-yoyo'    => $item['svg_yoyo'],
								'data-svg-point'   => $item['lottie_reverse'] ? $item['end_point']['size'] : $item['start_point']['size'],
							)
						);

					} else {
						$this->add_render_attribute( $animation_key, 'class', 'premium-svg-nodraw' );
					}
				} elseif ( 'lottie' === $item['icon_type'] ) {

					$this->add_render_attribute(
						$animation_key,
						array(
							'class'               => 'premium-lottie-animation',
							'data-lottie-url'     => $item['lottie_url'],
							'data-lottie-loop'    => $item['lottie_loop'],
							'data-lottie-reverse' => $item['lottie_reverse'],
						)
					);
				}

				$list_content_key = 'content_index_' . $index;

				$this->add_render_attribute(
					$list_content_key,
					'class',
					array(
						'premium-bullet-list-content',
						'elementor-repeater-item-' . $item['_id'],
					)
				);

				if ( 'none' !== $settings['items_lq_effect'] ) {
					$this->add_render_attribute( $list_content_key, 'class', 'premium-con-lq__' . $settings['items_lq_effect'] );
				}

				if ( 'yes' === $animation_switch ) {

					$this->add_render_attribute(
						$list_content_key,
						'data-delay',
						array(
							$delay,
						)
					);

					$delay = $delay + $settings['premium_icon_list_animation_delay'] * 1000;
				}

				if ( 'grow' === $settings['hover_effect_type'] ) {

					$this->add_render_attribute(
						$list_content_key,
						'class',
						array(
							'premium-bullet-list-content-grow-effect',
						)
					);
				}

				?>

			<li <?php echo wp_kses_post( $this->get_render_attribute_string( $list_content_key ) ); ?>>
				<div class="premium-bullet-list-text">
				<?php

				if ( 'yes' === $item['show_icon'] ) {

					$wrapper_class = 'premium-bullet-list-wrapper';

					$this->add_render_attribute( 'wrapper-' . $index, 'class', $wrapper_class );

					if ( 'column' === $settings['icon_postion'] ) {

						$wrapper_top_class = 'premium-bullet-list-wrapper-top ';

						$this->add_render_attribute( 'wrapper-' . $index, 'class', $wrapper_top_class );

					}

					if ( 'linear gradient' === $settings['hover_effect_type'] ) {
						$this->add_render_attribute( 'title_wrapper', 'class', 'premium-bullet-list-gradient-effect' );
					}

					if ( in_array( $settings['hover_effect_type'], array( 'show-bullet', 'translate-bullet' ), true ) ) {
						$this->add_render_attribute( 'wrapper-' . $index, 'class', 'pa-' . $settings['hover_effect_type'] );

						if ( 'text' === $item['icon_type'] ) {
							$this->add_render_attribute( 'wrapper-' . $index, 'class', 'pa-has-text-bullet' );
						}
					}

					?>
				<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'wrapper-' . $index ) ); ?>>
					<?php if ( 'yes' === $settings['show_connector'] && 'column' === $settings['layout_type'] && 'column' !== $settings['icon_postion'] && 'grow' !== $settings['hover_effect_type'] && 'visible' === $settings['list_overflow'] ) { ?>
						<div class="premium-bullet-list-connector">
							<div class="premium-icon-connector-content"></div>
						</div>
						<?php
					}

					if ( 'icon' === $item['icon_type'] ) {
						if ( 'yes' !== $item['draw_svg'] ) {
							echo '<div class="premium-drawable-icon">';
								Icons_Manager::render_icon(
									$item['premium_icon_list_font_updated'],
									array(
										'class'       => array( 'premium-svg-nodraw' ),
										'aria-hidden' => 'true',
									)
								);
							echo '</div>';
						} else {
							?>
							<div <?php echo wp_kses_post( $this->get_render_attribute_string( $animation_key ) ); ?>>
								<?php echo Helper_Functions::get_svg_by_icon( $item['premium_icon_list_font_updated'] ); ?>
							</div>
							<?php
						}
					} elseif ( 'svg' === $item['icon_type'] ) {
						?>
						<div <?php echo wp_kses_post( $this->get_render_attribute_string( $animation_key ) ); ?>>
							<?php echo $this->print_unescaped_setting( 'custom_svg', 'list', $index ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>
						<?php
					} elseif ( 'text' === $item['icon_type'] ) {
						?>
						<div class="premium-bullet-list-icon-text">
							<p <?php echo wp_kses_post( $this->get_render_attribute_string( $text_icon ) ); ?>>
								<?php echo wp_kses_post( $item['list_text_icon'] ); ?>
							</p>
						</div>
						<?php
					} elseif ( 'image' === $item['icon_type'] ) {
						if ( ! empty( $item['custom_image']['url'] ) ) {
							$alt = Control_Media::get_image_alt( $item['custom_image'] );
							echo '<img src="' . esc_url( $item['custom_image']['url'] ) . '" alt="' . esc_attr( $alt ) . '">';
						}
					} else {
						echo '<div ' . wp_kses_post( $this->get_render_attribute_string( $animation_key ) ) . '></div>';
					}
					?>
				</div>
				<?php } ?>
				<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'title_wrapper' ) ); ?>>
					<?php echo '<span class="premium-bullet-text" data-text="' . esc_attr( $item['list_title'] ) . '"> ' . wp_kses_post( $item['list_title'] ) . ' </span>'; ?>
					<?php if ( ! empty( $item['list_desc'] ) ) : ?>
					<span class="premium-bullet-list-desc" data-text="<?php echo esc_attr( $item['list_desc'] ); ?>"><?php echo wp_kses_post( $item['list_desc'] ); ?></span>
					<?php endif; ?>
				</div>
				</div>

				<?php if ( 'yes' === $item['show_badge'] ) { ?>
					<div class="premium-bullet-list-badge">
						<span <?php echo wp_kses_post( $this->get_render_attribute_string( $text_badge ) ); ?>>
							<?php echo wp_kses_post( $item['badge_title'] ); ?>
						</span>
					</div>
				<?php } ?>

				<?php if ( 'yes' === $item['show_list_link'] ) { ?>
					<a <?php echo wp_kses_post( $this->get_render_attribute_string( $item_link ) ); ?>>
						<span><?php echo wp_kses_post( $item['list_title'] ); ?></span>
					</a>
				<?php } ?>

			</li>

				<?php
				if ( 'yes' === $settings['show_divider'] ) {
					$layout        = $settings['layout_type'];
					$divider_class = 'premium-bullet-list-divider';
					if ( 'row' === $layout ) {
						$divider_class .= '-inline';
					}

					$this->add_render_attribute( 'divider', 'class', $divider_class );
					?>
						<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'divider' ) ); ?>></div>
					<?php
				}
			}
		}
		?>
		</ul>
		<?php
	}

	/**
	 * Render Bullet List widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 3.21.2
	 * @access protected
	 */
	protected function content_template() {}
}
