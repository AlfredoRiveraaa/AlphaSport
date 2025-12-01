<?php
/**
 * Premium Mini Cart.
 */

namespace PremiumAddons\Modules\Woocommerce\Widgets;

// Elementor Classes.
use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Icons_Manager;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use PremiumAddons\Includes\Controls\Premium_Background;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;

use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

// Premium Addons Classes.
use PremiumAddons\Includes\Helper_Functions;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // If this file is called directly, abort.
}

/**
 * Class Mini_Cart
 */
class Mini_Cart extends Widget_Base {

	/**
	 * Retrieve Widget Name.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function get_name() {
		return 'premium-mini-cart';
	}

	private $render_mc_conds = array(
		'relation' => 'or',
		'terms'    => array(
			array(
				'name'  => 'behaviour',
				'value' => 'toggle',
			),
			array(
				'terms' => array(
					array(
						'name'  => 'behaviour',
						'value' => 'url',
					),
					array(
						'name'  => 'woo_cta_connect',
						'value' => 'yes',
					),
				),
			),
		),
	);

	/**
	 * Retrieve Widget Title.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function get_title() {
		return __( 'Mini Cart', 'premium-addons-for-elementor' );
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
		return array( 'pa', 'premium', 'mini cart', 'cart', 'woocommerce' );
	}

	/**
	 * Retrieve Widget Dependent CSS.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array CSS script handles.
	 */
	public function get_style_depends() {
		return array(
			'font-awesome-5-all',
			'pa-slick',
			'pa-odometer',
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
		return array(
			'pa-slick',
			'pa-odometer',
			'wc-cart-fragments',
			'premium-mini-cart',
		);
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
		return 'pa-mini-cart';
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
	 * Retrieve Widget Support URL.
	 *
	 * @access public
	 *
	 * @return string support URL.
	 */
	public function get_custom_help_url() {
		return 'https://premiumaddons.com/support/';
	}

	/**
	 * Register Mini Cart Controls.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {
		$this->register_content_tab_controls();
		$this->register_style_tab_controls();
	}

	private function register_content_tab_controls() {
		$this->add_trigger_ctrls();
		$this->add_mini_cart_ctrls();
		$this->add_mc_header_ctrls();
		$this->add_mc_listing_ctrls();
		$this->add_mc_cross_sells_ctrls();
		$this->add_mc_progressbar_ctrls();
		$this->add_mc_footer_ctrls();
		$this->add_help_docs_tab();
	}

	private function register_style_tab_controls() {
		$this->add_trigger_style();
		$this->add_header_style();
		$this->add_items_style();
		$this->add_cross_sells_style();
		$this->add_progressbar_style();
		$this->add_coupon_style();
		$this->add_remove_all_style();
		$this->add_footer_style();
		$this->add_loader_style();
		$this->add_empty_cart_style();
		$this->add_cart_containers_style();
	}
	/**
	 * Register Trigger section controls for the Mini Cart widget.
	 *
	 * @since 4.7.0
	 * @access private
	 *
	 * @return void
	 */
	private function add_trigger_ctrls() {

		$this->start_controls_section(
			'trigger_section',
			array(
				'label' => __( 'Trigger', 'premium-addons-for-elementor' ),
			)
		);

		$url = add_query_arg(
			array(
				'page'   => 'premium-addons',
				'search' => 'mini cart',
				'#tab'   => 'elements',
			),
			esc_url( admin_url( 'admin.php' ) )
		);

		$this->add_control(
			'mc_template_notice',
			array(
				'raw'             => __( 'Make sure the <b>Enable Custom Mini Cart Template</b> option is enabled from ', 'premium-addons-for-elementor' ) . '<a href="' . esc_url( $url ) . '" target="_blank">' . __( 'here.', 'premium-addons-for-elementor' ) . '</a>',
				'type'            => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			)
		);

		$this->add_control(
			'placement',
			array(
				'label'        => __( 'Placement', 'premium-addons-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'render_type'  => 'template',
				'prefix_class' => 'pa-woo-mc__',
				'options'      => array(
					'default' => __( 'Static', 'premium-addons-for-elementor' ),
					'float'   => __( 'Float', 'premium-addons-for-elementor' ),
				),
				'default'      => 'default',
			)
		);

		$this->add_responsive_control(
			'float_hpos',
			array(
				'label'        => __( 'Horizontal Position', 'premium-addons-pro' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => array(
					'left'   => array(
						'title' => __( 'Left', 'premium-addons-pro' ),
						'icon'  => 'eicon-h-align-left',
					),
					'right'  => array(
						'title' => __( 'Right', 'premium-addons-pro' ),
						'icon'  => 'eicon-h-align-right',
					),
					'custom' => array(
						'title' => __( 'Custom', 'premium-addons-pro' ),
						'icon'  => 'eicon-cog',
					),
				),
				'prefix_class' => 'premium-mc-float-',
				'default'      => 'right',
				'condition'    => array(
					'placement' => 'float',
				),
			)
		);

		$this->add_responsive_control(
			'float_custom_hpos',
			array(
				'label'     => __( 'Horizontal Offset (%)', 'premium-addons-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__inner-container' => 'left: {{SIZE}}%',
				),
				'condition' => array(
					'placement'  => 'float',
					'float_hpos' => 'custom',
				),
			)
		);

		$this->add_responsive_control(
			'float_vpos',
			array(
				'label'        => __( 'Vertical Position', 'premium-addons-pro' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => array(
					'top'    => array(
						'title' => __( 'Top', 'premium-addons-pro' ),
						'icon'  => 'eicon-arrow-up',
					),
					'middle' => array(
						'title' => __( 'Middle', 'premium-addons-pro' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'bottom' => array(
						'title' => __( 'Bottom', 'premium-addons-pro' ),
						'icon'  => 'eicon-arrow-down',
					),
					'custom' => array(
						'title' => __( 'Custom', 'premium-addons-pro' ),
						'icon'  => 'eicon-cog',
					),
				),
				'prefix_class' => 'premium-mc-float-',
				'default'      => 'middle',
				'condition'    => array(
					'placement' => 'float',
				),
			)
		);

		$this->add_responsive_control(
			'float_custom_vpos',
			array(
				'label'     => __( 'Vertical Offset (%)', 'premium-addons-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__inner-container' => 'top: {{SIZE}}%',
				),
				'condition' => array(
					'placement'  => 'float',
					'float_vpos' => 'custom',
				),
			)
		);

		$this->add_control(
			'presets',
			array(
				'label'        => __( 'Presets', 'premium-addons-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'prefix_class' => 'pa-woo-mc__',
				'render_type'  => 'template',
				'options'      => array(
					'preset-1' => __( 'Preset 1', 'premium-addons-for-elementor' ),
					'preset-2' => __( 'Preset 2', 'premium-addons-for-elementor' ),
					'preset-3' => __( 'Preset 3', 'premium-addons-for-elementor' ),
					'preset-4' => __( 'Preset 4', 'premium-addons-for-elementor' ),
					'preset-5' => __( 'Preset 5', 'premium-addons-for-elementor' ),
					'preset-6' => __( 'Preset 6', 'premium-addons-for-elementor' ),
					'preset-7' => __( 'Preset 7', 'premium-addons-for-elementor' ),
				),
				'default'      => 'preset-1',
				'condition'    => array(
					'placement' => 'default',
				),
			)
		);

		$this->add_control(
			'icon_type',
			array(
				'label'   => __( 'Icon Type', 'premium-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'icon'      => __( 'Icon', 'premium-addons-for-elementor' ),
					'image'     => __( 'Image', 'premium-addons-for-elementor' ),
					'animation' => __( 'Lottie Animation', 'premium-addons-for-elementor' ),
					'svg'       => __( 'SVG Code', 'premium-addons-for-elementor' ),
				),
				'default' => 'icon',
			)
		);

		$this->add_control(
			'default_icons',
			array(
				'label'     => __( 'Select an Icon', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'default-sharp'         => __( 'Cart (Sharp)', 'premium-addons-for-elementor' ),
					'default-round'         => __( 'Cart (Rounded)', 'premium-addons-for-elementor' ),
					'cart'                  => __( 'Modern Cart (Filled)', 'premium-addons-for-elementor' ),
					'cart-outline'          => __( 'Modern Cart (Outlined)', 'premium-addons-for-elementor' ),
					'basket'                => __( 'Basket (Filled)', 'premium-addons-for-elementor' ),
					'basket-thin'           => __( 'Basket (Outlined)', 'premium-addons-for-elementor' ),
					'shopping-bag-filled'   => __( 'Shopping Bag (Filled)', 'premium-addons-for-elementor' ),
					'shopping-bag-outlined' => __( 'Shopping Bag (Outlined)', 'premium-addons-for-elementor' ),
					'custom'                => __( 'Custom', 'premium-addons-for-elementor' ),
				),
				'default'   => 'default-sharp',
				'condition' => array(
					'icon_type' => 'icon',
				),
			)
		);

		$this->add_control(
			'icon',
			array(
				'label'                  => __( 'Select an Icon', 'premium-addons-for-elementor' ),
				'type'                   => Controls_Manager::ICONS,
				'label_block'            => false,
				'default'                => array(
					'value'   => 'fas fa-shopping-cart',
					'library' => 'fa-solid',
				),
				'exclude_inline_options' => 'none',
				'skin'                   => 'inline',
				'condition'              => array(
					'icon_type'     => 'icon',
					'default_icons' => 'custom',
				),
			)
		);

		$this->add_control(
			'image',
			array(
				'label'     => __( 'Upload Image', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'condition' => array(
					'icon_type' => 'image',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'thumbnail',
				'default'   => 'woocommerce_gallery_thumbnail',
				'condition' => array(
					'icon_type' => 'image',
				),
			)
		);

		$this->add_control(
			'custom_svg',
			array(
				'label'       => __( 'SVG Code', 'premium-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'description' => 'You can use these sites to create SVGs: <a href="https://danmarshall.github.io/google-font-to-svg-path/" target="_blank">Google Fonts</a> and <a href="https://boxy-svg.com/" target="_blank">Boxy SVG</a>',
				'condition'   => array(
					'icon_type' => 'svg',
				),
			)
		);

		$this->add_control(
			'lottie_source',
			array(
				'label'     => __( 'Source', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'url'  => __( 'External URL', 'premium-addons-for-elementor' ),
					'file' => __( 'Media File', 'premium-addons-for-elementor' ),
				),
				'default'   => 'url',
				'condition' => array(
					'icon_type' => 'animation',
				),
			)
		);

		$this->add_control(
			'lottie_url',
			array(
				'label'       => __( 'Animation JSON URL', 'premium-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array( 'active' => true ),
				'description' => 'Get JSON code URL from <a href="https://lottiefiles.com/" target="_blank">here</a>',
				'label_block' => true,
				'condition'   => array(
					'icon_type'     => 'animation',
					'lottie_source' => 'url',
				),
			)
		);

		$this->add_control(
			'lottie_file',
			array(
				'label'      => __( 'Upload JSON File', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::MEDIA,
				'media_type' => 'application/json',
				'condition'  => array(
					'icon_type'     => 'animation',
					'lottie_source' => 'file',
				),
			)
		);

		$this->add_control(
			'lottie_loop',
			array(
				'label'        => __( 'Loop', 'premium-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'default'      => 'true',
				'condition'    => array(
					'icon_type' => 'animation',
				),
			)
		);

		$this->add_control(
			'lottie_reverse',
			array(
				'label'        => __( 'Reverse', 'premium-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'condition'    => array(
					'icon_type' => 'animation',
				),
			)
		);

		$this->add_responsive_control(
			'alignment',
			array(
				'label'      => __( 'Position', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::CHOOSE,
				'options'    => array(
					'flex-start' => array(
						'title' => __( 'Start', 'premium-addons-for-elementor' ),
						'icon'  => is_rtl() ? 'eicon-h-align-right' : 'eicon-h-align-left',
					),
					'center'     => array(
						'title' => __( 'Center', 'premium-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-center',
					),
					'flex-end'   => array(
						'title' => __( 'End', 'premium-addons-for-elementor' ),
						'icon'  => is_rtl() ? 'eicon-h-align-left' : 'eicon-h-align-right',
					),
				),
				'default'    => 'center',
				'toggle'     => false,
				'selectors'  => array(
					'{{WRAPPER}}.pa-woo-mc__default .pa-woo-mc__outer-container' => 'justify-content: {{VALUE}}',
					'{{WRAPPER}}.pa-woo-mc__float .pa-woo-mc__inner-container' => 'align-items: {{VALUE}}',
				),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'  => 'placement',
							'value' => 'default',
						),
						array(
							'terms' => array(
								array(
									'name'  => 'placement',
									'value' => 'float',
								),
								array(
									'name'  => 'subtotal',
									'value' => 'yes',
								),
							),
						),
					),
				),
			)
		);

		$this->add_responsive_control(
			'spacing',
			array(
				'label'      => __( 'Spacing', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .pa-woo-mc__inner-container' => 'gap: {{SIZE}}{{UNIT}}',
				),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'terms' => array(
								array(
									'name'  => 'placement',
									'value' => 'default',
								),
								array(
									'name'     => 'presets',
									'operator' => '!==',
									'value'    => 'preset-2',
								),
							),
						),
						array(
							'terms' => array(
								array(
									'name'  => 'placement',
									'value' => 'float',
								),
								array(
									'name'  => 'subtotal',
									'value' => 'yes',
								),
							),
						),
					),
				),
			)
		);

		$this->add_control(
			'badge_switcher',
			array(
				'label'      => __( 'Count Badge', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::SWITCHER,
				'label_on'   => __( 'Show', 'premium-addons-for-elementor' ),
				'label_off'  => __( 'Hide', 'premium-addons-for-elementor' ),
				'separator'  => 'before',
				'default'    => 'yes',
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'  => 'placement',
							'value' => 'float',
						),
						array(
							'terms' => array(
								array(
									'name'  => 'placement',
									'value' => 'default',
								),
								array(
									'name'     => 'presets',
									'operator' => '!in',
									'value'    => array( 'preset-5', 'preset-7' ),
								),
							),
						),
					),
				),
			)
		);

		$this->add_control(
			'badge_hide_switcher',
			array(
				'label'        => __( 'Hide if cart is empty', 'premium-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'prefix_class' => 'pa-hide-empty-badge-',
				'default'      => 'yes',
				'conditions'   => array(
					'terms' => array(
						array(
							'name'  => 'badge_switcher',
							'value' => 'yes',
						),
						array(
							'relation' => 'or',
							'terms'    => array(
								array(
									'name'  => 'placement',
									'value' => 'float',
								),
								array(
									'terms' => array(
										array(
											'name'  => 'placement',
											'value' => 'default',
										),
										array(
											'name'     => 'presets',
											'operator' => '!in',
											'value'    => array( 'preset-5', 'preset-7' ),
										),
									),
								),
							),
						),
					),

				),
			)
		);

		$this->add_control(
			'subtotal',
			array(
				'label'     => __( 'Subtotal', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'separator' => 'before',
				'condition' => array(
					'placement' => 'float',
				),
			)
		);

		// $this->add_responsive_control(
		// 'icon_alignment',
		// array(
		// 'label'     => __( 'Icon Alignment', 'premium-addons-for-elementor' ),
		// 'type'      => Controls_Manager::CHOOSE,
		// 'options'   => array(
		// 'flex-start' => array(
		// 'title' => __( 'Left', 'premium-addons-for-elementor' ),
		// 'icon'  => 'eicon-h-align-left',
		// ),
		// 'center'     => array(
		// 'title' => __( 'Center', 'premium-addons-for-elementor' ),
		// 'icon'  => 'eicon-h-align-center',
		// ),
		// 'flex-end'   => array(
		// 'title' => __( 'Right', 'premium-addons-for-elementor' ),
		// 'icon'  => 'eicon-h-align-right',
		// ),
		// ),
		// 'default'   => 'center',
		// 'toggle'    => false,
		// 'selectors' => array(
		// '{{WRAPPER}} .pa-woo-mc__icon-wrapper' => 'justify-content: {{VALUE}}',
		// ),
		// 'condition' => array(
		// 'display' => 'column',
		// ),
		// )
		// );

		// $this->add_responsive_control(
		// 'icon_order',
		// array(
		// 'label'     => __( 'Icon Order', 'premium-addons-for-elementor' ),
		// 'type'      => Controls_Manager::CHOOSE,
		// 'toggle'    => false,
		// 'options'   => array(
		// '0' => array(
		// 'title' => __( 'First', 'premium-addons-for-elementor' ),
		// 'icon'  => 'eicon-order-start',
		// ),
		// '2' => array(
		// 'title' => __( 'Last', 'premium-addons-for-elementor' ),
		// 'icon'  => 'eicon-order-end',
		// ),
		// ),
		// 'default'   => '2',
		// 'selectors' => array(
		// '{{WRAPPER}} .pa-woo-mc__icon-wrapper' => 'order: {{VALUE}}',
		// ),
		// )
		// );

		// $this->add_responsive_control(
		// 'txt_spacing',
		// array(
		// 'label'      => __( 'Cart Text Spacing', 'premium-addons-for-elementor' ),
		// 'type'       => Controls_Manager::SLIDER,
		// 'size_units' => array( 'px', 'em' ),
		// 'selectors'  => array(
		// '{{WRAPPER}} .pa-woo-mc__text-wrapper' => 'gap: {{SIZE}}{{UNIT}}',
		// ),
		// 'condition'  => array(
		// 'subtotal'  => 'yes',
		// ),
		// )
		// );

		$this->add_control(
			'odometer_effect',
			array(
				'label'        => apply_filters( 'pa_pro_label', __( 'Counting Effect (Pro)', 'premium-addons-for-elementor' ) ),
				'type'         => Controls_Manager::SWITCHER,
				'render_type'  => 'template',
				'separator'    => 'before',
				'prefix_class' => 'premium-mc-counting-',
			)
		);

		$this->add_control(
			'show_tax_label',
			array(
				'label'        => __( 'Tax Label', 'premium-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'premium-addons-for-elementor' ),
				'label_off'    => __( 'Hide', 'premium-addons-for-elementor' ),
				'separator'    => 'before',
				'prefix_class' => 'pa-trigger-label-',
				'render_type'  => 'template',
				'description'  => __( 'Displays the tax label next to the subtotal amount when the ', 'premium-addons-for-elementor' ) . sprintf( __( '<a href="%s" target="_blank">Enable Taxes</a>', 'premium-addons-for-elementor' ), esc_url( admin_url( 'admin.php?page=wc-settings&tab=general' ) ) ) . __( ' option is enabled.', 'premium-addons-for-elementor' ),
				'condition'    => array(
					'presets!' => array( 'preset-1', 'preset-2' ),
				),
			)
		);

		$this->add_control(
			'behaviour',
			array(
				'label'     => __( 'Icon Click Behaviour', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'separator' => 'before',
				'options'   => array(
					'toggle' => __( 'Opens Mini Cart List', 'premium-addons-for-elementor' ),
					'url'    => apply_filters( 'pa_pro_label', __( 'Redirect To Cart Page (Pro)', 'premium-addons-for-elementor' ) ),
				),
				'default'   => 'toggle',
			)
		);

		$this->add_control(
			'render_on_cart_checkout',
			array(
				'label'     => __( 'Open Mini Cart on Cart/Checkout', 'premium-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'render_type'  => 'template',
				'condition' => array(
					'behaviour' => 'toggle',
				),
			)
		);

		$this->add_control(
			'cart_link',
			array(
				'label'     => __( 'URL', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::URL,
				'dynamic'   => array( 'active' => true ),
				'default'   => array(
					'url' => get_permalink( wc_get_page_id( 'cart' ) ),
				),
				'condition' => array(
					'behaviour' => 'url',
				),
			)
		);

		$this->add_control(
			'woo_cta_connect',
			array(
				'label'       => __( 'Connect To Premium Woo CTA', 'premium-addons-for-elementor' ),
				'type'        => Controls_Manager::SWITCHER,
				'description' => __( 'Use this option to open the cart menu every time a product is added to cart using Premium Woo CTA widget.', 'premium-addons-for-elementor' ),
				'condition'   => array(
					'behaviour' => 'url',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Mini Cart controls for the widget settings panel.
	 *
	 * @since 4.7.0
	 * @access private
	 *
	 * @return void
	 */
	private function add_mini_cart_ctrls() {

		$slide_connected_conds = array(
			'relation' => 'or',
			'terms'    => array(
				array(
					'terms' => array(
						array(
							'name'  => 'behaviour',
							'value' => 'toggle',
						),
						array(
							'name'  => 'placement',
							'value' => 'float',
						),
					),
				),
				array(
					'terms' => array(
						array(
							'name'  => 'behaviour',
							'value' => 'toggle',
						),
						array(
							'name'  => 'cart_type',
							'value' => 'slide',
						),
					),
				),
				array(
					'terms' => array(
						array(
							'name'  => 'behaviour',
							'value' => 'url',
						),
						array(
							'name'  => 'woo_cta_connect',
							'value' => 'yes',
						),
					),
				),
			),
		);

		$this->start_controls_section(
			'mini_cart_sec',
			array(
				'label'      => __( 'Mini Cart', 'premium-addons-for-elementor' ),
				'conditions' => $this->render_mc_conds,
			)
		);

		// This will only show if the behavior is to toggle.
		$this->add_control(
			'cart_type',
			array(
				'label'       => __( 'Type', 'premium-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'separator'   => 'before',
				'render_type' => 'template',
				'options'     => array(
					'slide' => __( 'Slide Menu', 'premium-addons-for-elementor' ),
					'menu'  => apply_filters( 'pa_pro_label', __( 'Mini Window (Pro)', 'premium-addons-for-elementor' ) ),
				),
				'default'     => 'slide',
				'condition'   => array(
					'behaviour' => 'toggle',
					'placement' => 'default',
				),
			)
		);

		do_action( 'pa_woo_mini_cart_window_controls', $this );

		$this->add_control(
			'cart_dir',
			array(
				'label'              => __( 'Direction', 'premium-addons-for-elementor' ),
				'prefix_class'       => 'pa-slide-dir-',
				'type'               => Controls_Manager::CHOOSE,
				'toggle'             => false,
				'options'            => array(
					'left'  => array(
						'title' => __( 'Left', 'premium-addons-for-elementor' ),
						'icon'  => is_rtl() ? 'eicon-order-end' : 'eicon-order-start',
					),
					'right' => array(
						'title' => __( 'Right', 'premium-addons-for-elementor' ),
						'icon'  => is_rtl() ? 'eicon-order-start' : 'eicon-order-end',
					),
				),
				'default'            => 'right',
				'selectors_dictionary'   => array(
					'left'  => 'left: 0; transform: translateX(calc(-1 * var(--pa-slide-mc-width))); -webkit-transform: translateX(calc(-1 * var(--pa-slide-mc-width))); -ms-transform: translateX(calc(-1 * var(--pa-slide-mc-width)))',
					'right' => 'right:0; transform: translateX(var(--pa-slide-mc-width)); -webkit-transform: translateX(var(--pa-slide-mc-width)); -ms-transform: translateX(var(--pa-slide-mc-width))',
				),
				'selectors'          => array(
					'{{WRAPPER}} .pa-woo-mc__content-wrapper' => '{{VALUE}}',
				),
				'conditions'         => $slide_connected_conds,
			)
		);

		$this->add_control(
			'cart_css_selector',
			array(
				'label'       => __( 'CSS Selector', 'premium-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => __( 'Add the CSS selector of the element that will trigger the Mini Cart. For example, #element-id or .element-class', 'premium-addons-for-elementor' ),
				'label_block' => true,
				'render_type' => 'template',
				'condition'   => array(
					'cart_type'  => 'slide',
					'behaviour!' => 'url',
				),
			)
		);

		$this->add_control(
			'content_layout',
			array(
				'label'        => __( 'Layout', 'premium-addons-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'prefix_class' => 'pa-woo-mc__',
				'render_type'  => 'template',
				'options'      => array(
					'layout-1' => __( 'Layout 1', 'premium-addons-for-elementor' ),
					'layout-2' => apply_filters( 'pa_pro_label', __( 'Layout 2 (Pro)', 'premium-addons-for-elementor' ) ),
					'layout-3' => __( 'Layout 3', 'premium-addons-for-elementor' ),
					'layout-4' => __( 'Layout 4', 'premium-addons-for-elementor' ),
				),
				'default'      => 'layout-1',
			)
		);

		$this->add_control(
			'content_layout_notice',
			array(
				'raw'             => __( 'The mini cart layout will be applied to all the Premium Mini Cart widgets used in your site.', 'premium-addons-for-elementor' ),
				'type'            => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			)
		);

		$this->add_responsive_control(
			'menu_width',
			array(
				'label'      => __( 'Width', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vw', '%', 'custom' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}}' => '--pa-slide-mc-width: {{SIZE}}{{UNIT}};'
				),
			)
		);

		$this->add_responsive_control(
			'menu_height',
			array(
				'label'      => __( 'Height', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vh', 'custom' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 500,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .pa-woo-mc__items-wrapper' => 'height: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'cart_type' => 'menu',
				),
			)
		);

		$this->add_control(
			'slide_overlay',
			array(
				'label'      => __( 'Overlay', 'premium-addons-pro' ),
				'type'       => Controls_Manager::SWITCHER,
				'default'    => 'yes',
				'conditions' => $slide_connected_conds,
			)
		);

		$this->add_control(
			'overlay_color',
			array(
				'label'      => __( 'Overlay Color', 'premium-addons-pro' ),
				'type'       => Controls_Manager::COLOR,
				'default'    => 'rgba(0,0,0,0.5)',
				'selectors'  => array(
					'.pa-woo-mc__overlay-{{ID}}' => 'background-color: {{VALUE}}',
				),
				'conditions' => array(
					'terms' => array(
						array(
							'name'  => 'slide_overlay',
							'value' => 'yes',
						),
						$slide_connected_conds,
					),
				),
			)
		);

		$this->add_control(
			'transition',
			array(
				'label'      => __( 'Transition Duration (Sec)', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'separator'  => 'before',
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 10,
						'step' => 0.1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__content-wrapper' => 'transition-duration: {{SIZE}}s',
				)
			)
		);

		$this->add_control(
			'close_delay',
			array(
				'label'      => __( 'Transition Delay (Sec)', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 10,
						'step' => 0.1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__content-wrapper' => 'transition-delay: {{SIZE}}s',
				)
			)
		);

		$this->add_control(
			'close_on_outside',
			array(
				'label'      => __( 'Close On Click Outside Content', 'premium-addons-pro' ),
				'type'       => Controls_Manager::SWITCHER,
				'default'    => 'yes',
				'separator'  => 'before',
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'  => 'cart_type',
							'value' => 'slide',
						),
						array(
							'terms' => array(
								array(
									'name'  => 'cart_type',
									'value' => 'menu',
								),
								array(
									'name'  => 'trigger',
									'value' => 'click',
								),
							),
						),
					),
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Free Shipping Progress Bar controls.
	 *
	 * @since 4.7.0
	 * @access private
	 *
	 * @return void
	 */
	private function add_mc_progressbar_ctrls() {

		$this->start_controls_section(
			'progressbar_section',
			array(
				'label'      => __( 'Free Shipping Progress Bar', 'premium-addons-for-elementor' ),
				'conditions' => $this->render_mc_conds,
			)
		);

		$this->add_control(
			'mc_progressbar',
			array(
				'label'       => apply_filters( 'pa_pro_label', __( 'Free Shipping Progress Bar (Pro)', 'premium-addons-for-elementor' ) ),
				'type'        => Controls_Manager::SWITCHER,
				'separator'   => 'before',
				'description' => __( 'Please note that the progressbar will only be displayed if <b>Free Shipping</b> is set as your shipping method..', 'premium-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'progressbar_txt',
			array(
				'label'       => __( 'Text', 'premium-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'render_type' => 'template',
				'default'     => 'Spend {{thershold}} to Get Free Shipping',
				'description' => __( 'Use this option to add a text of your choice, and use the {{thershold}} placeholder to add the free shipping minium amount.', 'premium-addons-for-elementor' ),
				'label_block' => true,
				'dynamic'     => array( 'active' => true ),
				'condition'   => array(
					'mc_progressbar' => 'yes',
				),
			)
		);

		$this->add_control(
			'complete_txt',
			array(
				'label'       => __( 'Complete Text', 'premium-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'render_type' => 'template',
				'default'     => 'Congratulations! You\'ve Got Free Shipping.',
				'label_block' => true,
				'dynamic'     => array( 'active' => true ),
				'condition'   => array(
					'mc_progressbar' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'progressbar_spacing',
			array(
				'label'     => __( 'Spacing', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 500,
						'step' => 1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__progressbar-wrapper' => 'gap: {{SIZE}}px',
				),
				'condition' => array(
					'mc_progressbar' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'progressbar_height',
			array(
				'label'     => __( 'Bar Height', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 500,
						'step' => 1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__progressbar' => 'height: {{SIZE}}px',
				),
				'condition' => array(
					'mc_progressbar' => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Cross-sells controls for the Mini Cart widget.
	 *
	 * @since 4.7.0
	 * @access private
	 *
	 * @return void
	 */
	private function add_mc_cross_sells_ctrls() {

		$this->start_controls_section(
			'cross_sells_section',
			array(
				'label'      => __( 'Cross Sells Listing', 'premium-addons-for-elementor' ),
				'conditions' => $this->render_mc_conds,
			)
		);

		$this->add_control(
			'cross_sells',
			array(
				'label'        => __( 'Cross-sells Section', 'premium-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'render_type'  => 'template',
				'prefix_class' => 'pa-show-cross-sells-',
			)
		);

		$this->add_control(
			'cross_sells_txt',
			array(
				'label'       => __( 'Cross-sells Text', 'premium-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'render_type' => 'template',
				'label_block' => true,
				'default'     => 'You may be interested in…',
				'dynamic'     => array( 'active' => true ),
				'condition'   => array(
					'cross_sells' => 'yes',
				),
			)
		);

		$this->add_control(
			'arrows_order',
			array(
				'label'     => __( 'Arrows Position', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'toggle'    => false,
				'options'   => array(
					'2' => array(
						'title' => __( 'start', 'premium-addons-for-elementor' ),
						'icon'  => is_rtl() ? 'eicon-order-end' : 'eicon-order-start',
					),
					'0' => array(
						'title' => __( 'End', 'premium-addons-for-elementor' ),
						'icon'  => is_rtl() ? 'eicon-order-start' : 'eicon-order-end',
					),
				),
				'default'   => '0',
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__cross-sells-heading' => 'order: {{VALUE}}',
				),
				'condition' => array(
					'cross_sells' => 'yes',
				),
			)
		);

		$this->add_control(
			'slides_to_show',
			array(
				'label'     => __( 'Slides To Show', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 3,
				'condition' => array(
					'cross_sells' => 'yes',
				),
			)
		);

		$this->add_control(
			'slides_to_scroll',
			array(
				'label'     => __( 'Slides To Scroll', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 1,
				'condition' => array(
					'cross_sells' => 'yes',
				),
			)
		);

		$this->add_control(
			'auto_play',
			array(
				'label'     => __( 'Autoplay', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'condition' => array(
					'cross_sells' => 'yes',
				),
			)
		);

		$this->add_control(
			'autoplay_speed',
			array(
				'label'     => __( 'Autoplay Speed (ms)', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 5000,
				'condition' => array(
					'auto_play'   => 'yes',
					'cross_sells' => 'yes',
				),
			)
		);

		$this->add_control(
			'speed',
			array(
				'label'       => __( 'Animation Speed (ms)', 'premium-addons-for-elementor' ),
				'description' => __( 'Set the speed of the animation in milliseconds (ms)', 'premium-addons-for-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 1000,
				'render_type' => 'template',
				'condition'   => array(
					'cross_sells' => 'yes',
				),
			)
		);

		$this->add_control(
			'carousel_arrows',
			array(
				'label'        => __( 'Navigation Arrows', 'premium-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'prefix_class' => 'pa-show-cs-arrows-',
				'condition'    => array(
					'cross_sells' => 'yes',
				),
			)
		);

		// $this->add_responsive_control(
		// 'carousel_spacing',
		// array(
		// 'label'     => __( 'Carousel Spacing', 'premium-addons-for-elementor' ),
		// 'type'      => Controls_Manager::SLIDER,
		// 'range'     => array(
		// 'px' => array(
		// 'min'  => 0,
		// 'max'  => 500,
		// 'step' => 1,
		// ),
		// ),
		// 'selectors' => array(
		// '{{WRAPPER}} .slick-slide' => 'padding: 0 {{SIZE}}px; box-sizing: border-box;',
		// )
		// )
		// );

		$this->end_controls_section();
	}

	/**
	 * Register listing controls for the Mini Cart widget.
	 *
	 * @since 4.7.0
	 * @access private
	 *
	 * @return void
	 */
	private function add_mc_listing_ctrls() {

		$this->start_controls_section(
			'mc_listing_section',
			array(
				'label'      => __( 'Product Listing', 'premium-addons-for-elementor' ),
				'conditions' => $this->render_mc_conds,
			)
		);

		$this->add_control(
			'thumb_heading',
			array(
				'label'     => __( 'Thumbnail', 'premium-addons-for-elementor' ),
				'separator' => 'before',
				'type'      => Controls_Manager::HEADING,
			)
		);

		$this->add_responsive_control(
			'item_thumb',
			array(
				'label'      => __( 'Image Size', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 1000,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .pa-woo-mc__product-thumbnail'  => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'thumb_fit',
			array(
				'label'     => __( 'Image Fit', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					''        => __( 'Default', 'premium-addons-pro' ),
					'fill'    => __( 'Fill', 'premium-addons-pro' ),
					'cover'   => __( 'Cover', 'premium-addons-pro' ),
					'contain' => __( 'Contain', 'premium-addons-pro' ),
				),
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__product-thumbnail img' => 'object-fit: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'thumb_info_spacing',
			array(
				'label'      => __( 'Spacing', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 500,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .pa-woo-mc__item-wrapper' => 'column-gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'product_info_heading',
			array(
				'label'     => __( 'Product Info', 'premium-addons-for-elementor' ),
				'separator' => 'before',
				'type'      => Controls_Manager::HEADING,
			)
		);

		$this->add_responsive_control(
			'v_align',
			array(
				'label'                => __( 'Vertical Alignment', 'premium-addons-for-elementor' ),
				'type'                 => Controls_Manager::CHOOSE,
				'options'              => array(
					'start'   => array(
						'title' => __( 'Top', 'premium-addons-for-elementor' ),
						'icon'  => 'eicon-align-start-v',
					),
					'center'  => array(
						'title' => __( 'Center', 'premium-addons-for-elementor' ),
						'icon'  => 'eicon-align-center-v',
					),
					'end'     => array(
						'title' => __( 'Bottom', 'premium-addons-for-elementor' ),
						'icon'  => 'eicon-align-end-v',
					),
					'stretch' => array(
						'title' => __( 'Stretch', 'premium-addons-for-elementor' ),
						'icon'  => 'eicon-align-stretch-v',
					),
				),
				'selectors_dictionary' => array(
					'start'   => 'align-self: flex-start',
					'center'  => 'align-self: center',
					'end'     => 'align-self: flex-end',
					'stretch' => 'justify-content: space-between',
				),
				'default'              => 'stretch',
				'toggle'               => false,
				'selectors'            => array(
					'{{WRAPPER}} .pa-woo-mc__product-data' => '{{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'product_info_spacing',
			array(
				'label'      => __( 'Spacing', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .pa-woo-mc__product-data' => 'gap: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'v_align!' => '',
				),
			)
		);

		$this->add_control(
			'remove_icon',
			array(
				'label'        => __( 'Remove Item', 'premium-addons-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'separator'    => 'before',
				'default'      => 'yes',
				'prefix_class' => 'pa-woo-mc__remove-icon-',
			)
		);

		$this->add_responsive_control(
			'remove_type',
			array(
				'label'        => __( 'Type', 'premium-addons-for-elementor' ),
				'type'         => Controls_Manager::CHOOSE,
				'prefix_class' => 'pa-show-trash-',
				'render_type'  => 'template',
				'options'      => array(
					'text' => array(
						'title' => __( 'Text', 'premium-addons-for-elementor' ),
						'icon'  => 'eicon-pencil',
					),
					'icon' => array(
						'title' => __( 'Icon', 'premium-addons-for-elementor' ),
						'icon'  => 'eicon-trash-o',
					),
				),
				'default'      => 'icon',
				'toggle'       => false,
				'condition'    => array(
					'remove_icon'    => 'yes',
					'content_layout' => array( 'layout-1', 'layout-2' ),
				),
			)
		);

		$this->add_control(
			'remove_txt',
			array(
				'label'       => __( 'Text', 'premium-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'render_type' => 'template',
				'default'     => 'Remove',
				'label_block' => true,
				'dynamic'     => array( 'active' => true ),
				'condition'   => array(
					'remove_icon'    => 'yes',
					'remove_type'    => 'text',
					'content_layout' => array( 'layout-1', 'layout-2' ),
				),
			)
		);

		$this->add_responsive_control(
			'remove_icon_size',
			array(
				'label'      => __( 'Icon Size', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}}:not(.pa-woo-mc__layout-3) .pa-woo-mc__remove-item' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .pa-woo-mc__remove-item i' => 'font-size: {{SIZE}}{{UNIT}};',
				),
				'conditions' => array(
					'terms' => array(
						array(
							'name'  => 'remove_icon',
							'value' => 'yes',
						),
						array(
							'relation' => 'or',
							'terms'    => array(
								array(
									'terms' => array(
										array(
											'name'  => 'remove_type',
											'value' => 'icon',
										),
										array(
											'name'     => 'content_layout',
											'operator' => 'in',
											'value'    => array( 'layout-1', 'layout-2' ),
										),
									),
								),
								array(
									'name'     => 'content_layout',
									'operator' => '!in',
									'value'    => array( 'layout-1', 'layout-2' ),
								),
							),
						),
					),
				),
			)
		);

		$this->add_responsive_control(
			'remove_icon_align',
			array(
				'label'       => __( 'Alignment', 'premium-addons-for-elementor' ),
				'description' => __( 'This option works better when the product title is displayed on more than one line.', 'premium-addons-for-elementor' ),
				'type'        => Controls_Manager::CHOOSE,
				'options'     => array(
					'flex-start' => array(
						'title' => __( 'Top', 'premium-addons-for-elementor' ),
						'icon'  => 'eicon-v-align-top',
					),
					'center'     => array(
						'title' => __( 'Center', 'premium-addons-for-elementor' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'flex-end'   => array(
						'title' => __( 'Bottom', 'premium-addons-for-elementor' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'default'     => 'flex-start',
				'toggle'      => false,
				'selectors'   => array(
					'{{WRAPPER}} .pa-woo-mc__remove-item' => 'align-self: {{VALUE}};',
				),
				'condition'   => array(
					'remove_icon'    => 'yes',
					'content_layout' => 'layout-1',
				),
			)
		);

		$this->add_responsive_control(
			'qty_input_width',
			array(
				'label'      => __( 'Quantity Width', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'separator'  => 'before',
				'selectors'  => array(
					'{{WRAPPER}} .pa-woo-mc__input' => 'width: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'content_layout' => array( 'layout-1', 'layout-2' ),
				),
			)
		);

		$this->add_control(
			'qty_controls',
			array(
				'label'        => __( 'Quantity Controls', 'premium-addons-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'separator'    => 'before',
				'default'      => 'yes',
				'prefix_class' => 'pa-woo-mc__qty-btn-',
				'condition'    => array(
					'content_layout' => array( 'layout-1', 'layout-2' ),
				),
			)
		);

		$this->add_responsive_control(
			'qty_controls_size',
			array(
				'label'      => __( 'Controls Size', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .pa-woo-mc__qty-btn' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'qty_controls'   => 'yes',
					'content_layout' => array( 'layout-1', 'layout-2' ),
				),
			)
		);

		$this->add_control(
			'allow_backorders',
			array(
				'label'        => __( 'Allow Backorders', 'premium-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'prefix_class' => 'pa-allow-bkorders-',
				'description'  => __( 'Allow customers to order quantities beyond available stock.', 'premium-addons-for-elementor' ),
				'condition'    => array(
					'qty_controls'   => 'yes',
					'content_layout' => array( 'layout-1', 'layout-2' ),
				),
			)
		);

		$this->add_control(
			'separator',
			array(
				'label'        => __( 'Items Divider', 'premium-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'prefix_class' => 'pa-mc-separator-',
				'default'      => 'yes',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'divider_style',
			array(
				'label'     => __( 'Style', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'solid'  => __( 'Solid', 'premium-addons-for-elementor' ),
					'double' => __( 'Double', 'premium-addons-for-elementor' ),
					'dotted' => __( 'Dotted', 'premium-addons-for-elementor' ),
					'dashed' => __( 'Dashed', 'premium-addons-for-elementor' ),
				),
				'default'   => 'solid',
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__item-divider' => 'border-style: {{VALUE}};',
				),
				'condition' => array(
					'separator' => 'yes',
				),

			)
		);

		$this->add_control(
			'pa_txt_color_divider_color',
			array(
				'label'     => __( 'Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__item-divider' => 'border-color: {{VALUE}}',
				),
				'condition' => array(
					'cart_txt!' => '',
				),
				'condition' => array(
					'separator' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'divider_height',
			array(
				'label'     => __( 'Thickness', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__item-divider' => 'border-top-width: {{SIZE}}px;',
				),
				'condition' => array(
					'separator' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'divider_hor_spacing',
			array(
				'label'      => __( 'Divider Horizontal Spacing', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}}.pa-mc-separator-yes .pa-woo-mc__item-divider'  => 'margin-inline: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'separator' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'items_spacing',
			array(
				'label'      => __( 'Items Spacing', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}}:not(.pa-mc-separator-yes) .pa-woo-mc__items-wrapper'  => 'row-gap: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.pa-mc-separator-yes .pa-woo-mc__item-divider'  => 'margin-top: {{SIZE}}{{UNIT}}; margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register header controls for the Mini Cart widget.
	 *
	 * @since 4.7.0
	 * @access private
	 *
	 * @return void
	 */
	private function add_mc_header_ctrls() {

		$slide_connected_conds = array(
			'relation' => 'or',
			'terms'    => array(
				array(
					'terms' => array(
						array(
							'name'  => 'behaviour',
							'value' => 'toggle',
						),
						array(
							'name'  => 'placement',
							'value' => 'float',
						),
					),
				),
				array(
					'terms' => array(
						array(
							'name'  => 'behaviour',
							'value' => 'toggle',
						),
						array(
							'name'  => 'cart_type',
							'value' => 'slide',
						),
					),
				),
				array(
					'terms' => array(
						array(
							'name'  => 'behaviour',
							'value' => 'url',
						),
						array(
							'name'  => 'woo_cta_connect',
							'value' => 'yes',
						),
					),
				),
			),
		);

		$this->start_controls_section(
			'mc_header_section',
			array(
				'label'      => __( 'Mini-Cart Header', 'premium-addons-for-elementor' ),
				'conditions' => $this->render_mc_conds,
			)
		);

		$this->add_control(
			'cart_title',
			array(
				'label'       => __( 'Title', 'premium-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'Your Cart',
				'label_block' => true,
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->add_control(
			'close_icon',
			array(
				'label'                  => __( 'Select an Icon', 'premium-addons-for-elementor' ),
				'type'                   => Controls_Manager::ICONS,
				'label_block'            => false,
				'exclude_inline_options' => 'none',
				'default'                => array(
					'value'   => 'fas fa-times',
					'library' => 'fa-solid',
				),
				'skin'                   => 'inline',
				'condition'              => array(
					'content_layout!' => array( 'layout-3', 'layout-4' ),
				),
			)
		);

		$this->add_responsive_control(
			'close_icon_size',
			array(
				'label'      => __( 'Icon Size', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .pa-woo-mc__close-button i' => 'font-size: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .pa-woo-mc__close-button svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'close_icon_order',
			array(
				'label'     => __( 'Icon Position', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'toggle'    => false,
				'options'   => array(
					'2' => array(
						'title' => __( 'left', 'premium-addons-for-elementor' ),
						'icon'  => 'eicon-order-start',
					),
					'0' => array(
						'title' => __( 'Right', 'premium-addons-for-elementor' ),
						'icon'  => 'eicon-order-end',
					),
				),
				'default'   => '0',
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__cart-title' => 'order: {{VALUE}}',
				),
				'condition' => array(
					'content_layout!' => array( 'layout-3', 'layout-4' ),
					'cart_title!'     => '',
				),
			)
		);

		$this->add_control(
			'icon_pos',
			array(
				'label'                => __( 'Icon Position', 'premium-addons-for-elementor' ),
				'type'                 => Controls_Manager::CHOOSE,
				'toggle'               => false,
				'options'              => array(
					'margin-right' => array(
						'title' => __( 'start', 'premium-addons-for-elementor' ),
						'icon'  => is_rtl() ? 'eicon-order-end' : 'eicon-order-start',
					),
					'margin-left'  => array(
						'title' => __( 'End', 'premium-addons-for-elementor' ),
						'icon'  => is_rtl() ? 'eicon-order-start' : 'eicon-order-end',
					),
				),
				'default'              => 'margin-left',
				'selectors_dictionary' => array(
					'margin-right' => 'margin-inline-end',
					'margin-left'  => 'margin-inline-start',
				),
				'selectors'            => array(
					'{{WRAPPER}} .pa-woo-mc__close-button' => '{{VALUE}}: auto',
				),
				'condition'            => array(
					'content_layout!' => array( 'layout-3', 'layout-4' ),
					'cart_title'      => '',
				),
				// 'conditions' => array(
				// 'relation' => 'or',
				// 'terms'    => array(
				// array(
				// 'name'     => 'cart_title',
				// 'value'    => '',
				// ),
				// array(
				// 'name'     => 'content_layout',
				// 'operator' => '!in',
				// 'value'    => array( 'layout-3', 'layout-4' ),
				// ),
				// ),
				// ),
			)
		);

		$this->add_control(
			'header_item_count',
			array(
				'label'       => __( 'Item Count Text', 'premium-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'render_type' => 'template',
				'default'     => 'Items',
				'label_block' => true,
				'dynamic'     => array( 'active' => true ),
				'condition'   => array(
					'content_layout' => 'layout-3',
				),
			)
		);

		$this->add_responsive_control(
			'title_pos',
			array(
				'label'     => __( 'Alignment', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start' => array(
						'title' => __( 'Start', 'premium-addons-for-elementor' ),
						'icon'  => is_rtl() ? 'eicon-h-align-right' : 'eicon-h-align-left',
					),
					'center'     => array(
						'title' => __( 'Center', 'premium-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-center',
					),
					'flex-end'   => array(
						'title' => __( 'End', 'premium-addons-for-elementor' ),
						'icon'  => is_rtl() ? 'eicon-h-align-left' : 'eicon-h-align-right',
					),
				),
				'default'   => 'center',
				'toggle'    => false,
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__cart-header' => 'justify-content: {{VALUE}}',
				),
				'condition' => array(
					'cart_title!'     => '',
					'placement'       => 'default',
					'cart_type'       => 'menu',
					'content_layout!' => 'layout-3',
				),
			)
		);

		$this->add_responsive_control(
			'title_pos_layout3',
			array(
				'label'      => __( 'Alignment', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::CHOOSE,
				'options'    => array(
					'flex-start' => array(
						'title' => __( 'Start', 'premium-addons-for-elementor' ),
						'icon'  => is_rtl() ? 'eicon-h-align-right' : 'eicon-h-align-left',
					),
					'center'     => array(
						'title' => __( 'Center', 'premium-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-center',
					),
					'flex-end'   => array(
						'title' => __( 'End', 'premium-addons-for-elementor' ),
						'icon'  => is_rtl() ? 'eicon-h-align-left' : 'eicon-h-align-right',
					),
				),
				'default'    => 'center',
				'toggle'     => false,
				'selectors'  => array(
					'{{WRAPPER}} .pa-woo-mc__cart-header' => 'justify-content: {{VALUE}}',
				),
				'conditions' => array(
					'terms' => array(
						array(
							'name'     => 'cart_title',
							'operator' => '!==',
							'value'    => '',
						),
						array(
							'relation' => 'or',
							'terms'    => array(
								array(
									'name'  => 'content_layout',
									'value' => 'layout-4',
								),
								array(
									'terms' => array(
										array(
											'name'  => 'content_layout',
											'value' => 'layout-3',
										),
										array(
											'name'  => 'header_item_count',
											'value' => '',
										),
									),
								),
							),
						),
					),
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register footer controls for the Mini Cart widget.
	 *
	 * @since 4.7.0
	 * @access private
	 *
	 * @return void
	 */
	private function add_mc_footer_ctrls() {

		$this->start_controls_section(
			'mc_footer_section',
			array(
				'label'      => __( 'Mini-Cart Footer', 'premium-addons-for-elementor' ),
				'conditions' => $this->render_mc_conds,
			)
		);

		$this->add_control(
			'remove_all_btn',
			array(
				'label'       => __( 'Remove All', 'premium-addons-pro' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'yes',
				'description' => __( 'Use this option to empty your cart.', 'premium-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'remove_all_txt',
			array(
				'label'       => __( 'Text', 'premium-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'render_type' => 'template',
				'default'     => __( 'Remove All Items', 'premium-addons-for-elementor' ),
				'label_block' => true,
				'dynamic'     => array( 'active' => true ),
				'condition'   => array(
					'remove_all_btn' => 'yes',
				),
			)
		);

		$this->add_control(
			'footer_subtotal',
			array(
				'label'     => __( 'Subtotal', 'premium-addons-pro' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'subtotal_txt',
			array(
				'label'       => __( 'Text', 'premium-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'render_type' => 'template',
				'default'     => __( 'Subtotal {{count}} items', 'premium-addons-for-elementor' ),
				'description' => __( 'Use this option to add a text of your choice, and use the {{count}} placeholder to add your items\' count.', 'premium-addons-for-elementor' ),
				'label_block' => true,
				'dynamic'     => array( 'active' => true ),
				'condition'   => array(
					'footer_subtotal' => 'yes',
					'content_layout!' => 'layout-3',
				),
			)
		);

		$this->add_control(
			'show_footer_tax_label',
			array(
				'label'        => __( 'Tax Label', 'premium-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'premium-addons-for-elementor' ),
				'label_off'    => __( 'Hide', 'premium-addons-for-elementor' ),
				'prefix_class' => 'pa-footer-label-',
				'render_type'  => 'template',
				'description'  => __( 'Displays the tax label next to the subtotal amount when the ', 'premium-addons-for-elementor' ) . sprintf( __( '<a href="%s" target="_blank">Enable Taxes</a>', 'premium-addons-for-elementor' ), esc_url( admin_url( 'admin.php?page=wc-settings&tab=general' ) ) ) . __( ' option is enabled.', 'premium-addons-for-elementor' ),
				'condition'    => array(
					'footer_subtotal' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'footer_subtotal_pos',
			array(
				'label'     => __( 'Position', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start' => array(
						'title' => __( 'Start', 'premium-addons-for-elementor' ),
						'icon'  => is_rtl() ? 'eicon-h-align-right' : 'eicon-h-align-left',
					),
					'center'     => array(
						'title' => __( 'Center', 'premium-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-center',
					),
					'flex-end'   => array(
						'title' => __( 'End', 'premium-addons-for-elementor' ),
						'icon'  => is_rtl() ? 'eicon-h-align-left' : 'eicon-h-align-right',
					),
				),
				'default'   => 'flex-end',
				'toggle'    => false,
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__cart-subtotal' => 'justify-content: {{VALUE}}',
				),
				'condition' => array(
					'footer_subtotal' => 'yes',
					'subtotal_txt'    => '',
				),
			)
		);

		$this->add_control(
			'footer_subtotal_order',
			array(
				'label'     => __( 'Order', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'0' => array(
						'title' => __( 'Default', 'premium-addons-for-elementor' ),
						'icon'  => 'eicon-order-start',
					),
					'2' => array(
						'title' => __( 'Reverse', 'premium-addons-for-elementor' ),
						'icon'  => 'eicon-order-end',
					),
				),
				'default'   => '0',
				'toggle'    => false,
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__subtotal-heading' => 'order: {{VALUE}}',
				),
				'condition' => array(
					'footer_subtotal' => 'yes',
					'subtotal_txt!'   => '',
				),
			)
		);

		$this->add_control(
			'view_cart',
			array(
				'label'     => __( 'View Cart', 'premium-addons-pro' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'checkout',
			array(
				'label'   => __( 'Checkout', 'premium-addons-pro' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_responsive_control(
			'cart_btn_display',
			array(
				'label'      => __( 'Display', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::CHOOSE,
				'options'    => array(
					'nowrap' => array(
						'title' => __( 'Inline', 'premium-addons-for-elementor' ),
						'icon'  => 'eicon-ellipsis-h',
					),
					'wrap'   => array(
						'title' => __( 'Block', 'premium-addons-for-elementor' ),
						'icon'  => 'eicon-ellipsis-v',
					),
				),
				'default'    => 'nowrap',
				'toggle'     => false,
				'selectors'  => array(
					'{{WRAPPER}} .pa-woo-mc__cart-buttons' => 'flex-wrap: {{VALUE}}',
				),
				'conditions' => array(
					'terms' => array(
						array(
							'name'  => 'checkout',
							'value' => 'yes',
						),
						array(
							'name'  => 'view_cart',
							'value' => 'yes',
						),
					),
				),
			)
		);

		$this->add_control(
			'cart_btn_order',
			array(
				'label'      => __( 'Order', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::CHOOSE,
				'options'    => array(
					'0' => array(
						'title' => __( 'Default', 'premium-addons-for-elementor' ),
						'icon'  => 'eicon-order-start',
					),
					'2' => array(
						'title' => __( 'Reverse', 'premium-addons-for-elementor' ),
						'icon'  => 'eicon-order-end',
					),
				),
				'default'    => '0',
				'toggle'     => false,
				'selectors'  => array(
					'{{WRAPPER}} .pa-woo-mc__view-cart' => 'order: {{VALUE}}',
				),
				'conditions' => array(
					'terms' => array(
						array(
							'name'  => 'checkout',
							'value' => 'yes',
						),
						array(
							'name'  => 'view_cart',
							'value' => 'yes',
						),
					),
				),
			)
		);

		$this->add_responsive_control(
			'cart_btn_spacing',
			array(
				'label'      => __( 'Spacing', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .pa-woo-mc__cart-buttons' => 'gap: {{SIZE}}{{UNIT}}',
				),
				'conditions' => array(
					'terms' => array(
						array(
							'name'  => 'checkout',
							'value' => 'yes',
						),
						array(
							'name'  => 'view_cart',
							'value' => 'yes',
						),
					),
				),
			)
		);

		$this->add_control(
			'coupon',
			array(
				'label'     => apply_filters( 'pa_pro_label', __( 'Coupon Form (Pro)', 'premium-addons-for-elementor' ) ),
				'type'      => Controls_Manager::SWITCHER,
				'separator' => 'before',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register help documents controls for the Mini Cart widget.
	 *
	 * @since 4.7.0
	 * @access private
	 *
	 * @return void
	 */
	private function add_help_docs_tab() {

		$this->start_controls_section(
			'section_pa_docs',
			array(
				'label' => __( 'Help & Docs', 'premium-addons-for-elementor' ),
			)
		);

		$doc1_url = Helper_Functions::get_campaign_link( 'https://premiumaddons.com/docs/elementor-woocommerce-mini-cart-widget-tutorial/', 'editor-page', 'wp-editor', 'get-support' );
		$doc2_url = Helper_Functions::get_campaign_link( 'https://premiumaddons.com/docs/how-to-enable-elementor-woo-mini-cart-custom-template/', 'editor-page', 'wp-editor', 'get-support' );

		$this->add_control(
			'doc_1',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => sprintf( '<a href="%s" target="_blank">%s</a>', $doc1_url, __( 'Getting started »', 'premium-addons-for-elementor' ) ),
				'content_classes' => 'editor-pa-doc',
			)
		);

		$this->add_control(
			'doc_2',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => sprintf( '<a href="%s" target="_blank">%s</a>', $doc2_url, __( 'How to Enable Elementor Woo Mini Cart Custom Template »', 'premium-addons-for-elementor' ) ),
				'content_classes' => 'editor-pa-doc',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register trigger style controls for the Mini Cart widget.
	 *
	 * @since 4.7.0
	 * @access private
	 *
	 * @return void
	 */
	private function add_trigger_style() {

		$subtotal_conds = array(
			'relation' => 'or',
			'terms'    => array(
				array(
					'terms' => array(
						array(
							'name'  => 'placement',
							'value' => 'float',
						),
						array(
							'name'  => 'subtotal',
							'value' => 'yes',
						),
					),
				),
				array(
					'terms' => array(
						array(
							'name'  => 'placement',
							'value' => 'default',
						),
						array(
							'name'     => 'presets',
							'operator' => '!in',
							'value'    => array( 'preset-1', 'preset-2' ),
						),
					),
				),
			),
		);

		$this->start_controls_section(
			'trigger_style',
			array(
				'label' => __( 'Trigger', 'premium-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'     => __( 'Icon Size', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 500,
						'step' => 1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__icon-wrapper i' => 'font-size: {{SIZE}}px',
					'{{WRAPPER}} .pa-woo-mc__icon-wrapper svg, .pa-woo-mc__icon-wrapper .premium-lottie-animation' => 'width: {{SIZE}}px; height: {{SIZE}}px',
				),
				'condition' => array(
					'icon_type' => array( 'animation', 'svg', 'icon' ),
				),
			)
		);

		$this->add_control(
			'separator_color',
			array(
				'label'      => __( 'Separator Color', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::COLOR,
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 500,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .pa-woo-mc__icon-sep' => 'color: {{SIZE}}px',
					'{{WRAPPER}}.pa-woo-mc__preset-7 .pa-woo-mc__text-wrapper' => 'border-color: {{SIZE}}px',
				),
				'conditions' => array(
					'terms' => array(
						array(
							'name'  => 'placement',
							'value' => 'default',
						),
						array(
							'name'     => 'presets',
							'operator' => 'in',
							'value'    => array( 'preset-5', 'preset-7' ),
						),
					),
				),
			)
		);

		$this->add_responsive_control(
			'badge_width',
			array(
				'label'      => __( 'Badge Size', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'selectors'  => array(
					'{{WRAPPER}} .pa-woo-mc__badge' => 'width: {{SIZE}}px; height: {{SIZE}}px;',
				),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'  => 'placement',
							'value' => 'float',
						),
						array(
							'terms' => array(
								array(
									'name'  => 'placement',
									'value' => 'default',
								),
								array(
									'name'     => 'presets',
									'operator' => '!in',
									'value'    => array( 'preset-5', 'preset-7' ),
								),
							),
						),
					),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'badge_typo',
				'label'    => __( 'Badge Typography', 'premium-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .pa-woo-mc__badge',
			)
		);

		$this->add_control(
			'badge_rad',
			array(
				'label'      => __( 'Badge Border Radius', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pa-woo-mc__badge' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
				'conditions' => array(
					'terms' => array(
						array(
							'name'  => 'badge_switcher',
							'value' => 'yes',
						),
						array(
							'relation' => 'or',
							'terms'    => array(
								array(
									'name'  => 'placement',
									'value' => 'float',
								),
								array(
									'terms' => array(
										array(
											'name'  => 'placement',
											'value' => 'default',
										),
										array(
											'name'     => 'presets',
											'operator' => '!in',
											'value'    => array( 'preset-2', 'preset-4', 'preset-6' ),
										),
									),
								),
							),
						),
					),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'       => 'subtotal_typo',
				'label'      => __( 'Subtotal Typography', 'premium-addons-for-elementor' ),
				'selector'   => '{{WRAPPER}}  .pa-woo-mc__inner-container .pa-woo-mc__subtotal, {{WRAPPER}}.premium-mc-counting-yes  .pa-woo-mc__inner-container .pa-woo-mc__subtotal *',
				'conditions' => $subtotal_conds,
			)
		);

		$this->add_responsive_control(
			'trigger_cont_size',
			array(
				'label'      => __( 'Container Size', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .pa-woo-mc__inner-container' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'placement' => 'default',
					'presets'   => 'preset-2',
				),
			)
		);

		$this->start_controls_tabs(
			'trigger_style_tabs'
		);

		$this->start_controls_tab(
			'triggle_style_normal',
			array(
				'label' => __( 'Normal', 'premium-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'pa_txt_color_subtotal',
			array(
				'label'      => __( 'Subtotal Color', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => array(
					'{{WRAPPER}} .pa-woo-mc__inner-container .pa-woo-mc__subtotal' => 'color: {{VALUE}}',
				),
				'conditions' => $subtotal_conds,
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'label'     => __( 'Icon Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__icon-wrapper i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .pa-woo-mc__icon-wrapper svg, {{WRAPPER}} .pa-woo-mc__icon-wrapper svg *' => 'fill: {{VALUE}};',
				),
				'condition' => array(
					'icon_type' => array( 'svg', 'icon' ),
				),
			)
		);

		$this->add_control(
			'pa_btn_bg_icon',
			array(
				'label'     => __( 'Icon Background Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__icon-wrapper' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'badge_heading',
			array(
				'label'     => __( 'Count Badge', 'premium-addons-for-elementor' ),
				'separator' => 'before',
				'type'      => Controls_Manager::HEADING,
				'condition' => array(
					'badge_switcher' => 'yes',
				),
			)
		);

		$this->add_control(
			'pa_btn_color_badge',
			array(
				'label'     => __( 'Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__badge' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'badge_switcher' => 'yes',
				),
			)
		);

		$this->add_control(
			'pa_btn_bg_badge',
			array(
				'label'     => __( 'Background Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__badge' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'badge_switcher' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'pa_border_color_badge',
				'selector'  => '{{WRAPPER}} .pa-woo-mc__badge',
				'condition' => array(
					'badge_switcher' => 'yes',
				),
			)
		);

		$this->add_control(
			'cont_heading',
			array(
				'label'     => __( 'Container', 'premium-addons-for-elementor' ),
				'separator' => 'before',
				'type'      => Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			Premium_Background::get_type(),
			array(
				'name'     => 'pa_btn_bg',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .pa-woo-mc__inner-container',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'pa_border_color_cont',
				'selector' => '{{WRAPPER}} .pa-woo-mc__inner-container',
			)
		);

		$this->add_responsive_control(
			'cont_border_rad',
			array(
				'label'      => __( 'Border Radius', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pa-woo-mc__inner-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'cont_padding',
			array(
				'label'      => __( 'Padding', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pa-woo-mc__inner-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'triggle_style_hov',
			array(
				'label' => __( 'Hover', 'premium-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'pa_txt_color_hov',
			array(
				'label'     => __( 'Cart Text Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__link:hover .pa-woo-mc__text,
					{{WRAPPER}} .pa-woo-mc__inner-container:hover .pa-woo-mc__text' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'cart_txt!' => '',
				),
			)
		);

		$this->add_control(
			'pa_txt_color_subtotal_hov',
			array(
				'label'      => __( 'Subtotal Color', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => array(
					'{{WRAPPER}} .pa-woo-mc__link:hover .pa-woo-mc__subtotal,
					{{WRAPPER}} .pa-woo-mc__inner-container:hover .pa-woo-mc__subtotal' => 'color: {{VALUE}}',
				),
				'conditions' => $subtotal_conds,
			)
		);

		$this->add_control(
			'icon_color_hov',
			array(
				'label'     => __( 'Icon Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__link:hover .pa-woo-mc__icon-wrapper i,
					{{WRAPPER}} .pa-woo-mc__inner-container:hover .pa-woo-mc__icon-wrapper i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .pa-woo-mc__link:hover .pa-woo-mc__icon-wrapper svg,
					{{WRAPPER}} .pa-woo-mc__inner-container:hover .pa-woo-mc__icon-wrapper svg,
					{{WRAPPER}} .pa-woo-mc__inner-container:hover .pa-woo-mc__icon-wrapper svg *' => 'fill: {{VALUE}};',
				),
				'condition' => array(
					'icon_type' => array( 'svg', 'icon' ),
				),
			)
		);

		$this->add_control(
			'pa_btn_bg_icon_hov',
			array(
				'label'     => __( 'Icon Background Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__inner-container:hover .pa-woo-mc__icon-wrapper' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'badge_heading_hov',
			array(
				'label'     => __( 'Count Badge', 'premium-addons-for-elementor' ),
				'separator' => 'before',
				'type'      => Controls_Manager::HEADING,
				'condition' => array(
					'badge_switcher' => 'yes',
				),
			)
		);

		$this->add_control(
			'pa_btn_color_badge_hov',
			array(
				'label'     => __( 'Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__link:hover .pa-woo-mc__badge,
					{{WRAPPER}} .pa-woo-mc__inner-container:hover .pa-woo-mc__badge' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'badge_switcher' => 'yes',
				),
			)
		);

		$this->add_control(
			'pa_btn_bg_hover_badge',
			array(
				'label'     => __( 'Background Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__link:hover .pa-woo-mc__badge,
					 {{WRAPPER}} .pa-woo-mc__inner-container:hover .pa-woo-mc__badge' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'badge_switcher' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'pa_border_color_badge_hov',
				'selector'  => '{{WRAPPER}} .pa-woo-mc__link:hover .pa-woo-mc__badge, {{WRAPPER}} .pa-woo-mc__inner-container:hover .pa-woo-mc__badge',
				'condition' => array(
					'badge_switcher' => 'yes',
				),
			)
		);

		$this->add_control(
			'cont_heading_hov',
			array(
				'label'     => __( 'Container', 'premium-addons-for-elementor' ),
				'separator' => 'before',
				'type'      => Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			Premium_Background::get_type(),
			array(
				'name'     => 'pa_btn_bg_hover',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .pa-woo-mc__inner-container:hover, {{WRAPPER}} .pa-woo-mc__link:hover .pa-woo-mc__inner-container',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'pa_border_color_cont_hov',
				'selector' => '{{WRAPPER}} .pa-woo-mc__inner-container:hover, {{WRAPPER}} .pa-woo-mc__link:hover .pa-woo-mc__inner-container',
			)
		);

		$this->add_responsive_control(
			'cont_border_rad_hov',
			array(
				'label'      => __( 'Border Radius', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pa-woo-mc__inner-container:hover, {{WRAPPER}} .pa-woo-mc__link:hover .pa-woo-mc__inner-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'cont_padding_hov',
			array(
				'label'      => __( 'Padding', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pa-woo-mc__inner-container:hover, {{WRAPPER}} .pa-woo-mc__link:hover .pa-woo-mc__inner-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Register cart containers style controls for the Mini Cart widget.
	 *
	 * @since 4.7.0
	 * @access private
	 *
	 * @return void
	 */
	private function add_cart_containers_style() {

		$this->start_controls_section(
			'cart_conts_style_sec',
			array(
				'label'      => __( 'Cart Containers', 'premium-addons-for-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'  => 'behaviour',
							'value' => 'toggle',
						),
						array(
							'terms' => array(
								array(
									'name'  => 'behaviour',
									'value' => 'url',
								),
								array(
									'name'  => 'woo_cta_connect',
									'value' => 'yes',
								),
							),
						),
					),
				),
			)
		);

		$this->add_control(
			'items_style_heading',
			array(
				'label'     => __( 'Items', 'premium-addons-for-elementor' ),
				'separator' => 'before',
				'type'      => Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			Premium_Background::get_type(),
			array(
				'name'     => 'pa_ele_bg_item_cont',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .pa-woo-mc__content-wrapper:not(.pa-hide-content) .pa-woo-mc__items-wrapper',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'pa_border_color_item_cont',
				'selector' => '{{WRAPPER}} .pa-woo-mc__content-wrapper:not(.pa-hide-content) .pa-woo-mc__items-wrapper',
			)
		);

		$this->add_responsive_control(
			'item_cont_padding',
			array(
				'label'      => __( 'Padding', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pa-woo-mc__content-wrapper:not(.pa-hide-content) .pa-woo-mc__items-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'mc_cont_style_heading',
			array(
				'label'     => __( 'Mini Cart', 'premium-addons-for-elementor' ),
				'separator' => 'before',
				'type'      => Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			Premium_Background::get_type(),
			array(
				'name'     => 'pa_ele_bg_outer_cont',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .pa-woo-mc__content-wrapper',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'pa_border_color_outer_cont',
				'selector' => '{{WRAPPER}} .pa-woo-mc__content-wrapper',
			)
		);

		do_action( 'pa_woo_mini_cart_window_style_controls', $this );

		$this->add_responsive_control(
			'outer_cont_padding',
			array(
				'label'      => __( 'Padding', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pa-woo-mc__content-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register coupon style controls for the Mini Cart widget.
	 *
	 * @since 4.7.0
	 * @access private
	 *
	 * @return void
	 */
	private function add_coupon_style() {

		$this->start_controls_section(
			'coupon_style_sec',
			array(
				'label'      => __( 'Coupon', 'premium-addons-for-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'conditions' => array(
					'terms' => array(
						array(
							'name'  => 'coupon',
							'value' => 'yes',
						),
						$this->render_mc_conds,
					),
				),
			)
		);

		$this->add_control(
			'coupon_txt_heading',
			array(
				'label'     => __( 'Text', 'premium-addons-for-elementor' ),
				'separator' => 'before',
				'type'      => Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'coupon_typo',
				'selector' => '{{WRAPPER}} .pa-woo-mc__coupon-toggler',
			)
		);

		$this->add_control(
			'pa_heading_color_coupon',
			array(
				'label'     => __( 'Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__coupon-toggler' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'coupon_input_heading',
			array(
				'label'     => __( 'Input Field', 'premium-addons-for-elementor' ),
				'separator' => 'before',
				'type'      => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'coupon_ph_color',
			array(
				'label'     => __( 'Placeholder Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__coupon-field' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'coupon_shadow',
				'selector' => '{{WRAPPER}} .pa-woo-mc__coupon-field',
			)
		);

		$this->add_group_control(
			Premium_Background::get_type(),
			array(
				'name'     => 'pa_ele_bg_coupon',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .pa-woo-mc__coupon-field',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'pa_border_color_field',
				'selector' => '{{WRAPPER}} .pa-woo-mc__coupon-field',
			)
		);

		$this->add_responsive_control(
			'field_rad',
			array(
				'label'      => __( 'Border Radius', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pa-woo-mc__coupon-field' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'field_padding',
			array(
				'label'      => __( 'Padding', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .pa-woo-mc__coupon-field' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'field_margin',
			array(
				'label'      => __( 'Margin', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .pa-woo-mc__coupon-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'coupon_submit_heading',
			array(
				'label'     => __( 'Submit Button', 'premium-addons-for-elementor' ),
				'separator' => 'before',
				'type'      => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'pa_btn_color_submit',
			array(
				'label'     => __( 'Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__coupon-submit svg *' => 'stroke: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'pa_btn_color_hov_submit',
			array(
				'label'     => __( 'Hover Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__coupon-submit:hover svg *' => 'stroke: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'submit_size',
			array(
				'label'      => __( 'Size', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pa-woo-mc__coupon-submit svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'submit_pos',
			array(
				'label'      => __( 'Position', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pa-woo-mc__coupon-submit' => 'inset-inline-end: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'coupon_cont_heading',
			array(
				'label'     => __( 'Container', 'premium-addons-for-elementor' ),
				'separator' => 'before',
				'type'      => Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			Premium_Background::get_type(),
			array(
				'name'     => 'pa_ele_bg_coupon_cont',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .pa-woo-mc__coupon-sec-wrapper',
			)
		);

		$this->add_responsive_control(
			'coupon_container_padd',
			array(
				'label'      => __( 'Padding', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'separator'  => 'before',
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pa-woo-mc__coupon-sec-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'coupon_container_marg',
			array(
				'label'      => __( 'Margin', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pa-woo-mc__coupon-sec-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register cross sell style controls for the Mini Cart widget.
	 *
	 * @since 4.7.0
	 * @access private
	 *
	 * @return void
	 */
	private function add_cross_sells_style() {

		$this->start_controls_section(
			'cross_style_sec',
			array(
				'label'      => __( 'Cross Sells Listing', 'premium-addons-for-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'conditions' => array(
					'terms' => array(
						array(
							'name'  => 'cross_sells',
							'value' => 'yes',
						),
						$this->render_mc_conds,
					),
				),
			)
		);

		$this->add_control(
			'crossell_txt_heading',
			array(
				'label'     => __( 'Text', 'premium-addons-for-elementor' ),
				'separator' => 'before',
				'type'      => Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'cross_sells_typo',
				'selector' => '{{WRAPPER}} .pa-woo-mc__cross-sells-heading',
			)
		);

		$this->add_control(
			'pa_heading_color_crossels',
			array(
				'label'     => __( 'Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__cross-sells-heading' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'cross_border',
			array(
				'label'     => __( 'Separator Thickness', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 500,
						'step' => 1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__cross-sells-heading-wrapper' => 'border-bottom-width: {{SIZE}}px',
				),
			)
		);

		$this->add_control(
			'pa_border_color_crosssells',
			array(
				'label'     => __( 'Separator Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__cross-sells-heading-wrapper' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'crossell_nav_heading',
			array(
				'label'     => __( 'Arrows', 'premium-addons-for-elementor' ),
				'separator' => 'before',
				'type'      => Controls_Manager::HEADING,
			)
		);

		$this->add_responsive_control(
			'cross_nav_size',
			array(
				'label'     => __( 'Size', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 500,
						'step' => 1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__cross-sells-arrows a' => 'width: {{SIZE}}px; height: {{SIZE}}px',
				),
			)
		);

		$this->add_control(
			'crosssells_nav',
			array(
				'label'     => __( 'Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__cross-sells-arrows a svg' => 'fill: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'crossell_product_heading',
			array(
				'label'     => __( 'Product', 'premium-addons-for-elementor' ),
				'separator' => 'before',
				'type'      => Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'cross_product_title_typo',
				'selector' => '{{WRAPPER}} .pa-woo-mc__cross-sell-title',
			)
		);

		$this->add_control(
			'pa_heading_color_cross_product',
			array(
				'label'     => __( 'Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__cross-sell-title' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'cross_img_size',
			array(
				'label'           => __( 'Thumbnail Size', 'premium-addons-for-elementor' ),
				'type'            => Controls_Manager::SLIDER,
				'render_template' => 'template',
				'range'           => array(
					'px' => array(
						'min'  => 0,
						'max'  => 500,
						'step' => 1,
					),
				),
				'selectors'       => array(
					'{{WRAPPER}} .pa-woo-mc__cross-sell-thumbnail' => 'width: {{SIZE}}px; height: {{SIZE}}px',
				),
			)
		);

		$this->add_responsive_control(
			'cross_container',
			array(
				'label'      => __( 'Container Padding', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'separator'  => 'before',
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pa-woo-mc__cross-sells-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}


	private function add_items_style() {

		$this->start_controls_section(
			'items_style_sec',
			array(
				'label'      => __( 'Product Listing', 'premium-addons-for-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'  => 'behaviour',
							'value' => 'toggle',
						),
						array(
							'terms' => array(
								array(
									'name'  => 'behaviour',
									'value' => 'url',
								),
								array(
									'name'  => 'woo_cta_connect',
									'value' => 'yes',
								),
							),
						),
					),
				),
			)
		);

		$this->add_control(
			'thumb_style_heading',
			array(
				'label'     => __( 'Thumbnail', 'premium-addons-for-elementor' ),
				'separator' => 'before',
				'type'      => Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'img_shadow',
				'selector' => '{{WRAPPER}} .pa-woo-mc__product-thumbnail img',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'pa_border_color_img',
				'selector' => '{{WRAPPER}} .pa-woo-mc__product-thumbnail img',
			)
		);

		$this->add_responsive_control(
			'img_rad',
			array(
				'label'      => __( 'Border Radius', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pa-woo-mc__product-thumbnail img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'title_style_heading',
			array(
				'label'     => __( 'Title', 'premium-addons-for-elementor' ),
				'separator' => 'before',
				'type'      => Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'product_title_typo',
				'selector' => '{{WRAPPER}} .pa-woo-mc__title',
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => __( 'Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__title' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'title_color_hov',
			array(
				'label'     => __( 'Hover Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__title:hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'trash_style_heading',
			array(
				'label'     => __( 'Remove Icon', 'premium-addons-for-elementor' ),
				'separator' => 'before',
				'type'      => Controls_Manager::HEADING,
				'condition' => array(
					'remove_icon' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'remove_txt_typo',
				'label'     => __( 'Typography', 'premium-addons-for-elementor' ),
				'selector'  => '{{WRAPPER}} .pa-woo-mc__remove-item span',
				'condition' => array(
					'remove_icon' => 'yes',
				),
			)
		);

		$this->add_control(
			'pa_btn_color_remove',
			array(
				'label'     => __( 'Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__remove-item svg, {{WRAPPER}} .pa-woo-mc__remove-item svg *' => 'fill: {{VALUE}}',
					'{{WRAPPER}} .pa-woo-mc__remove-item span, {{WRAPPER}} .pa-woo-mc__remove-item i' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'remove_icon' => 'yes',
				),
			)
		);

		$this->add_control(
			'pa_btn_color_hov_remove',
			array(
				'label'     => __( 'Hover Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__remove-item:hover svg, {{WRAPPER}} .pa-woo-mc__remove-item:hover svg *' => 'fill: {{VALUE}}',
					'{{WRAPPER}} .pa-woo-mc__remove-item:hover span, {{WRAPPER}} .pa-woo-mc__remove-item:hover i' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'remove_icon' => 'yes',
				),
			)
		);

		$this->add_control(
			'price_style_heading',
			array(
				'label'     => __( 'Price', 'premium-addons-for-elementor' ),
				'separator' => 'before',
				'type'      => Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'price_typo',
				'label'    => __( 'Typography', 'premium-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .pa-woo-mc__item-price',
			)
		);

		$this->add_control(
			'pa_txt_color_price',
			array(
				'label'     => __( 'Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__item-price' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'qty_style_heading',
			array(
				'label'     => __( 'Quantity', 'premium-addons-for-elementor' ),
				'separator' => 'before',
				'type'      => Controls_Manager::HEADING,
				'condition' => array(
					'content_layout' => array( 'layout-1', 'layout-2' ),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'qty_typo',
				'label'     => __( 'Typography', 'premium-addons-for-elementor' ),
				'selector'  => '{{WRAPPER}} .pa-woo-mc__input[type="number"]',
				'condition' => array(
					'content_layout' => array( 'layout-1', 'layout-2' ),
				),
			)
		);

		$this->add_control(
			'pa_txt_color_qty',
			array(
				'label'     => __( 'Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__input[type="number"]' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'content_layout' => array( 'layout-1', 'layout-2' ),
				),
			)
		);

		$this->add_control(
			'qty_cta_style_heading',
			array(
				'label'     => __( '+/- Buttons', 'premium-addons-for-elementor' ),
				'separator' => 'before',
				'type'      => Controls_Manager::HEADING,
				'condition' => array(
					'content_layout' => array( 'layout-1', 'layout-2' ),
				),
			)
		);

		$this->start_controls_tabs(
			'qty_cta_tabs',
		);

		$this->start_controls_tab(
			'qty_cta_normal',
			array(
				'label'     => __( 'Normal', 'premium-addons-for-elementor' ),
				'condition' => array(
					'content_layout' => array( 'layout-1', 'layout-2' ),
				),
			)
		);

		$this->add_control(
			'pa_btn_color_qta_cta',
			array(
				'label'     => __( 'Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__qty-btn, {{WRAPPER}} .pa-woo-mc__qty-btn *' => 'fill: {{VALUE}}',
				),
				'condition' => array(
					'qty_controls'   => 'yes',
					'content_layout' => array( 'layout-1', 'layout-2' ),
				),
			)
		);

		$this->add_control(
			'pa_btn_bg_qty_cta',
			array(
				'label'     => __( 'Background Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__qty-btn' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'qty_controls'   => 'yes',
					'content_layout' => array( 'layout-1', 'layout-2' ),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'qta_btn_shadow',
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .pa-woo-mc__qty-btn',
				'condition' => array(
					'qty_controls'   => 'yes',
					'content_layout' => array( 'layout-1', 'layout-2' ),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'pa_border_color_qta_cta',
				'selector'  => '{{WRAPPER}} .pa-woo-mc__qty-btn',
				'condition' => array(
					'qty_controls'   => 'yes',
					'content_layout' => array( 'layout-1', 'layout-2' ),
				),
			)
		);

		$this->add_responsive_control(
			'qta_btn_rad',
			array(
				'label'      => __( 'Border Radius', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pa-woo-mc__qty-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'qty_controls'   => 'yes',
					'content_layout' => array( 'layout-1', 'layout-2' ),
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'qta_btn_tab_hov',
			array(
				'label'     => __( 'Hover', 'premium-addons-for-elementor' ),
				'condition' => array(
					'content_layout' => array( 'layout-1', 'layout-2' ),
				),
			)
		);

		$this->add_control(
			'pa_btn_color_hov',
			array(
				'label'     => __( 'Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__qty-btn:hover' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'qty_controls'   => 'yes',
					'content_layout' => array( 'layout-1', 'layout-2' ),
				),
			)
		);

		$this->add_control(
			'pa_btn_color_qta_cta_hov',
			array(
				'label'     => __( 'Background Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__qty-btn:hover, {{WRAPPER}} .pa-woo-mc__qty-btn:hover *' => 'fill: {{VALUE}}',
				),
				'condition' => array(
					'qty_controls'   => 'yes',
					'content_layout' => array( 'layout-1', 'layout-2' ),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'qty_btn_shadow_hov',
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .pa-woo-mc__qty-btn:hover',
				'condition' => array(
					'qty_controls'   => 'yes',
					'content_layout' => array( 'layout-1', 'layout-2' ),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'pa_border_color_qty_cta_hov',
				'selector'  => '{{WRAPPER}} .pa-woo-mc__qty-btn:hover',
				'condition' => array(
					'qty_controls'   => 'yes',
					'content_layout' => array( 'layout-1', 'layout-2' ),
				),
			)
		);

		$this->add_responsive_control(
			'qty_btn_rad_hov',
			array(
				'label'      => __( 'Border Radius', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pa-woo-mc__qty-btn:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'qty_controls'   => 'yes',
					'content_layout' => array( 'layout-1', 'layout-2' ),
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'qty_btn_padding',
			array(
				'label'      => __( 'Padding', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'separator'  => 'before',
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pa-woo-mc__qty-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'qty_controls'   => 'yes',
					'content_layout' => array( 'layout-1', 'layout-2' ),
				),
			)
		);

		$this->add_control(
			'qty_cont_heading',
			array(
				'label'     => __( 'Quantity Container', 'premium-addons-for-elementor' ),
				'separator' => 'before',
				'type'      => Controls_Manager::HEADING,
				'condition' => array(
					'content_layout' => array( 'layout-1', 'layout-2' ),
				),
			)
		);

		$this->add_control(
			'pa_ele_bg_qty-cont',
			array(
				'label'     => __( 'Background Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__item-qty' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'content_layout' => array( 'layout-1', 'layout-2' ),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'qty_cont_shadow',
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .pa-woo-mc__item-qty',
				'condition' => array(
					'content_layout' => array( 'layout-1', 'layout-2' ),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'pa_border_color_qty_cont',
				'selector'  => '{{WRAPPER}} .pa-woo-mc__item-qty',
				'condition' => array(
					'content_layout' => array( 'layout-1', 'layout-2' ),
				),
			)
		);

		$this->add_responsive_control(
			'qty_cont_rad',
			array(
				'label'      => __( 'Border Radius', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pa-woo-mc__item-qty' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'content_layout' => array( 'layout-1', 'layout-2' ),
				),
			)
		);

		$this->add_responsive_control(
			'qty_cont_padding',
			array(
				'label'      => __( 'Padding', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pa-woo-mc__item-qty' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'content_layout' => array( 'layout-1', 'layout-2' ),
				),
			)
		);

		$this->add_control(
			'pa_btn_bg_item_cont_hover',
			array(
				'label'     => __( 'Hover Background Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}}.pa-woo-mc__layout-4 .pa-woo-mc__item-wrapper:hover' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'content_layout' => 'layout-4',
				),
			)
		);

		$this->end_controls_section();
	}

	private function add_header_style() {

		$this->start_controls_section(
			'header_style',
			array(
				'label'      => __( 'Mini-Cart Header', 'premium-addons-for-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'conditions' => $this->render_mc_conds,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'cart_title_typo',
				'label'     => __( 'Title Typography', 'premium-addons-for-elementor' ),
				'selector'  => '{{WRAPPER}} .pa-woo-mc__cart-title',
				'condition' => array(
					'cart_title!' => '',
				),
			)
		);

		$this->add_control(
			'pa_heading_color',
			array(
				'label'     => __( 'Title Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__cart-title' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'cart_title!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'      => 'cart_title_shadow',
				'selector'  => '{{WRAPPER}} .pa-woo-mc__cart-title',
				'condition' => array(
					'cart_title!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'header_count_type',
				'label'     => __( 'Count Typography', 'premium-addons-for-elementor' ),
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .pa-woo-mc__cart-header .pa-woo-mc__cart-count',
				'condition' => array(
					'cart_title!'    => '',
					'content_layout' => 'layout-3',
				),
			)
		);

		$this->add_control(
			'pa_heading_color_cart_count',
			array(
				'label'     => __( 'Title Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__cart-header .pa-woo-mc__cart-count' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'cart_title!'    => '',
					'content_layout' => 'layout-3',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'      => 'header_cart_count_shadow',
				'selector'  => '{{WRAPPER}} .pa-woo-mc__cart-header .pa-woo-mc__cart-count',
				'condition' => array(
					'cart_title!'    => '',
					'content_layout' => 'layout-3',
				),
			)
		);

		$this->add_control(
			'cicon_heading',
			array(
				'label'     => __( 'Close Icon', 'premium-addons-for-elementor' ),
				'separator' => 'before',
				'type'      => Controls_Manager::HEADING,
			)
		);

		$this->start_controls_tabs(
			'cicon_tabs'
		);

		$this->start_controls_tab(
			'cicon_tab_normal',
			array(
				'label' => __( 'Normal', 'premium-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'pa_btn_color_cicon',
			array(
				'label'     => __( 'Icon Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__close-button i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .pa-woo-mc__close-button svg, {{WRAPPER}} .pa-woo-mc__close-button svg *' => 'fill: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'pa_btn_bg_cicon',
			array(
				'label'     => __( 'Background Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__close-button' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'cicon_shadow',
				'selector' => '{{WRAPPER}} .pa-woo-mc__close-button',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'pa_border_color_cicon',
				'selector' => '{{WRAPPER}} .pa-woo-mc__close-button',
			)
		);

		$this->add_responsive_control(
			'cicon_rad',
			array(
				'label'      => __( 'Border Radius', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pa-woo-mc__close-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'cicon_padding',
			array(
				'label'      => __( 'Padding', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pa-woo-mc__close-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'cicon_tab_hov',
			array(
				'label' => __( 'Hover', 'premium-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'pa_btn_color_cicon_hov',
			array(
				'label'     => __( 'Hover Icon Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__close-button:hover i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .pa-woo-mc__close-button:hover svg, {{WRAPPER}} .pa-woo-mc__close-button:hover svg *' => 'fill: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'pa_btn_bg_hover_cicon',
			array(
				'label'     => __( 'Background Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__close-button:hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'cicon_shadow_hov',
				'selector' => '{{WRAPPER}} .pa-woo-mc__close-button:hover',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'pa_border_color_cicon_hov',
				'selector' => '{{WRAPPER}} .pa-woo-mc__close-button:hover',
			)
		);

		$this->add_responsive_control(
			'cicon_rad_hov',
			array(
				'label'      => __( 'Border Radius', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pa-woo-mc__close-button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'cicon_padding_hov',
			array(
				'label'      => __( 'Padding', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pa-woo-mc__close-button:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'header_cont_heading',
			array(
				'label'     => __( 'Container', 'premium-addons-for-elementor' ),
				'separator' => 'before',
				'type'      => Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			Premium_Background::get_type(),
			array(
				'name'     => 'pa_btn_bg_header',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .pa-woo-mc__cart-header',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'pa_border_color_header',
				'selector' => '{{WRAPPER}} .pa-woo-mc__cart-header',
			)
		);

		$this->add_responsive_control(
			'header_padding',
			array(
				'label'      => __( 'Padding', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pa-woo-mc__cart-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	private function add_remove_all_style() {

		$this->start_controls_section(
			'remove_all_style',
			array(
				'label'      => __( 'Remove All Button', 'premium-addons-for-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'conditions' => array(
					'terms' => array(
						array(
							'name'  => 'remove_all_btn',
							'value' => 'yes',
						),
						$this->render_mc_conds,
					),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'remove_all_typo',
				'label'    => __( 'Typography', 'premium-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .pa-woo-mc__empty-mc .pa-woo-mc__remove-all-btn,
				{{WRAPPER}} .pa-woo-mc__empty-mc .pa-woo-mc__confirm-msg',
			)
		);

		$this->start_controls_tabs(
			'remove_all_btn_tabs',
		);

		$this->start_controls_tab(
			'remove_btn_normal',
			array(
				'label' => __( 'Normal', 'premium-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'pa_btn_color_remove_all',
			array(
				'label'     => __( 'Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__empty-mc .pa-woo-mc__remove-all-btn,
					{{WRAPPER}} .pa-woo-mc__empty-mc .pa-woo-mc__confirm-msg' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'pa_btn_color_remove_btns',
			array(
				'label'     => __( 'Yes/No Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__empty-mc .pa-woo-mc__confirm-btn' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Premium_Background::get_type(),
			array(
				'name'     => 'pa_btn_bg_remove_all',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .pa-woo-mc__empty-mc .pa-woo-mc__remove-all-btn',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'remove_btn_shadow',
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .pa-woo-mc__empty-mc .pa-woo-mc__remove-all-btn',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'pa_border_color_remove_cta',
				'selector' => '{{WRAPPER}} .pa-woo-mc__empty-mc .pa-woo-mc__remove-all-btn',
			)
		);

		$this->add_responsive_control(
			'remove_btn_rad',
			array(
				'label'      => __( 'Border Radius', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pa-woo-mc__empty-mc .pa-woo-mc__remove-all-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'remove_btn_tab_hov',
			array(
				'label' => __( 'Hover', 'premium-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'pa_btn_color_remove_hov',
			array(
				'label'     => __( 'Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__empty-mc .pa-woo-mc__remove-all-btn:hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'pa_btn_color_remove_btns_hov',
			array(
				'label'     => __( 'Yes/No Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__empty-mc .pa-woo-mc__confirm-btn:hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Premium_Background::get_type(),
			array(
				'name'     => 'pa_btn_bg_remove_hov',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .pa-woo-mc__empty-mc .pa-woo-mc__remove-all-btn:hover',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'remove_btn_shadow_hov',
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .pa-woo-mc__empty-mc .pa-woo-mc__remove-all-btn:hover',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'pa_border_color_remove_cta_hov',
				'selector' => '{{WRAPPER}} .pa-woo-mc__empty-mc .pa-woo-mc__remove-all-btn:hover',
			)
		);

		$this->add_responsive_control(
			'remove_btn_rad_hov',
			array(
				'label'      => __( 'Border Radius', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pa-woo-mc__empty-mc .pa-woo-mc__remove-all-btn:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	private function add_progressbar_style() {

		$this->start_controls_section(
			'progressbar_sec_style',
			array(
				'label'      => __( 'Free Shipping Progress Bar', 'premium-addons-for-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'conditions' => array(
					'terms' => array(
						array(
							'name'  => 'mc_progressbar',
							'value' => 'yes',
						),
						$this->render_mc_conds,
					),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'proress_typo',
				'label'     => __( 'Text Typography', 'premium-addons-for-elementor' ),
				'selector'  => '{{WRAPPER}} .pa-woo-mc__progress-heading',
				'condition' => array(
					'mc_progressbar' => 'yes',
				),
			)
		);

		$this->add_control(
			'pa_txt_color_progress',
			array(
				'label'     => __( 'Text Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__progress-heading' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'mc_progressbar' => 'yes',
				),
			)
		);

		$this->add_control(
			'pa_btn_bg_progress',
			array(
				'label'     => __( 'Value Indicator Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__progressbar-wrapper progress::-webkit-progress-value' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .pa-woo-mc__progressbar-wrapper progress::-moz-progress-bar' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}' => '@-webkit-keyframes progress-bar-move { 0% { background-position: 0 0; } 100% { background-position: 30px 30px; } } @keyframes progress-bar-move { 0% { background-position: 0 0; } 100% { background-position: 30px 30px; } }',
					'{{WRAPPER}} .pa-woo-mc__progressbar::-webkit-progress-bar' => 'background-color: #e6e6e6; background-image: -webkit-linear-gradient(315deg, rgba(255, 255, 255, 0.7) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.7) 50%, rgba(255, 255, 255, 0.7) 75%, transparent 75%, transparent); background-size: 30px 30px; animation: progress-bar-move 2s linear infinite reverse;',
				),
				'condition' => array(
					'mc_progressbar' => 'yes',
				),
			)
		);

		$this->add_control(
			'progress_rad',
			array(
				'label'      => __( 'Border Radius', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pa-woo-mc__progressbar' => 'border-radius: {{SIZE}}{{UNIT}}; overflow: hidden;',
				),
				'condition'  => array(
					'mc_progressbar' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'progress_container_marg',
			array(
				'label'      => __( 'Container Margin', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'separator'  => 'before',
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pa-woo-mc__progressbar-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	private function add_footer_style() {

		$this->start_controls_section(
			'footer_style',
			array(
				'label'      => __( 'Mini-Cart Footer', 'premium-addons-for-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'conditions' => $this->render_mc_conds,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'footer_sub_text_typo',
				'label'     => __( 'Subtotal Text Typography', 'premium-addons-for-elementor' ),
				'selector'  => '{{WRAPPER}} .pa-woo-mc__subtotal-heading',
				'condition' => array(
					'footer_subtotal' => 'yes',
					'subtotal_txt!'   => '',
				),
			)
		);

		$this->add_control(
			'pa_txt_color_footer_heading',
			array(
				'label'     => __( 'Subtotal Text Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__subtotal-heading' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'footer_subtotal' => 'yes',
					'subtotal_txt!'   => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'footer_amount_typo',
				'label'     => __( 'Subtotal Amount Typography', 'premium-addons-for-elementor' ),
				'selector'  => '{{WRAPPER}} .pa-woo-mc__cart-footer .pa-woo-mc__subtotal',
				'condition' => array(
					'footer_subtotal' => 'yes',
				),
			)
		);

		$this->add_control(
			'pa_txt_color_footer_amount',
			array(
				'label'     => __( 'Subtotal Amount Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__cart-footer .pa-woo-mc__subtotal' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'footer_subtotal' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Premium_Background::get_type(),
			array(
				'name'      => 'pa_ele_bg',
				'types'     => array( 'classic', 'gradient' ),
				'selector'  => '{{WRAPPER}} .pa-woo-mc__cart-subtotal',
				'condition' => array(
					'view_cart' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'footer_sub_padding',
			array(
				'label'      => __( 'Padding', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'separator'  => 'before',
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .pa-woo-mc__cart-subtotal' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'footer_btn_heading',
			array(
				'label'     => __( 'Cart CTA', 'premium-addons-for-elementor' ),
				'separator' => 'before',
				'type'      => Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'       => 'footer_btn_typo',
				'selector'   => '{{WRAPPER}} .pa-woo-mc__btn-txt',
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'  => 'checkout',
							'value' => 'yes',
						),
						array(
							'name'  => 'view_cart',
							'value' => 'yes',
						),
					),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'       => 'footer_btn_shadow',
				'selector'   => '{{WRAPPER}} .pa-woo-mc__btn-txt',
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'  => 'checkout',
							'value' => 'yes',
						),
						array(
							'name'  => 'view_cart',
							'value' => 'yes',
						),
					),
				),
			)
		);

		$this->start_controls_tabs(
			'footer_btn_tabs',
		);

		$this->start_controls_tab(
			'footer_btn_normal',
			array(
				'label'      => __( 'Normal', 'premium-addons-for-elementor' ),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'  => 'checkout',
							'value' => 'yes',
						),
						array(
							'name'  => 'view_cart',
							'value' => 'yes',
						),
					),
				),
			)
		);

		$this->add_control(
			'vcart_heading',
			array(
				'label'     => __( 'View Cart', 'premium-addons-for-elementor' ),
				'separator' => 'before',
				'type'      => Controls_Manager::HEADING,
				'condition' => array(
					'view_cart' => 'yes',
				),
			)
		);

		$this->add_control(
			'pa_btn_color_vcart',
			array(
				'label'     => __( 'Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__view-cart .pa-woo-mc__btn-txt' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'view_cart' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Premium_Background::get_type(),
			array(
				'name'      => 'pa_btn_bg_vcart',
				'types'     => array( 'classic', 'gradient' ),
				'selector'  => '{{WRAPPER}} .pa-woo-mc__view-cart',
				'condition' => array(
					'view_cart' => 'yes',
				),
			)
		);

		$this->add_control(
			'checkout_heading',
			array(
				'label'     => __( 'Checkout', 'premium-addons-for-elementor' ),
				'separator' => 'before',
				'type'      => Controls_Manager::HEADING,
				'condition' => array(
					'checkout' => 'yes',
				),
			)
		);

		$this->add_control(
			'pa_btn_color_checkout',
			array(
				'label'     => __( 'Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__checkout .pa-woo-mc__btn-txt' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'checkout' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Premium_Background::get_type(),
			array(
				'name'      => 'pa_btn_bg_checkout',
				'types'     => array( 'classic', 'gradient' ),
				'selector'  => '{{WRAPPER}} .pa-woo-mc__checkout',
				'condition' => array(
					'checkout' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'footer_btn_shadow',
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .pa-woo-mc__mc-btn',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'pa_border_color_footer_cta',
				'selector' => '{{WRAPPER}} .pa-woo-mc__mc-btn',
			)
		);

		$this->add_responsive_control(
			'footer_btn_rad',
			array(
				'label'      => __( 'Border Radius', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pa-woo-mc__mc-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'footer_btn_tab_hov',
			array(
				'label'      => __( 'Hover', 'premium-addons-for-elementor' ),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'  => 'checkout',
							'value' => 'yes',
						),
						array(
							'name'  => 'view_cart',
							'value' => 'yes',
						),
					),
				),
			)
		);

		$this->add_control(
			'vcart_heading_hov',
			array(
				'label'     => __( 'View Cart', 'premium-addons-for-elementor' ),
				'separator' => 'before',
				'type'      => Controls_Manager::HEADING,
				'condition' => array(
					'view_cart' => 'yes',
				),
			)
		);

		$this->add_control(
			'pa_btn_color_vcart_hov',
			array(
				'label'     => __( 'Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__view-cart:hover .pa-woo-mc__btn-txt' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'view_cart' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Premium_Background::get_type(),
			array(
				'name'      => 'pa_btn_bg_vcart_hov',
				'types'     => array( 'classic', 'gradient' ),
				'selector'  => '{{WRAPPER}} .pa-woo-mc__view-cart:hover',
				'condition' => array(
					'view_cart' => 'yes',
				),
			)
		);

		$this->add_control(
			'checkout_heading_hov',
			array(
				'label'     => __( 'Checkout', 'premium-addons-for-elementor' ),
				'separator' => 'before',
				'type'      => Controls_Manager::HEADING,
				'condition' => array(
					'checkout' => 'yes',
				),
			)
		);

		$this->add_control(
			'pa_btn_color_checkout_hov',
			array(
				'label'     => __( 'Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__checkout:hover .pa-woo-mc__btn-txt' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'checkout' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Premium_Background::get_type(),
			array(
				'name'      => 'pa_btn_bg_checkout_hov',
				'types'     => array( 'classic', 'gradient' ),
				'selector'  => '{{WRAPPER}} .pa-woo-mc__checkout:hover',
				'condition' => array(
					'checkout' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'footer_btn_shadow_hov',
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .pa-woo-mc__mc-btn:hover',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'pa_border_color_footer_cta_hov',
				'selector' => '{{WRAPPER}} .pa-woo-mc__mc-btn:hover',
			)
		);

		$this->add_responsive_control(
			'footer_btn_rad_hov',
			array(
				'label'      => __( 'Border Radius', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pa-woo-mc__mc-btn:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'footer_btn_padding',
			array(
				'label'      => __( 'Padding', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'separator'  => 'before',
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pa-woo-mc__mc-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'  => 'checkout',
							'value' => 'yes',
						),
						array(
							'name'  => 'view_cart',
							'value' => 'yes',
						),
					),
				),
			)
		);

		$this->add_responsive_control(
			'footer_btn_cont',
			array(
				'label'      => __( 'container Margin', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pa-woo-mc__cart-buttons' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'  => 'checkout',
							'value' => 'yes',
						),
						array(
							'name'  => 'view_cart',
							'value' => 'yes',
						),
					),
				),
			)
		);

		$this->add_control(
			'footer_cont_heading',
			array(
				'label'     => __( 'Container', 'premium-addons-for-elementor' ),
				'separator' => 'before',
				'type'      => Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			Premium_Background::get_type(),
			array(
				'name'     => 'pa_btn_bg_footer',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .pa-woo-mc__cart-footer',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'pa_border_color_footer',
				'selector' => '{{WRAPPER}} .pa-woo-mc__cart-footer',
			)
		);

		$this->add_responsive_control(
			'footer_padding',
			array(
				'label'      => __( 'Padding', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pa-woo-mc__cart-footer' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	private function add_empty_cart_style() {

		$this->start_controls_section(
			'empty_cart_style_sec',
			array(
				'label'      => __( 'Empty Cart Message', 'premium-addons-for-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'conditions' => $this->render_mc_conds,
			)
		);

		$this->add_responsive_control(
			'empty_icon_size',
			array(
				'label'      => __( 'Icon Size', 'premium-addons-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .pa-woo-mc__empty-msg-img' => 'width: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'empty_msg_heading',
			array(
				'label'     => __( 'Message', 'premium-addons-for-elementor' ),
				'separator' => 'before',
				'type'      => Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'empty_msg_typo',
				'selector' => '{{WRAPPER}} .pa-woo-mc__empty-msg',
			)
		);

		$this->add_control(
			'pa_txt_color_msg',
			array(
				'label'     => __( 'Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__empty-msg' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'empty_msg_marg',
			array(
				'label'      => __( 'Margin', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .pa-woo-mc__empty-msg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'empty_btn_heading',
			array(
				'label'     => __( 'Button', 'premium-addons-for-elementor' ),
				'separator' => 'before',
				'type'      => Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'empty_btn_typo',
				'selector' => '{{WRAPPER}} .pa-woo-mc__empty-msg-btn',
			)
		);

		$this->add_control(
			'pa_txt_color_msg_btn',
			array(
				'label'     => __( 'Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pa-woo-mc__empty-msg-btn' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Premium_Background::get_type(),
			array(
				'name'     => 'pa_btn_bg_emptybtn',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .pa-woo-mc__empty-msg-btn',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'pa_border_color_empty-btn',
				'selector' => '{{WRAPPER}} .pa-woo-mc__empty-msg-btn',
			)
		);

		$this->add_responsive_control(
			'empty_btn_border_rad',
			array(
				'label'      => __( 'Border Radius', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pa-woo-mc__empty-msg-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'empty_btn_padding',
			array(
				'label'      => __( 'Padding', 'premium-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pa-woo-mc__empty-msg-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	private function add_loader_style() {

		$this->start_controls_section(
			'loader_style_sec',
			array(
				'label'      => __( 'Loader', 'premium-addons-for-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'  => 'behaviour',
							'value' => 'toggle',
						),
						array(
							'terms' => array(
								array(
									'name'  => 'behaviour',
									'value' => 'url',
								),
								array(
									'name'  => 'woo_cta_connect',
									'value' => 'yes',
								),
							),
						),
					),
				),
			)
		);

		$this->add_control(
			'loader_overlay_color',
			array(
				'label'     => __( 'Overlay Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .premium-loading-feed' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'spinner_color',
			array(
				'label'     => __( 'Spinner Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .premium-loader' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'spinner_fill_color',
			array(
				'label'     => __( 'Fill Color', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .premium-loader' => 'border-top-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render Mini Cart widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {

		if ( ! wp_script_is( 'wc-cart-fragments' ) ) {
			wp_enqueue_script( 'wc-cart-fragments' );
		}

		$settings = $this->get_settings_for_display();

		$papro_activated = Helper_Functions::check_papro_version();

		if ( ! $papro_activated || version_compare( PREMIUM_PRO_ADDONS_VERSION, '2.9.34', '<' ) ) {

			if ( 'url' === $settings['behaviour'] || 'menu' === $settings['cart_type'] || 'layout-2' === $settings['content_layout'] || 'yes' === $settings['odometer_effect'] || 'yes' === $settings['coupon'] || 'yes' === $settings['mc_progressbar'] ) {

				?>
				<div class="premium-error-notice">
					<?php
						$message = __( 'This option is available in <b>Premium Addons Pro</b>.', 'premium-addons-for-elementor' );
						echo wp_kses_post( $message );
					?>
				</div>
				<?php
				return false;

			}
		}

		$trigger_pos = $settings['placement'];

		$subtotal    = 'float' === $trigger_pos && 'yes' === $settings['subtotal'];
		$has_subtotal = 'default' === $trigger_pos && ! in_array( $settings['presets'], array( 'preset-1', 'preset-2' ), true );
		$has_badge    = 'default' === $trigger_pos && in_array( $settings['presets'], array( 'preset-5', 'preset-7' ), true );
		$badge        = 'yes' === $settings['badge_switcher'];
		$behaviour    = $settings['behaviour'];

		$is_connected     = 'url' === $behaviour && 'yes' === $settings['woo_cta_connect'];

		// if yes, we should ignore the is_cart or is_checkout, and always render the mini cart. if not, we follow the previous logic.
		$render_on_cart_checkout = 'yes' === $settings['render_on_cart_checkout'] ? true : ( ! is_cart() && ! is_checkout());

		// $render_mini_cart = ( 'toggle' === $behaviour || $is_connected ) && ! is_cart() && ! is_checkout();
		$render_mini_cart = ( 'toggle' === $behaviour || $is_connected ) && $render_on_cart_checkout;

		$counting_effect  = 'yes' === $settings['odometer_effect'];

		$this->add_render_attribute( 'cart_outer_wrapper', 'class', 'pa-woo-mc__outer-container' );

		if ( $render_mini_cart ) {

			delete_option( 'pa_mc_layout' );
			update_option( 'pa_mc_layout', $settings['content_layout'], true );

			$cart_type  = $is_connected || 'float' === $trigger_pos ? 'slide' : $settings['cart_type'];
			$cart_count = WC()->cart ? WC()->cart->get_cart_contents_count() : '0';
			$header_cls = ! empty( $settings['cart_title'] ) ? 'premium-mc-title-yes' : '';

			// we should also add the animation.
			$this->add_render_attribute(
				'cart_menu_content',
				'class',
				array(
					'pa-woo-mc__content-wrapper',
					'pa-woo-mc__content-wrapper-' . $this->get_id(),
					'premium-addons__v-hidden',
					'pa-flex-col',
					'pa-woo-mc__' . $cart_type,
					$header_cls,
				)
			);

			if ( ! $cart_count ) {
				$this->add_render_attribute( 'cart_menu_content', 'class', 'pa-hide-content' );
			}

			$cart_settings = array(
				'type'         => $cart_type,
				'behavior'     => $behaviour,
				'trigger'      => 'slide' === $cart_type ? 'click' : $settings['trigger'],
				'clickOutside' => 'yes' === $settings['close_on_outside'],
				'removeTxt'    => 'yes' === $settings['remove_icon'] && 'text' === $settings['remove_type'] ? $settings['remove_txt'] : false,
				'coupon'       => 'yes' === $settings['coupon']
			);

			if ( 'yes' === get_option( 'woocommerce_calc_taxes' ) && in_array( 'yes', array( $settings['show_tax_label'], $settings['show_footer_tax_label'] ), true ) ) {
				$cart_settings['taxLabel'] = WC()->countries->inc_tax_or_vat();
			}

			if ( 'yes' === $settings['cross_sells'] ) {
				$cart_settings['crossSells'] = 'yes' === $settings['cross_sells'];
				// $cart_settings['crossSellTxt'] =  !empty( $settings['cross_sells_txt'] ) ? $settings['cross_sells_txt'] : '';
				$cart_settings['crossSellTxt']   = $settings['cross_sells_txt'];
				$cart_settings['slidesToShow']   = $settings['slides_to_show'];
				$cart_settings['slidesToScroll'] = $settings['slides_to_scroll'];
				$cart_settings['autoplay']       = 'yes' === $settings['auto_play'];
				$cart_settings['autoplaySpeed']  = $settings['autoplay_speed'];
				$cart_settings['speed']          = $settings['speed'];
			}

			if ( ! empty( $settings['cart_css_selector'] ) ) {
				$cart_settings['cssSelector'] = $settings['cart_css_selector'];
				$clean_selector               = ltrim( $settings['cart_css_selector'], '.#' );
				$this->add_render_attribute( 'cart_outer_wrapper', 'data-cart-selector', esc_attr( $clean_selector ) );
			}

			$this->add_render_attribute( 'cart_outer_wrapper', 'data-settings', json_encode( $cart_settings ) );
		}

		?>
			<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'cart_outer_wrapper' ) ); ?>>
				<div class="pa-woo-mc__inner-container">
					<div class="pa-woo-mc__icon-wrapper">
						<?php
							$this->render_trigger_icon( $settings );

						if ( ! $has_badge && $badge ) {
							$this->render_count_badge( $settings );
						}
						?>
					</div>
					<?php if ( $subtotal || $has_subtotal ) : ?>
						<div class="pa-woo-mc__text-wrapper">
							<?php

							$this->add_render_attribute( 'trigger_subtotal', 'class', 'pa-woo-mc__subtotal' );

							if ( $has_badge ) {
								$this->render_count_badge( $settings );

								if ( 'preset-5' === $settings['presets'] ) {
									?>
										<span class="pa-woo-mc__icon-sep">/</span>
									<?php
								}
							}

							$subtotal_display = $this->get_cart_subtotal_amount( $settings );

							if ( $counting_effect ) {
								$this->add_render_attribute( 'trigger_subtotal', 'class', 'pa-counting' );

								?>
									<span class="pa-woo-mc__subtotal-placeholder" style="display:none;"><?php echo esc_html( $subtotal_display['raw_subtotal_amount'] ); ?></span>
																													<?php
							}
							?>
								<span <?php echo wp_kses_post( $this->get_render_attribute_string( 'trigger_subtotal' ) ); ?>>
									<?php
									if ( $counting_effect ) {
										?>
											<!-- <span class='pa-rtl-counting'> -->
												<span class="pa-woo-mc__subtotal-val" ><?php echo esc_html( $subtotal_display['raw_subtotal_amount'] ); ?></span>
												<span class="pa-woo-mc__subtotal-currency"> <?php echo get_woocommerce_currency_symbol(); ?></span>
											<!-- </span> -->
											<?php
									} else {
										echo wp_kses_data( WC()->cart ? $subtotal_display['subtotal_amount'] : '' );
									}

									if ( $subtotal_display['includes_tax'] && 'yes' === $settings['show_tax_label'] ) {
										?>
										<small class="pa-woo-mc__tax-label"><?php echo wp_kses_data( WC()->countries->inc_tax_or_vat() ); ?></small>
										<?php
									}
									?>
								</span>

						</div>
					<?php endif; ?>

					<?php
					if ( 'url' === $behaviour ) :
						$this->add_link_attributes( 'cart_link', $settings['cart_link'] );
						$this->add_render_attribute( 'cart_link', 'class', 'pa-woo-mc__link' );
						?>
						<a <?php $this->print_render_attribute_string( 'cart_link' ); ?>></a>
					<?php endif; ?>

					<?php
					if ( $render_mini_cart && 'menu' === $cart_type ) {
						$this->render_mini_cart_content( $settings, $cart_type );
					}
					?>
				</div>

			</div>
		<?php
		if ( $render_mini_cart && 'slide' === $cart_type ) {
			$this->render_mini_cart_content( $settings, $cart_type );

			if ( 'yes' === $settings['slide_overlay'] ) {
				$this->add_render_attribute(
					'overlay',
					array(
						'class' => array(
							'pa-woo-mc__overlay-' . $this->get_id(),
							'pa-woo-mc__overlay',
							'premium-addons__v-hidden',
						),
					)
				);
				?><div <?php echo wp_kses_post( $this->get_render_attribute_string( 'overlay' ) ); ?>></div><?php
			}
		}
	}

	private function render_count_badge( $settings ) {

		$count           = WC()->cart ? WC()->cart->get_cart_contents_count() : '0';
		$empty_count_cls = ! $count ? 'pa-hide-badge' : '';
		$counting_effect = 'yes' === $settings['odometer_effect'];
		$has_txt         = 'preset-7' === $settings['presets'];

		$this->add_render_attribute( 'count_badge', 'class', array( 'pa-woo-mc__badge', $empty_count_cls ) );

		if ( $has_txt ) {
			$this->add_render_attribute( 'count_badge', 'class', 'pa-has-txt' );
		}

		if ( $counting_effect ) {
			$this->add_render_attribute(
				'count_badge',
				array(
					'class'                 => 'pa-counting',
					'data-pa-count-next'    => esc_attr( $count ),
					'data-pa-count-current' => esc_attr( $count ),
				)
			);

			?>
			<span class="pa-woo-mc__count-placeholder" style="display:none"><?php echo esc_html( $count ); ?></span>
																						<?php
		}

		?>
			<span <?php echo wp_kses_post( $this->get_render_attribute_string( 'count_badge' ) ); ?>>
				<?php
				if ( $counting_effect ) {
					?>
					<span class="odometer-wrapper"><?php echo esc_html( $count ); ?></span>
																<?php
				} else {
					echo esc_html( $count );
				}
				if ( $has_txt ) {
					?>
					<span class="pa-woo-mc__badge-txt"><?php echo $count === 1 ? ' item' : ' items'; ?></span>
																	<?php
				}
				?>
			</span>
		<?php
	}

	private function render_trigger_icon( $settings ) {

		$icon_type = $settings['icon_type'];

		switch ( $icon_type ) {
			case 'icon':
				if ( 'none' === $settings['default_icons'] ) {
					$trigger_icon = '';
				} elseif ( 'custom' === $settings['default_icons'] ) {
					Icons_Manager::render_icon(
						$settings['icon'],
						array(
							'class'       => array( 'pa-woo-mc__icon' ),
							'aria-hidden' => 'true',
						)
					);
				} else {
					echo $this->getTriggerIcon( $settings['default_icons'] );
				}

				break;
			case 'image':
				if ( ! empty( $settings['image']['url'] ) ) {
					$image_html = Group_Control_Image_Size::get_attachment_image_html( $settings, 'thumbnail', 'image' );
				}

				echo wp_kses_post( $image_html );
				break;
			case 'animation':
				$this->add_render_attribute(
					'cart_lottie',
					array(
						'class'               => array(
							'pa-woo-mc__lotti-animation',
							'premium-lottie-animation',
						),
						'data-lottie-url'     => 'url' === $settings['lottie_source'] ? $settings['lottie_url'] : $settings['lottie_file']['url'],
						'data-lottie-loop'    => $settings['lottie_loop'],
						'data-lottie-reverse' => $settings['lottie_reverse'],
					)
				);
				?>
						<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'cart_lottie' ) ); ?>></div>
					<?php

				break;
			default:
				$this->print_unescaped_setting( 'custom_svg' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				break;
		}
	}

	private function render_mini_cart_content( $settings, $cart_type ) {

		$render_header = 'slide' === $cart_type || ( 'menu' === $cart_type && ! empty( $settings['cart_title'] ) );
		$progress_bar  = 'yes' === $settings['mc_progressbar'];

		?>
			<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'cart_menu_content' ) ); ?>>
				<?php
				if ( $render_header ) {
					$this->render_cart_header( $settings, $cart_type );
				}
				?>

				<?php
				if ( $progress_bar ) {
					$free_shipping_threshold = $this->check_free_shipping_method();
					$raw_subtotal            = WC()->cart ? WC()->cart->get_cart_contents_total() : 0;
					if ( $free_shipping_threshold ) :
						?>
							<div class="pa-woo-mc__progressbar-wrapper" data-pa-progress-txt="<?php echo wp_kses_data( $this->format_free_shipping_txt( $settings, $free_shipping_threshold, $raw_subtotal ) ); ?>" data-pa-progress-threshold="<?php echo esc_attr( $free_shipping_threshold ); ?>" data-pa-progress-complete="<?php echo esc_attr( $settings['complete_txt'] ); ?>">
								<span class="pa-woo-mc__subtotal-placeholder" style="display:none"><?php echo esc_html( $raw_subtotal ); ?></span>
								<span class="pa-woo-mc__progress-heading"></span>
								<progress class="pa-woo-mc__progressbar" value="<?php echo esc_attr( $this->get_purchase_percentage( $free_shipping_threshold ) ); ?>" max="100"></progress>
							</div>
						<?php endif; ?>
				<?php } ?>
				<div class="pa-woo-mc__widget-shopping-outer-wrapper">
					<div class="widget_shopping_cart_content">
						<?php woocommerce_mini_cart(); ?>
					</div>
				</div>
				<?php
					$render_empty_btn = 'yes' === $settings['remove_all_btn'];
					$coupon_section   = 'yes' === $settings['coupon'];

				if ( $render_empty_btn ) {
					$this->render_remove_all_btn( $settings['remove_all_txt'] );
				}

				if ( $coupon_section ) {
					$this->render_coupon_section( $settings );
				}

						$this->render_cart_footer( $settings );
				?>
			</div>
		<?php
	}

	private function render_coupon_section( $settings ) {
		// check if the user already applied a coupon.
		$applied_coupons = WC()->cart->get_applied_coupons();

		$last_applied_coupon = ! empty( $applied_coupons ) ? end( $applied_coupons ) : '';
		$def_style           = ! $last_applied_coupon ? 'display:none' : '';
		$remove_style        = ! $last_applied_coupon ? 'display:none' : 'display:inline-block';
		?>
			<div class="pa-woo-mc__coupon-sec-wrapper">
				<a role="button" href="#" class="pa-woo-mc__coupon-toggler"><?php echo esc_attr__( 'Apply Coupon', 'premium-addons-for-elementor' ); ?></a>
				<div class="pa-woo-mc__coupon-wrapper" style="<?php echo esc_attr( $def_style ); ?>">
					<input type="text" class="pa-woo-mc__coupon-field" name="coupon_code" placeholder="<?php echo esc_attr__( 'Coupon code', 'premium-addons-for-elementor' ); ?>" value="<?php echo esc_attr( $last_applied_coupon ); ?>">
					<a role="button" href="#" class="pa-woo-mc__coupon-submit" aria-label="<?php echo esc_attr__( 'Apply Coupon', 'premium-addons-for-elementor' ); ?>">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" ><path d="M8.90002 7.55999C9.21002 3.95999 11.06 2.48999 15.11 2.48999H15.24C19.71 2.48999 21.5 4.27999 21.5 8.74999V15.27C21.5 19.74 19.71 21.53 15.24 21.53H15.11C11.09 21.53 9.24002 20.08 8.91002 16.54" stroke="#000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M2 12H14.88" stroke="#000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M12.65 8.6499L16 11.9999L12.65 15.3499" stroke="#000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
					</a>
				</div>
				<div class="pa-woo-mc__coupon-notice-wrapper">
					<span class="pa-woo-mc__coupon-notice"></span>
					<a href="#" role="button" aria-label="<?php echo esc_attr__( 'Remove coupon', 'premium-addons-for-elementor' ); ?>" class="pa-woo-mc__remove-coupon" style="<?php echo esc_attr( $remove_style ); ?>"><?php echo esc_html__( 'Remove', 'premium-addons-for-elementor' ); ?></a>
				</div>
			</div>
		<?php
	}


	private function render_remove_all_btn( $text ) {
		?>
			<div class="pa-woo-mc__empty-mc">
				<a type="button" role="button" class="pa-woo-mc__remove-all-btn"><?php echo esc_html__( $text, 'premium-addons-for-elementor' ); ?></a>
				<div class="pa-woo-mc__empty-mc-confirm" style="display: none;">
					<span class="pa-woo-mc__confirm-msg"><?php echo esc_html__( 'Are you sure? ', 'premium-addons-for-elementor' ); ?></span>
					<a type="button" role="button" class="pa-woo-mc__confirm-btn pa-empty-mc"><?php echo esc_html__( 'Yes', 'premium-addons-for-elementor' ); ?></a> / <a type="button" role="button" class="pa-woo-mc__confirm-btn"><?php echo esc_html__( 'No', 'premium-addons-for-elementor' ); ?></a>
				</div>
			</div>
		<?php
	}

	/**
	 * Get the WooCommerce cart subtotal amount after applying discounts
	 * and optionally including tax, based on the widget settings.
	 *
	 * @since 4.7.0
	 *
	 * @param array $settings Widget settings.
	 *
	 * @return array
	 */
	private function get_cart_subtotal_amount( $settings ) {

		$discount_total = WC()->cart->get_discount_total();
		$raw_subtotal   = floatval( WC()->cart->get_subtotal() );

		$includes_tax = false;

		if ( 'yes' === get_option( 'woocommerce_calc_taxes' ) ) {
			$raw_subtotal += WC()->cart->get_taxes_total();
			$includes_tax  = true;
		}

		$raw_subtotal_amount = number_format( $raw_subtotal - $discount_total, 2, '.', '' );
		$subtotal_amount     = wc_price( $raw_subtotal - $discount_total, array( 'in_span' => false ) );

		return array(
			'raw_subtotal_amount' => $raw_subtotal_amount,
			'subtotal_amount'     => $subtotal_amount,
			'includes_tax'        => $includes_tax,
		);
	}

	/**
	 * Render the cart header section.
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @param array
	 */
	private function render_cart_header( $settings, $cart_type ) {
		$title      = $settings['cart_title'];
		$layout     = $settings['content_layout'];
		$cart_count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;

		$this->add_render_attribute( 'cart_header', 'class', 'pa-woo-mc__cart-header' );

		if ( 'layout-3' === $layout ) {
			$this->add_render_attribute( 'cart_header', 'data-pa-count-txt', $settings['header_item_count'] );
		}

		?>
		<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'cart_header' ) ); ?>>
			<?php

			if ( 'layout-3' === $layout ) {
				?>
					<span class="pa-woo-mc__cart-count"> <?php echo esc_html( $cart_count ); ?></span>
				<?php
			}

			if ( ! empty( $title ) ) {
				?>
						<div class="pa-woo-mc__cart-title"> <?php echo esc_html__( $title ); ?> </div>
					<?php
			}

			if ( 'slide' === $cart_type ) {
				?>
						<span class="pa-woo-mc__close-button">
						<?php
						if ( in_array( $layout, array( 'layout-3', 'layout-4', true ) ) ) {
							?>
									<svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" width="16" height="15.99" viewBox="0 0 16 15.99"><path d="M15.85,15.14l-7.15-7.15L15.85.85c.19-.19.19-.5,0-.69-.19-.2-.51-.2-.71-.01l-7.15,7.15L.85.14C.66-.05.35-.05.16.14c-.2.19-.2.51-.01.71l7.15,7.15L.15,15.14C.05,15.23,0,15.36,0,15.49c0,.28.22.5.5.5.13,0,.26-.05.35-.15l7.15-7.15,7.15,7.15c.09.09.22.15.35.15.13,0,.26-.05.35-.15.2-.2.2-.51,0-.71Z"/></svg>
									<?php
						} else {
							Icons_Manager::render_icon(
								$settings['close_icon'],
								array(
									'class'       => array( 'pa-woo-mc__close-icon' ),
									'aria-hidden' => 'true',
								)
							);
						}
						?>
						</span>
					<?php
			}
			?>
		</div>
		<?php
	}

	/**
	 * Render the cart footer section.
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @param array
	 */
	private function render_cart_footer( $settings ) {

		$subtotal  = 'yes' === $settings['footer_subtotal'];
		$checkout  = 'yes' === $settings['checkout'];
		$view_cart = 'yes' === $settings['view_cart'];
		$layout    = $settings['content_layout'];

		if ( $subtotal ) {
			$subtotal_display = $this->get_cart_subtotal_amount( $settings );
		}

		if ( ! in_array( true, array( $subtotal, $checkout, $view_cart ), true ) ) {
			return;
		}

		if ( $subtotal ) {
			$cart_count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;

			$this->add_render_attribute( 'cart_footer', 'class', 'pa-woo-mc__cart-footer' );

			if ( 'layout-3' !== $layout ) {
				$this->add_render_attribute( 'cart_footer', 'data-pa-count-txt', __( $settings['subtotal_txt'], 'premium-addons-for-elementor' ) );

				$has_item_count = str_contains( $settings['subtotal_txt'], '{{count}}' );

				if ( $has_item_count ) {
					$subtotal_heading = ! empty( $settings['subtotal_txt'] ) ? '<span class="pa-woo-mc__cart-count">' . $cart_count . '</span>' : false;
				} else {
					$subtotal_heading = ! empty( $settings['subtotal_txt'] ) ? __( $settings['subtotal_txt'], 'premium-addons-for-elementor' ) : false;
				}
			}
		}
		?>
		<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'cart_footer' ) ); ?>>
			<?php if ( $subtotal ) : ?>
			<div class="pa-woo-mc__cart-subtotal">
				<?php if ( 'layout-3' === $layout || $subtotal_heading ) : ?>
					<span class="pa-woo-mc__subtotal-heading"> <?php echo 'layout-3' === $layout ? esc_html__( 'Subtotal', 'premium-addons-for-elementor' ) : wp_kses_post( $subtotal_heading ); ?></span>
				<?php endif; ?>

				<span class="pa-woo-mc__subtotal">
					<?php echo wp_kses_data( WC()->cart ? $subtotal_display['subtotal_amount'] : '' ); ?>
					<?php
					if ( $subtotal_display['includes_tax'] && 'yes' === $settings['show_footer_tax_label'] ) {
						?>
							<small class="pa-woo-mc__tax-label"><?php echo wp_kses_data( WC()->countries->inc_tax_or_vat() ); ?></small>
							<?php
					}
					?>
				</span>
				</div>
			<?php endif; ?>
			<?php if ( $view_cart || $checkout ) : ?>
			<div class="pa-woo-mc__cart-buttons">
				<?php if ( $view_cart ) : ?>
				<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="pa-woo-mc__mc-btn pa-woo-mc__view-cart">
					<span class="pa-woo-mc__btn-txt"><?php echo esc_html__( 'View cart', 'woocommerce' ); // phpcs:ignore WordPress.WP.I18n ?></span>
				</a>
				<?php endif; ?>
				<?php if ( $checkout ) : ?>
				<a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="pa-woo-mc__mc-btn pa-woo-mc__checkout">
					<span class="pa-woo-mc__btn-txt"><?php echo esc_html__( 'Checkout', 'woocommerce' ); // phpcs:ignore WordPress.WP.I18n ?></span>
				</a>
				<?php endif; ?>
			</div>
			<?php endif; ?>
		</div>

		<?php
	}

	/**
	 * Check for free shipping method.
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @return string|false
	 */
	private function check_free_shipping_method() {

		// get the first shipping zone as a default.
		$zones     = \WC_Shipping_Zones::get_zones();
		$threshold = '';

		$default_zone = is_array( $zones ) && count( $zones ) ? reset( $zones ) : false;

		if ( ! $default_zone ) {
			return false;
		}

		$shipping_methods = $default_zone['shipping_methods'];

		// loop through all the shipping methods in this zone.
		foreach ( $shipping_methods as $method ) {

			if ( $method->id === 'free_shipping' ) {
				$min_amount = $method->get_option( 'min_amount' );

				if ( $min_amount ) {
					$threshold = $min_amount;
					break;
				}
			}
		}

		return $threshold;
	}

	/**
	 * Format the free shipping progress bar text.
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @param array $settings.
	 * @param float $threshold The minimum amount required for free shipping.
	 * @param float $subtotal  The current cart subtotal.
	 *
	 * @return string The formatted progress bar text.
	 */
	private function format_free_shipping_txt( $settings, $threshold, $subtotal ) {

		return str_replace( '{{thershold}}', wc_price( $threshold, array( 'in_span' => false ) ), $settings['progressbar_txt'] );
	}

	/**
	 * Get purchase percentage.
	 *
	 * @param string $threshold  free shipping threshold.
	 *
	 * @return number
	 */
	private function get_purchase_percentage( $threshold ) {

		$cart_total = WC()->cart ? WC()->cart->get_cart_contents_total() : 0;

		return round( ( $cart_total / floatval( $threshold ) ) * 100, 2 );
	}

	/**
	 * Retrieve the SVG icon markup based on the icon key.
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @param string $icon.
	 *
	 * @return string|null
	 */
	private function getTriggerIcon( $icon ) {

		$icons = array(
			'default-sharp'         => '<svg class="pa-woo-mc__icon" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" width="22.5" height="22.5" viewBox="0 0 22.5 22.5"><defs><style>.pa-default-cart-sharp{fill:#1a1a1a;}</style></defs><g id="Default_Cart_Sharp"><path class="pa-default-cart-sharp" d="M22.19,3.25H4.61l-.04-.31c-.08-.65-.39-1.25-.88-1.68-.49-.43-1.12-.67-1.77-.67H.84v1.78h1.09c.22,0,.43.08.59.22.16.14.27.34.29.56l1.41,11.98c.08.65.39,1.25.88,1.68.49.43,1.12.67,1.77.67h11.76v-1.78H6.87c-.22,0-.43-.08-.59-.23-.16-.15-.27-.34-.29-.56l-.12-.99h14.4l1.93-10.68ZM18.78,12.14H5.66l-.84-7.12h15.24l-1.28,7.12Z"/><path class="pa-default-cart-sharp" d="M7.07,21.93c.98,0,1.78-.8,1.78-1.78s-.8-1.78-1.78-1.78-1.78.8-1.78,1.78.8,1.78,1.78,1.78Z"/><path class="pa-default-cart-sharp" d="M15.96,21.93c.98,0,1.78-.8,1.78-1.78s-.8-1.78-1.78-1.78-1.78.8-1.78,1.78.8,1.78,1.78,1.78Z"/></g></svg>',
			'default-round'         => '<svg class="pa-woo-mc__icon" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" width="22.5" height="22.5" viewBox="0 0 22.5 22.5"><defs><style>.pa-default-cart-round{fill:#1a1a1a;}</style></defs><g id="Cart"><path class="pa-default-cart-round" d="M21.04,4.2c-.25-.3-.56-.54-.92-.71-.35-.17-.74-.25-1.13-.25H4.61l-.04-.31c-.08-.65-.39-1.25-.88-1.68-.49-.43-1.12-.67-1.77-.67h-.2c-.24,0-.46.09-.63.26-.17.17-.26.39-.26.63s.09.46.26.63c.17.17.39.26.63.26h.2c.22,0,.43.08.59.22.16.14.27.34.29.56l1.22,10.41c.13,1.08.65,2.08,1.46,2.8s1.87,1.12,2.96,1.12h9.29c.24,0,.46-.09.63-.26s.26-.39.26-.63-.09-.46-.26-.63-.39-.26-.63-.26h-9.29c-.55,0-1.09-.17-1.54-.49-.45-.32-.79-.77-.97-1.29h10.6c1.04,0,2.05-.37,2.85-1.03s1.34-1.6,1.52-2.62l.7-3.87c.07-.38.05-.78-.05-1.16-.1-.38-.28-.73-.53-1.03ZM19.87,6.07l-.7,3.87c-.11.62-.44,1.17-.92,1.58-.48.4-1.09.62-1.71.62H5.66l-.84-7.12h14.17c.13,0,.26.03.38.08.12.05.22.14.31.24.08.1.15.22.18.34.03.13.04.26.02.39Z"/><path class="pa-default-cart-round" d="M7.06,21.92c.98,0,1.78-.8,1.78-1.78s-.8-1.78-1.78-1.78-1.78.8-1.78,1.78.8,1.78,1.78,1.78Z"/><path class="pa-default-cart-round" d="M15.96,21.92c.98,0,1.78-.8,1.78-1.78s-.8-1.78-1.78-1.78-1.78.8-1.78,1.78.8,1.78,1.78,1.78Z"/></g></svg>',
			'basket'                => '<svg class="pa-woo-mc__icon" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" width="21.5" height="21.5" viewBox="0 0 21.5 21.5"><defs><style>.pa-basket{fill:#333;}</style></defs><path class="pa-basket" d="M17.99,4.33h-.4l-3.38-3.38c-.27-.27-.71-.27-.99,0-.27.27-.27.71,0,.99l2.39,2.39H5.89l2.39-2.39c.27-.27.27-.71,0-.99-.27-.27-.71-.27-.99,0l-3.37,3.38h-.4c-.9,0-2.77,0-2.77,2.56,0,.97.2,1.61.62,2.03.24.25.53.38.84.45.29.07.6.08.9.08h15.28c.31,0,.6-.02.88-.08.84-.2,1.48-.8,1.48-2.48,0-2.56-1.87-2.56-2.76-2.56Z"/><path class="pa-basket" d="M17.8,10.75H3.62c-.62,0-1.09.55-.99,1.16l.84,5.14c.28,1.72,1.03,3.7,4.36,3.7h5.61c3.37,0,3.97-1.69,4.33-3.58l1.01-5.23c.12-.62-.35-1.19-.98-1.19ZM9.36,17.2c0,.39-.31.7-.69.7s-.7-.31-.7-.7v-3.3c0-.38.31-.7.7-.7s.69.32.69.7v3.3ZM13.64,17.2c0,.39-.31.7-.7.7s-.7-.31-.7-.7v-3.3c0-.38.32-.7.7-.7s.7.32.7.7v3.3Z"/></svg>',
			'basket-thin'           => '<svg class="pa-woo-mc__icon" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" width="21.5" height="21.51" viewBox="0 0 21.5 21.51"><defs><style>.pa-basket{fill:#1a1a1a;}</style></defs><g id="Basket"><path class="pa-basket" d="M18.53,3.85h-.44L14.47.22c-.29-.29-.77-.29-1.06,0-.29.29-.29.77,0,1.06l2.56,2.57H5.53l2.56-2.57c.29-.29.29-.77,0-1.06-.29-.29-.77-.29-1.06,0l-3.62,3.63h-.44c-.96,0-2.97,0-2.97,2.75,0,1.04.21,1.73.67,2.18.26.26.57.41.9.48l1.35,8.25c.31,1.86,1.12,3.99,4.69,3.99h6.03c3.63,0,4.28-1.82,4.67-3.85l1.61-8.39c.33-.07.64-.21.91-.48.46-.45.67-1.14.67-2.18,0-2.75-2.01-2.75-2.97-2.75ZM16.83,17.37c-.33,1.73-.62,2.63-3.19,2.63h-6.03c-2.32,0-2.92-.96-3.21-2.73l-1.29-7.92h15.26l-1.54,8.02ZM19.78,7.72c-.14.14-.57.13-1.02.13H2.74c-.45,0-.88.01-1.02-.13-.06-.07-.22-.31-.22-1.12,0-1.13.28-1.25,1.47-1.25h15.56c1.19,0,1.47.12,1.47,1.25,0,.81-.16,1.05-.22,1.12Z"/><path class="pa-basket" d="M8.51,17.05c-.41,0-.75-.34-.75-.75v-3.55c0-.41.34-.75.75-.75s.75.34.75.75v3.55c0,.41-.34.75-.75.75Z"/><path class="pa-basket" d="M13.11,17.05c-.41,0-.75-.34-.75-.75v-3.55c0-.41.34-.75.75-.75s.75.34.75.75v3.55c0,.41-.34.75-.75.75Z"/></g></svg>',
			'cart'                  => '<svg class="pa-woo-mc__icon" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" width="21.5" height="21.5" viewBox="0 0 21.5 21.5"><defs><style>.pa-cart-filled{fill:#333;}</style></defs><g id="Cart_Filled"><path class="pa-cart-filled" d="M15.6,21.38c.97,0,1.75-.78,1.75-1.75s-.78-1.75-1.75-1.75-1.75.78-1.75,1.75.78,1.75,1.75,1.75Z"/><path class="pa-cart-filled" d="M7.6,21.38c.97,0,1.75-.78,1.75-1.75s-.78-1.75-1.75-1.75-1.75.78-1.75,1.75.78,1.75,1.75,1.75Z"/><path class="pa-cart-filled" d="M4.19,2.82l-.2,2.45c-.04.47.33.86.8.86h15.31c.42,0,.77-.32.8-.74.13-1.77-1.22-3.21-2.99-3.21H5.62c-.1-.44-.3-.86-.61-1.21-.5-.53-1.2-.84-1.92-.84h-1.74C.94.12.6.47.6.88s.34.75.75.75h1.74c.31,0,.6.13.81.35.21.23.31.53.29.84Z"/><path class="pa-cart-filled" d="M19.86,7.62H4.52c-.42,0-.76.32-.8.73l-.36,4.35c-.14,1.71,1.2,3.17,2.91,3.17h11.12c1.5,0,2.82-1.23,2.93-2.73l.33-4.67c.04-.46-.32-.85-.79-.85Z"/></g></svg>',
			'cart-outline'          => '<svg class="pa-woo-mc__icon" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" width="22.5" height="22.5" viewBox="0 0 22.5 22.5"><defs><style>.pa-cart-outlined{fill:#1a1a1a;}</style></defs><g id="Cart_Outline"><path class="pa-cart-outlined" d="M20.83,3.5c-.67-.73-1.6-1.13-2.62-1.13H5.71c-.11-.38-.31-.73-.58-1.03-.5-.53-1.19-.84-1.91-.84h-1.74c-.42,0-.75.34-.75.75s.33.75.75.75h1.74c.3,0,.59.13.8.36.21.22.31.52.29.83l-.83,9.96c-.09.98.25,1.96.92,2.69.67.73,1.62,1.15,2.62,1.15h10.65c1.82,0,3.41-1.48,3.55-3.31l.54-7.5c.08-1.01-.25-1.96-.93-2.68ZM19.73,13.57c-.08,1.04-1.03,1.92-2.06,1.92H7.02c-.59,0-1.12-.24-1.52-.66-.39-.43-.58-.98-.53-1.56l.79-9.4h12.45c.59,0,1.14.23,1.53.65.38.41.57.96.53,1.56l-.03.42h-11.76c-.42,0-.75.34-.75.75s.33.75.75.75h11.65l-.4,5.57Z"/><path class="pa-cart-outlined" d="M15.73,22c-1.1,0-2-.9-2-2s.9-2,2-2,2,.9,2,2-.9,2-2,2ZM15.73,19.5c-.28,0-.5.22-.5.5s.22.5.5.5.5-.22.5-.5-.22-.5-.5-.5Z"/><path class="pa-cart-outlined" d="M7.73,22c-1.1,0-2-.9-2-2s.9-2,2-2,2,.9,2,2-.9,2-2,2ZM7.73,19.5c-.28,0-.5.22-.5.5s.22.5.5.5.5-.22.5-.5-.22-.5-.5-.5Z"/></g></svg>',
			'shopping-bag-filled'   => '<svg class="pa-woo-mc__icon" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" width="22.5" height="22.5" viewBox="0 0 22.5 22.5"><defs><style>.pa-shopping-bag-filled{fill:#1a1a1a;}</style></defs><g id="Bag_Filled"><path class="pa-shopping-bag-filled" d="M19.28,17.41H7.25c-.41,0-.75-.34-.75-.75s.34-.75.75-.75h11.76c.3,0,.53-.26.5-.56l-.68-5.7c-.21-1.69-.5-3.11-2.83-3.45v-2.07c0-1.92-1.34-3.26-3.25-3.26h-3c-1.91,0-3.25,1.34-3.25,3.26v2.07c-2.33.34-2.62,1.76-2.82,3.45l-.9,7.51c-.29,2.45.47,4.47,3.98,4.47h8.98c3.16,0,4.09-1.63,4.04-3.76-.01-.27-.23-.46-.5-.46ZM8,4.13c0-1.08.67-1.76,1.75-1.76h3c1.08,0,1.75.68,1.75,1.76v2h-6.5v-2Z"/></g></svg>',
			'shopping-bag-outlined' => '<svg class="pa-woo-mc__icon" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" width="22.5" height="22.5" viewBox="0 0 22.5 22.5"><defs><style>.pa-shopping-bag-outlined{fill:#1a1a1a;}</style></defs><g id="Bag_Outline"><path class="pa-shopping-bag-outlined" d="M20.46,16.69l-.05-.41s0-.1-.02-.15l-.83-6.94c-.23-1.97-.7-3.76-3.56-4.12v-1.32c0-1.91-1.34-3.25-3.25-3.25h-3c-1.92,0-3.25,1.34-3.25,3.25v1.32c-2.88.36-3.34,2.15-3.56,4.12l-.9,7.5c-.21,1.71.1,3.03.9,3.94.8.91,2.09,1.37,3.82,1.37h8.99c1.72,0,3-.46,3.8-1.37.81-.9,1.11-2.23.91-3.94ZM8,3.75c0-1.08.67-1.75,1.75-1.75h3c1.08,0,1.75.67,1.75,1.75v1.25h-6.5v-1.25ZM18.43,19.64c-.51.57-1.41.86-2.68.86H6.76c-1.28,0-2.19-.29-2.7-.87-.5-.57-.68-1.5-.53-2.76l.9-7.51c.17-1.45.29-2.49,2.07-2.78v.67c0,.41.33.75.75.75s.75-.34.75-.75v-.75h6.5v.75c0,.41.33.75.75.75s.75-.34.75-.75v-.67c1.77.29,1.9,1.33,2.07,2.79l.74,6.16H7.25c-.42,0-.75.34-.75.75s.33.75.75.75h11.74c.11,1.19-.07,2.06-.56,2.61Z"/></g></svg>',
		);

		return $icons[ $icon ];
	}
}
