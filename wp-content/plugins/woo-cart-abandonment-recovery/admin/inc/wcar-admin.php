<?php
/**
 * CartFlows CA Admin.
 *
 * @package Woocommerce-Cart-Abandonment-Recovery
 */

namespace WCAR\Admin\Inc;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Wcar_Admin.
 */
class Wcar_Admin {
	/**
	 * Member Variable.
	 *
	 * @var instance
	 */
	private static $instance;

	/**
	 * Constructor function that initializes required actions and hooks.
	 */
	public function __construct() {
		// Enqueue admin scripts.
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

		add_action( 'wp_ajax_cart_abandonment_fetch_whats_new', [ $this, 'fetch_whats_new' ] );
		add_action( 'wp_ajax_cart_abandonment_install_plugin', 'wp_ajax_install_plugin' );
		add_action( 'wp_ajax_cart_abandonment_activate_plugin', [ $this, 'cart_abandonment_activate_plugin' ] );
	}

	/**
	 *  Initiator.
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Enqueue admin scripts.
	 *
	 * @param string $hook Current page hook.
	 */
	public function enqueue_scripts( $hook ): void {
		// Only load scripts on plugin's admin page.
		if ( false === strpos( $hook, 'woo-cart-abandonment-recovery' ) ) {
			return;
		}

		$handle            = 'wcf-ca-react-app';
		$build_path        = CARTFLOWS_CA_DIR . 'admin/build/';
		$build_url         = CARTFLOWS_CA_URL . 'admin/build/';
		$script_asset_path = $build_path . 'settings.asset.php';
		$script_info       = file_exists( $script_asset_path )
			? include $script_asset_path
			: [
				'dependencies' => [],
				'version'      => CARTFLOWS_CA_VER,
			];

		$script_dep = array_merge( $script_info['dependencies'], [ 'updates' ] );

		// Enqueue app script.
		wp_register_script(
			$handle,
			$build_url . 'settings.js',
			$script_dep,
			$script_info['version'],
			true
		);

		// Register and enqueue styles.
		wp_register_style(
			$handle,
			$build_url . 'settings.css',
			[],
			CARTFLOWS_CA_VER
		);

		// Enqueue the script.
		wp_enqueue_script( $handle );

		// Set script translations.
		wp_set_script_translations( $handle, 'woo-cart-abandonment-recovery' );

		// Add RTL support if needed.
		wp_style_add_data( $handle, 'rtl', 'replace' );

		// Enqueue the style.
		wp_enqueue_style( $handle );

		// Enqueue Google Fonts.
		wp_enqueue_style( 'wcar-font', 'https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600&display=swap', [], CARTFLOWS_CA_VER );

		wp_enqueue_editor();
		// Enqueue media scripts for the media uploader.
		wp_enqueue_media();

		wp_enqueue_script( $handle . '-ottokit-integration', 'https://app.suretriggers.com/js/v2/embed.js', [], CARTFLOWS_CA_VER, true );

		$data_vars = [
			'ajax_url'                => admin_url( 'admin-ajax.php' ),
			
			// Pro Plugin Status - Following CartFlows pattern.
			'wcar_pro_status'         => $this->get_plugin_status( 'woo-cart-abandonment-recovery-pro/woo-cart-abandonment-recovery-pro.php' ),
			'wcar_pro_type'           => $this->get_version_display(),
			'is_pro'                  => _is_wcar_pro(),
			'upgrade_to_pro_url'      => wcf_ca()->helper->get_upgrade_to_pro_url(),
			'license_status'          => _is_wcar_pro_license_activated(),
			'knowledge_base'          => $this->get_knowledge_base(),
			'whats_new_rss_feed'      => $this->get_whats_new_rss_feeds_data(),
			'settings'                => $this->get_cart_abandonment_settings(),
			'supported_wp_roles'      => wcf_ca()->helper->get_wordpress_user_roles(),
			'order_statuses'          => wcf_ca()->helper->get_order_statuses(),
			'settings_fields'         => Meta_Options::get_meta_settings(),
			'save_setting_nonce'      => wp_create_nonce( 'wcar_save_setting' ),
			'plugin_installer_nonce'  => wp_create_nonce( 'updates' ),
			'plugin_activation_nonce' => wp_create_nonce( 'cart_abandonment_activate_plugin_nonce' ),
			'extend_plugins'          => $this->wcar_get_extend_plugins(),
			'ottokit'                 => [
				'status'               => $this->get_plugin_status( 'suretriggers/suretriggers.php' ),
				'is_ottokit_connected' => apply_filters( 'suretriggers_is_user_connected', false ),
				'ottokit_redirect_url' => esc_url( admin_url( 'admin.php?page=suretriggers' ) ),
				'config'               => [
					'st_embed_url'        => apply_filters( 'suretriggers_get_iframe_url', 'https://app.suretriggers.com/' ),
					'client_id'           => '4f26d5fa-d5bb-4910-8440-0fe1afaa3235',
					'embedded_identifier' => 'cart-abandonment-recovery',
					'target'              => 'wcar-iframe-wrapper',
					'summary'             => __( 'Create new automation', 'woo-cart-abandonment-recovery' ),
					'configure_trigger'   => true,
					'show_recipes'        => false,
					'style'               => [
						'button' => [
							'background' => '#F06434',
						],
						'icon'   => [
							'color' => '#b6d04c',
						],
					],
				],
			],
			'admin_url'               => esc_url( admin_url( 'admin.php?page=woo-cart-abandonment-recovery' ) ),
			'site_url'                => esc_url( site_url() ),
		];
		// Localize script with necessary data.
		wp_localize_script(
			$handle,
			'cart_abandonment_admin',
			apply_filters(
				'cart_abandonment_admin_vars',
				$data_vars
			)
		);
	}

