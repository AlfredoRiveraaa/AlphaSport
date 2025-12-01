<?php
/**
 * PAPRO Promotion
 */

namespace PremiumAddons\Includes;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

use Elementor\Controls_Manager;

class PAPRO_Promotion {

	/**
	 * Class instance
	 *
	 * @var instance
	 */
	private static $instance = null;

	public function __construct() {

		add_action( 'elementor/element/container/section_layout/after_section_end', array( $this, 'register_all_promotion_controls' ), 10 );

		add_action( 'elementor/element/common/_section_style/after_section_end', array( $this, 'register_magic_scroll_controls' ), 10 );
	}

	public function register_all_promotion_controls( $element ) {

		$promotions = array(
			'parallax'  => array(
				'title'    => __( 'Parallax', 'premium-addons-for-elementor' ),
				'messages' => __( 'Select between 7 neat parallax effects to be applied on Elementor containers.', 'premium-addons-for-elementor' ),
				'demo'     => 'https://premiumaddons.com/elementor-parallax-container-addon/',
			),
			'particles' => array(
				'title'    => __( 'Particles', 'premium-addons-for-elementor' ),
				'messages' => __( 'Create eye-catching particles background with many customization options.', 'premium-addons-for-elementor' ),
				'demo'     => 'https://premiumaddons.com/particles-container-addon-for-elementor-page-builder/',
			),
			'badge'     => array(
				'title'    => __( 'Badge', 'premium-addons-for-elementor' ),
				'messages' => __( 'Add an icon, image, Lottie animation, or SVG blob shape badge to Elementor container.', 'premium-addons-for-elementor' ),
				'demo'     => 'https://premiumaddons.com/elementor-badge-global-addon/',
			),
			'cursor'    => array(
				'title'    => __( 'Custom Mouse Cursor', 'premium-addons-for-elementor' ),
				'messages' => __( 'Add an image, icon or Lottie animation mouse cursor to any container, widget or the whole page.', 'premium-addons-for-elementor' ),
				'demo'     => 'https://premiumaddons.com/elementor-custom-mouse-cursor-global-addon',
			),
			'blob'      => array(
				'title'    => __( 'Animated Blob', 'premium-addons-for-elementor' ),
				'messages' => __( 'Add multiple animated blob layers to your containers with a wide range of smart customization options.', 'premium-addons-for-elementor' ),
				'demo'     => 'https://premiumaddons.com/elementor-animated-blob-generator/',
			),
			'gradient'  => array(
				'title'    => __( 'Animated Gradient', 'premium-addons-for-elementor' ),
				'messages' => __( 'Subtle animated gradients effect that makes your backgrounds attractive.', 'premium-addons-for-elementor' ),
				'demo'     => 'https://premiumaddons.com/elementor-container-animated-gradients-addon/',
			),
			'kenburns'  => array(
				'title'    => __( 'Ken Burns', 'premium-addons-for-elementor' ),
				'messages' => __( 'Add multiple images to your container background and animate them with the popular Ken Burns effect.', 'premium-addons-for-elementor' ),
				'demo'     => 'https://premiumaddons.com/ken-burns-container-addon-for-elementor-page-builder/',
			),
			'lottie'    => array(
				'title'    => __( 'Lottie Background', 'premium-addons-for-elementor' ),
				'messages' => __( 'Add multiple Lottie Animations easily to container with a lot of customization and interactivity options.', 'premium-addons-for-elementor' ),
				'demo'     => 'https://premiumaddons.com/elementor-lottie-animations-container-addon/',
			),

		);

		foreach ( $promotions as $key => $data ) {
			$element->start_controls_section(
				"section_premium_{$key}",
				array(
					'label' => sprintf( '<i class="pa-extension-icon pa-dash-icon"></i> %s', $data['title'] ),
					'tab'   => Controls_Manager::TAB_LAYOUT,
				)
			);

			$element->add_control(
				"{$key}_promoter",
				array(
					'type' => Controls_Manager::RAW_HTML,
					'raw'  => $this->promote_template(
						array(
							'title'    => 'Premium ' . $data['title'],
							'messages' => $data['messages'],
							'demo'     => $data['demo'],
							'addon'    => $key,
						)
					),
				)
			);

			$element->end_controls_section();
		}
	}

