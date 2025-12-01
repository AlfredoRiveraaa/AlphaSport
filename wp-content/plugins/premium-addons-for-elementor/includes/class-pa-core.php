<?php
/**
 * PA Core.
 */

namespace PremiumAddons\Includes;

if ( ! class_exists( 'PA_Core' ) ) {

	/**
	 * Intialize and Sets up the plugin
	 */
	class PA_Core {

		/**
		 * Member Variable
		 *
		 * @var instance
		 */
		private static $instance = null;

		/**
		 * Sets up needed actions/filters for the plug-in to initialize.
		 *
		 * @since 1.0.0
		 * @access public
		 *
		 * @return void
		 */
		public function __construct() {

			// Load plugin textdomain.
			add_action( 'init', array( $this, 'i18n' ) );

			// Run plugin and require the necessary files.
			add_action( 'plugins_loaded', array( $this, 'pa_init' ) );

			add_action( 'init', array( $this, 'init' ), -999 );

			// Register Activation hooks.
			register_activation_hook( PREMIUM_ADDONS_FILE, array( $this, 'handle_activation' ) );
			register_uninstall_hook( PREMIUM_ADDONS_FILE, array( __CLASS__, 'uninstall' ) );
		}

		/**
		 * Installs translation text domain and checks if Elementor is installed
		 *
		 * @since 1.0.0
		 * @access public
		 *
		 * @return void
		 */
		public function pa_init() {

			// Load plugin necessary files.
			\PremiumAddons\Admin\Includes\Admin_Helper::get_instance();

			$check_dynamic_assets = \PremiumAddons\Admin\Includes\Admin_Helper::check_element_by_key( 'premium-assets-generator' );

			if ( $check_dynamic_assets ) {
				\PremiumAddons\Includes\Assets_Manager::get_instance();
			}

			Addons_Integration::get_instance();

			include_once PREMIUM_ADDONS_PATH . 'includes/cm-pointer.php';
		}

		/**
		 * Set transient for admin review notice
		 *
		 * @since 3.1.7
		 * @access public
		 *
		 * @return void
		 */
		public function handle_activation() {

			$cache_key = 'pa_review_notice';

			$expiration = DAY_IN_SECONDS * 7;

			set_transient( $cache_key, true, $expiration );

			$install_time = get_option( 'pa_install_time' );

			if ( ! $install_time ) {

				$current_time = gmdate( 'j F, Y', time() );

				update_option( 'pa_complete_wizard', true );
				update_option( 'pa_install_time', $current_time );

				$api_url = 'https://feedbackpa.leap13.com/wp-json/install/v2/add';

				$response = wp_safe_remote_request(
					$api_url,
					array(
						'headers'     => array(
							'Content-Type' => 'application/json',
						),
						'body'        => wp_json_encode(
							array(
								'time' => $current_time,
							)
						),
						'timeout'     => 20,
						'method'      => 'POST',
						'httpversion' => '1.1',
					)
				);

				set_transient( 'pa_activation_redirect', true, 30 );
			}
		}

		public static function uninstall() {

			delete_option( 'pa_complete_wizard' );
			delete_option( 'pa_install_time' );
			delete_option( 'pa_review_notice' );

			$api_url = 'https://feedbackpa.leap13.com/wp-json/uninstall/v2/add';

			$current_time = gmdate( 'j F, Y', time() );

			$response = wp_safe_remote_request(
				$api_url,
				array(
					'headers'     => array(
						'Content-Type' => 'application/json',
					),
					'body'        => wp_json_encode(
						array(
							'time' => $current_time,
						)
					),
					'timeout'     => 20,
					'method'      => 'POST',
					'httpversion' => '1.1',
				)
			);
		}

		/**
		 * Load plugin translated strings using text domain
		 *
		 * @since 2.6.8
		 * @access public
		 *
		 * @return void
		 */
		public function i18n() {

			load_plugin_textdomain( 'premium-addons-for-elementor' );
		}

		/**
		 * Init
		 *
		 * @since 3.4.0
		 * @access public
		 *
		 * @return void
		 */
		public function init() {

			if ( is_user_logged_in() && \PremiumAddons\Admin\Includes\Admin_Helper::check_premium_templates() ) {
				require_once PREMIUM_ADDONS_PATH . 'includes/templates/templates.php';
			}
		}


		/**
		 * Creates and returns an instance of the class
		 *
		 * @since 2.6.8
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
}

if ( ! function_exists( 'pa_core' ) ) {

	/**
	 * Returns an instance of the plugin class.
	 *
	 * @since  1.0.0
	 * @return object
	 */
	function pa_core() {
		return PA_Core::get_instance();
	}
}

pa_core();