	/**
	 * Prepare the array of RSS Feeds of Modern Cart for Whats New slide-out panel.
	 *
	 * @since 2.0.0
	 * @return array<string> The prepared array of RSS feeds.
	 */
	public function get_whats_new_rss_feeds_data() {
		return [
			'key'   => 'cart-abandonment',
			'label' => 'Cart Abandonemtn',
			'url'   => add_query_arg(
				[
					'action' => 'cart_abandonment_fetch_whats_new',
					'nonce'  => wp_create_nonce( 'cart_abandonment_fetch_whats_new' ),
				],
				admin_url( 'admin-ajax.php' )
			),
		];
	}

	/**
	 * Fetch the Whats New RSS feed from the URL.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function fetch_whats_new(): void {
		if ( ! isset( $_GET['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_GET['nonce'] ), 'cart_abandonment_fetch_whats_new' ) ) {
			// Verify the nonce, if it fails, return an error.
			wp_send_json_error( [ 'message' => __( 'Nonce verification failed.', 'woo-cart-abandonment-recovery' ) ] );
		}

		// Fetch the RSS feed from the URL. This saves us from the CORS issue.
		$feed = wp_remote_retrieve_body( wp_remote_get( 'https://cartflows.com/product/cart-abandonment/feed/' ) ); // phpcs:ignore -- This is a valid use case cannot use VIP rules here.

		echo $feed; // phpcs:ignore -- Cannot sanitize the XML data as it is not in our control here.
		exit;
	}

	/**
	 * Returns modern cart knowledge base data
	 *
	 * @since 2.0.0
	 *
	 * @return array<string>
	 */
	public static function get_knowledge_base() {
		$url = esc_url( 'https://cartflows.com/wp-json/powerful-docs/v1/get-docs' );

		// https://cartflows.com/wp-json/powerful-docs/v1/get-docs.
		// phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.wp_remote_get_wp_remote_get
		$response = wp_remote_get( $url );

		if ( is_wp_error( $response ) ) {
			return []; // Return empty array on failure.
		}

		$body = wp_remote_retrieve_body( $response );
		$data = json_decode( $body, true );

		if ( ! is_array( $data ) || ! isset( $data['docs'] ) || ! is_array( $data['docs'] ) ) {
			return []; // Return empty array if docs are not available.
		}

		$target_category = 'cart-abandonment';

		$filtered_docs = array_filter(
			$data['docs'],
			static function ( $item ) use ( $target_category ) {
				return in_array( $target_category, $item['category'] );
			}
		);

		return array_reverse( array_values( $filtered_docs ) ); // Reindex and reverse.
	}

	/**
	 * Get all cart abandonment settings with their values.
	 *
	 * @return array Array of all settings with their values.
	 */
	public function get_cart_abandonment_settings() {
		if ( ! function_exists( 'wcf_ca' ) ) {
			return [];
		}
		// get_default_settings.
		$defaults = wcf_ca()->options->get_default_settings();
		$settings = [];

		foreach ( $defaults as $option_key => $default_value ) {
			$settings[ $option_key ] = get_option( $option_key, $default_value );
		}

		$settings['cf_analytics_optin'] = wcf_ca()->utils->wcar_get_option( 'cf_analytics_optin' );

		/**
		 * Filter cart abandonment settings
		 *
		 * @param array $settings Array of all settings with their values.
		 * @since 2.0.0
		 */
		return apply_filters( 'wcf_ca_get_all_settings', $settings );
	}