	public function promote_template( $texts ) {

		$upgrade_link = Helper_Functions::get_campaign_link(
			'https://premiumaddons.com/pro/#get-pa-pro',
			'panel-' . $texts['addon'],
			'wp-editor',
			'get-pro'
		);

		$html = '<div class="premium-promote-box addon-promotion">
            <div class="premium-promote-box-icon">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" id="Layer_1" aria-hidden="true" height="56.85" viewBox="0 0 56 56" width="">
					<defs>
						<style>
						.pa-logo-1 { fill: url(#linear-gradient); }
						.pa-logo-2 { fill: #fff; opacity: 1; }
						.pa-logo-3 { fill: url(#linear-gradient-3); }
						.pa-logo-4 { fill: url(#linear-gradient-4); }
						.pa-logo-5 { fill: url(#linear-gradient-2); }
						.pa-logo-6 { fill: url(#linear-gradient-5); }
						</style>
						<linearGradient id="linear-gradient" x1="8.73" y1="23.4" x2="10.95" y2="38.67" gradientUnits="userSpaceOnUse">
						<stop offset=".08" stop-color="#00cdfb" />
						<stop offset=".3" stop-color="#00b6ee" />
						<stop offset=".99" stop-color="#0074c9" />
						</linearGradient>
						<linearGradient id="linear-gradient-2" x1="12.17" y1="17.26" x2="26.84" y2="6.65" gradientUnits="userSpaceOnUse">
						<stop offset="0" stop-color="#ff4808" />
						<stop offset=".99" stop-color="#ed8a00" />
						</linearGradient>
						<linearGradient id="linear-gradient-3" x1="26.45" y1="36.93" x2="29.07" y2="54.9" gradientUnits="userSpaceOnUse">
						<stop offset=".08" stop-color="#008ff6" />
						<stop offset=".27" stop-color="#008af4" />
						<stop offset=".46" stop-color="#007eef" />
						<stop offset=".65" stop-color="#006ae6" />
						<stop offset=".85" stop-color="#004eda" />
						<stop offset=".99" stop-color="#0034cf" />
						</linearGradient>
						<linearGradient id="linear-gradient-4" x1="39.98" y1="2.41" x2="41.64" y2="13.76" gradientUnits="userSpaceOnUse">
						<stop offset="0" stop-color="#ffee52" />
						<stop offset=".34" stop-color="#f8c935" />
						<stop offset="1" stop-color="#ed8600" />
						</linearGradient>
						<linearGradient id="linear-gradient-5" x1="46.27" y1="25.1" x2="48.29" y2="38.92" gradientUnits="userSpaceOnUse">
						<stop offset=".02" stop-color="#95d500" />
						<stop offset=".09" stop-color="#90cf00" />
						<stop offset=".99" stop-color="#638b00" />
						</linearGradient>
					</defs>
					<circle class="pa-logo-2" cx="28.42" cy="28.42" r="28.42" />
					<path class="pa-logo-1" d="m17.22,33.19c-1.45,4.37-2.87,8.75-4.31,13.13-.24.75-.49,1.5-.73,2.25-5.88-4.74-9.65-12.01-9.65-20.16,0-2.08.25-4.1.71-6.04,4.56,3.3,9.11,6.6,13.68,9.88.38.27.45.5.3.94Z" />
					<path class="pa-logo-5" d="m11.63,19.63c-2.52,0-5.04,0-7.57,0C7.52,10.03,16.48,3.07,27.13,2.55c-1.78,5.52-3.56,11.03-5.33,16.55-.14.45-.35.57-.81.56-3.12-.02-6.24-.01-9.37-.01v-.02Z" />
					<path class="pa-logo-3" d="m40.42,49.37c.51.37,1.02.74,1.52,1.11-3.94,2.42-8.57,3.81-13.53,3.81s-9.59-1.39-13.53-3.81c4.32-3.15,8.64-6.3,12.96-9.47.42-.31.68-.35,1.12-.02,3.8,2.81,7.63,5.59,11.45,8.37Z" />
					<path class="pa-logo-4" d="m32.51,11.2c-.93-2.88-1.86-5.77-2.79-8.65,10.65.52,19.61,7.49,23.07,17.08-5.66,0-11.31.01-16.97.03-.44,0-.63-.13-.76-.54-.83-2.64-1.69-5.28-2.54-7.92Z" />
					<path class="pa-logo-6" d="m54.31,28.41c0,8.15-3.76,15.41-9.64,20.16-1.66-5.07-3.31-10.13-4.99-15.19-.2-.6-.09-.89.41-1.25,4.1-2.93,8.17-5.89,12.25-8.84.42-.31.84-.61,1.26-.91.46,1.93.71,3.95.71,6.03Z" />
				</svg>
            </div>
            <div class="papro-promote-title">' . $texts['title'] . '</div>
            <div class="papro-promote-message">' . $texts['messages'] . '</div>
			<div class="premium-promote-ctas">' .
			'<a class="premium-promote-demo elementor-button elementor-button-default" href="' . esc_url( $texts['demo'] ) . '" target="_blank">
            ' . __( 'Check Demo', 'premium-addons-for-elementor' ) . '
            </a>
            <a class="premium-promote-upgrade elementor-button elementor-button-default" href="' . esc_url( $upgrade_link ) . '" target="_blank">
            ' . __( 'Get PRO ($105 OFF)', 'premium-addons-for-elementor' ) . '
            </a>
        </div>';

		return $html;
	}

	public function register_magic_scroll_controls( $element ) {

		$element->start_controls_section(
			'section_premium_mscroll',
			array(
				'label' => sprintf( '<i class="pa-extension-icon pa-dash-icon"></i> %s', __( 'Magic Scroll', 'premium-addons-pro' ) ),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			)
		);

		$element->add_control(
			'magic_scroll_promoter',
			array(
				'type' => Controls_Manager::RAW_HTML,
				'raw'  => $this->promote_template(
					array(
						'title'    => __( 'Premium Magic Scroll', 'premium-addons-for-elementor' ),
						'messages' => __( 'Apply outstanding scroll animations to any column/widget with just few clicks and control every single detail in the animation scene.', 'premium-addons-for-elementor' ),
						'demo'     => 'https://premiumaddons.com/elementor-magic-scroll-global-addon/',
						'addon'    => 'magic',
					)
				),
			)
		);

		$element->end_controls_section();
	}

	/**
	 *
	 * Creates and returns an instance of the class
	 *
	 * @since 1.0.0
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
