<?php
/**
 * Update Compatibility
 *
 * @package Woocommerce-Cart-Abandonment-Recovery
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Cartflows_Ca_Update' ) ) {

	/**
	 * CartFlows CA Update initial setup
	 *
	 * @since 1.0.0
	 */
	class Cartflows_Ca_Update {
		/**
		 * Class instance.
		 *
		 * @access private
		 * @var Class $instance instance.
		 */
		private static $instance;

		/**
		 *  Constructor
		 */
		public function __construct() {
			add_action( 'admin_init', self::class . '::init' );
		}

		/**
		 * Initiator
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 *  Create tables if not exists and seed default settings.
		 */
		public static function update_table_with_default_settings(): void {

			$cartflows_loader = CARTFLOWS_CA_Loader::get_instance();
			$cartflows_loader->initialize_cart_abandonment_tables();
			$cartflows_loader->update_default_settings();
		}

		/**
		 * Init
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public static function init(): void {

			do_action( 'cartflows_ca_update_before' );

			// Get auto saved version number.
			$saved_version = get_option( 'wcf_ca_version', false );

			// Update auto saved version number.
			if ( ! $saved_version ) {
				self::update_table_with_default_settings();
				// Fresh install: enable new UI by default.
				update_option( 'cartflows_ca_use_new_ui', true );
				update_option( 'wcf_ca_version', CARTFLOWS_CA_VER );
				return;
			}

			// If equals then return.
			if ( version_compare( $saved_version, CARTFLOWS_CA_VER, '=' ) ) {
				return;
			}

			if ( version_compare( $saved_version, '1.2.16', '<' ) ) {
				update_option( 'wcf_ca_show_weekly_report_email_notice', 'yes' );
			}

			if ( version_compare( $saved_version, '2.0.0', '<' ) ) {
				self::handle_ignore_users_option_on_upgrade();
			}

			// Handle UI switching logic for version upgrades.
			self::handle_ui_option_on_upgrade( $saved_version );

			// Update auto saved version number.
			update_option( 'wcf_ca_version', CARTFLOWS_CA_VER );

			self::update_table_with_default_settings();

			do_action( 'cartflows_ca_update_after' );
		}

		/**
		 * Handle UI option during version upgrades.
		 *
		 * @since 1.3.2
		 * @param string $saved_version The previously saved version.
		 * @return void
		 */
		public static function handle_ui_option_on_upgrade( $saved_version ): void {
			// Only set the option if it doesn't already exist (user hasn't made a choice).
			if ( false === get_option( 'cartflows_ca_use_new_ui', false ) ) {
				
				// For versions above 2.0.0 default to new UI.
				if ( version_compare( $saved_version, '2.0.0', '>' ) ) {
					update_option( 'cartflows_ca_use_new_ui', true );
				}
				
				// For versions below 2.0.0, leave option as false (legacy UI with notice).
				// This allows users to see the notice and choose to upgrade.
			}
		}

		
		/**
		 * Handles the migration of the 'wcf_ca_ignore_users' option during upgrade.
		 *
		 * This function updates the 'wcf_ca_ignore_users' option to store user role keys instead of role names.
		 * It retrieves the current ignored user roles (by name), maps them to their corresponding role keys,
		 * and updates the option with the new format. This ensures compatibility with newer plugin versions
		 * that expect role keys instead of names.
		 *
		 * @since 2.0.0
		 * @return void
		 */
		public static function handle_ignore_users_option_on_upgrade(): void {
			$ignore_users  = wcf_ca()->utils->wcar_get_option( 'wcf_ca_ignore_users', [] );
				$roles     = wcf_ca()->helper->get_wordpress_user_roles();
				$new_roles = [];

			foreach ( $roles as $key => $value ) {
				if ( in_array( $value, $ignore_users, true ) ) {
					$new_roles[] = $key;
				}
			}
				update_option( 'wcf_ca_ignore_users', $new_roles );
		}
	}

	/**
	 * Kicking this off by calling 'get_instance()' method
	 */
	Cartflows_Ca_Update::get_instance();

}