	/**
	 * Get plugin status
	 *
	 * @since 2.0.0
	 * @param string $plugin Plugin path.
	 * @return string
	 */
	public function get_plugin_status( $plugin ) {

		$installed_plugins = get_plugins();

		if ( ! isset( $installed_plugins[ $plugin ] ) ) {
			return 'not-installed';
		}
		if ( is_plugin_active( $plugin ) ) {
			return 'active';
		}
			return 'inactive';
	}

	/**
	 * Activate a plugin via AJAX.
	 *
	 * @return void
	 */
	public function cart_abandonment_activate_plugin(): void {
		if ( ! isset( $_POST['security'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['security'] ), 'cart_abandonment_activate_plugin_nonce' ) ) {
			wp_send_json_error( [ 'message' => 'Nonce verification failed.' ] );
		}
		if ( ! current_user_can( 'activate_plugins' ) ) {
			wp_send_json_error( [ 'message' => 'You do not have permission to activate plugins.' ] );
		}
		$plugin_slug = isset( $_POST['init'] ) ? sanitize_text_field( $_POST['init'] ) : '';
		if ( empty( $plugin_slug ) ) {
			wp_send_json_error( [ 'message' => 'Invalid plugin slug.' ] );
		}
		$activation_result = activate_plugin( $plugin_slug );
		if ( is_wp_error( $activation_result ) ) {
			wp_send_json_error( [ 'message' => 'Plugin activation failed: ' . $activation_result->get_error_message() ] );
		}
		wp_send_json_success( [ 'message' => 'Plugin activated successfully.' ] );
	}

	/**
	 * Get wcar extend plugins
	 *
	 * @since 0.0.0
	 *
	 * @return array<mixed>
	 */
	public function wcar_get_extend_plugins() {
		$base_url = CARTFLOWS_CA_URL . 'admin/assets/images/dashboard/';
		return [
			[
				'title'  => __( 'CartFlows', 'woo-cart-abandonment-recovery' ),
				'desc'   => __( 'CartFlows helps users boost sales by creating optimized checkout flows and sales funnels.', 'woo-cart-abandonment-recovery' ),
				'status' => $this->get_plugin_status( 'cartflows/cartflows.php' ),
				'slug'   => 'cartflows',
				'path'   => 'cartflows/cartflows.php',
				'logo'   => esc_url( $base_url . 'cartflows.svg' ),
			],
			[
				'title'  => __( 'WooCommerce', 'woo-cart-abandonment-recovery' ),
				'desc'   => __( 'WooCommerce is a customizable, open-source ecommerce platform built on WordPress.', 'woo-cart-abandonment-recovery' ),
				'status' => $this->get_plugin_status( 'woocommerce/woocommerce.php' ),
				'slug'   => 'woocommerce',
				'path'   => 'woocommerce/woocommerce.php',
				'logo'   => esc_url( $base_url . 'woocommerce.svg' ),
			],
			[
				'title'  => __( 'Modern Cart', 'woo-cart-abandonment-recovery' ),
				'desc'   => __( 'Modern Cart helps shop owner improve their user experience, increase conversions & maximize profits.', 'woo-cart-abandonment-recovery' ),
				'status' => $this->get_plugin_status( 'modern-cart/modern-cart.php' ),
				'slug'   => 'modern-cart',
				'path'   => 'modern-cart/modern-cart.php',
				'logo'   => esc_url( $base_url . 'moderncart.svg' ),
			],
			[
				'title'  => __( 'OttoKit', 'woo-cart-abandonment-recovery' ),
				'desc'   => __( 'OttoKit automates work by integrating apps and plugins to share data and perform tasks automatically.', 'woo-cart-abandonment-recovery' ),
				'status' => $this->get_plugin_status( 'suretriggers/suretriggers.php' ),
				'slug'   => 'suretriggers',
				'path'   => 'suretriggers/suretriggers.php',
				'logo'   => esc_url( $base_url . 'ottokit.svg' ),
			],
		];
	}

	/**
	 * Get version display based on plugin status and license activation.
	 *
	 * @since 2.0.0
	 * @return string Version display text
	 */
	public function get_version_display() {
		$pro_status = $this->get_plugin_status( 'woo-cart-abandonment-recovery-pro/woo-cart-abandonment-recovery-pro.php' );
		
		// If pro plugin is active and license is activated.
		if ( 'active' === $pro_status && _is_wcar_pro_license_activated() ) {
			return 'Pro';
		}
		
		return 'Core';
	}
}
