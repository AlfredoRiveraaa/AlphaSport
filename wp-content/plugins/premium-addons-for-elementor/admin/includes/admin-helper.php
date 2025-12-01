<?php
/**
 * PA Admin Helper
 */

namespace PremiumAddons\Admin\Includes;

use PremiumAddons\Includes\Helper_Functions;
use Elementor\Modules\Usage\Module;
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Admin_Helper
 */
class Admin_Helper {

	/**
	 * Admin settings tabs
	 *
	 * @var tabs
	 */
	private static $tabs = null;

	/**
	 * Class instance
	 *
	 * @var instance
	 */
	private static $instance = null;

	/**
	 * Premium Addons Settings Page Slug
	 *
	 * @var page_slug
	 */
	public static $page_slug = 'premium-addons';

	/**
	 * Elements List
	 *
	 * @var elements_list
	 */
	public static $elements_list = null;

	/**
	 * Elements Keys
	 *
	 * @var elements_list
	 */
	public static $elements_keys = null;

	/**
	 * Enabled Elements
	 *
	 * @var enabled_elements
	 */
	public static $enabled_elements = null;

	/**
	 * Integrations Settings
	 *
	 * @var integrations_settings
	 */
	public static $integrations_settings = null;

	/**
	 * Elements Names
	 *
	 * @var elements_names
	 */
	public static $elements_names = null;

	/**
	 * Integrations List
	 *
	 * @var integrations_list
	 */
	public static $integrations_list = null;

	/**
	 * Constructor for the class
	 */
	public function __construct() {

		// Insert admin settings submenus.
		add_action( 'admin_menu', array( $this, 'add_menu_tabs' ), 100 );

		// Enqueue required admin scripts.
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

		// Plugin Action Links.
		add_filter( 'plugin_action_links_' . PREMIUM_ADDONS_BASENAME, array( $this, 'insert_action_links' ) );
		add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 2 );

		// Register AJAX HOOKS.
		add_action( 'wp_ajax_pa_save_global_btn', array( $this, 'save_global_btn_value' ) );
		add_action( 'wp_ajax_pa_elements_settings', array( $this, 'save_settings' ) );
		add_action( 'wp_ajax_pa_disable_elementor_mc_template', array( $this, 'disable_elementor_mc_template' ) );
		add_action( 'wp_ajax_pa_additional_settings', array( $this, 'save_additional_settings' ) );
		add_action( 'wp_ajax_pa_get_unused_widgets', array( $this, 'get_unused_widgets' ) );
		add_action( 'wp_ajax_get_pa_menu_item_settings', array( $this, 'get_pa_menu_item_settings' ) );
		add_action( 'wp_ajax_save_pa_menu_item_settings', array( $this, 'save_pa_menu_item_settings' ) );
		add_action( 'wp_ajax_save_pa_mega_item_content', array( $this, 'save_pa_mega_item_content' ) );

		// Register AJAX Hooks for regenerate assets.
		add_action( 'wp_ajax_pa_clear_cached_assets', array( $this, 'clear_cached_assets' ) );

		// Register Deactivation hooks.
		register_deactivation_hook( PREMIUM_ADDONS_FILE, array( $this, 'clear_dynamic_assets_data' ) );

		// Register AJAX Hooks for clearing saved site cursor.
		add_action( 'wp_ajax_pa_clear_site_cursor_settings', array( $this, 'clear_site_cursor_settings' ) );

		// Register AJAX Hooks for Newsletter.
		add_action( 'wp_ajax_subscribe_newsletter', array( $this, 'subscribe_newsletter' ) );

		// Add action for PA dashboard tab header.
		add_action( 'pa_before_render_admin_tabs', array( $this, 'render_dashboard_header' ) );

		// Register Rollback hooks.
		add_action( 'admin_post_premium_addons_rollback', array( $this, 'run_pa_rollback' ) );

		if ( is_admin() ) {

			Admin_Notices::get_instance();

			// Beta tester.
			// Not currently needed.
			// Beta_Testers::get_instance();.

			// PA Duplicator.
			if ( self::check_duplicator() ) {
				Duplicator::get_instance();
			}

			if ( self::check_user_can( 'install_plugins' ) ) {
				Feedback::get_instance();
			}
		}

		// PA Dynamic Assets.
		$row_meta = Helper_Functions::is_hide_row_meta();

		if ( ! is_admin() && self::check_dynamic_assets() && ! $row_meta ) {
			Admin_Bar::get_instance();
		}
	}

	/**
	 * Checks user credentials for specific action
	 *
	 * @since 2.6.8
	 *
	 * @param string $action action.
	 *
	 * @return boolean
	 */
	public static function check_user_can( $action ) {
		return current_user_can( $action );
	}

	/**
	 * Get Elements List
	 *
	 * Get a list of all the elements available in the plugin
	 *
	 * @since 3.20.9
	 * @access private
	 *
	 * @return array elements_list
	 */
	public static function get_elements_list() {

		if ( null === self::$elements_list ) {

			self::$elements_list = require_once PREMIUM_ADDONS_PATH . 'admin/includes/elements.php';

		}

		return self::$elements_list;
	}

	/**
	 * Get Elements Keys
	 *
	 * Get a list of all the keys available in the plugin
	 *
	 * @since 4.10.54
	 * @access private
	 *
	 * @return array elements_keys
	 */
	public static function get_elements_keys() {

		if ( null === self::$elements_keys ) {

			self::$elements_keys = require_once PREMIUM_ADDONS_PATH . 'admin/includes/keys.php';

		}

		return self::$elements_keys;
	}

	/**
	 * Get Integrations List
	 *
	 * Get a list of all the integrations available in the plugin
	 *
	 * @since 3.20.9
	 * @access private
	 *
	 * @return array integrations_list
	 */
	private static function get_integrations_list() {

		if ( null === self::$integrations_list ) {

			self::$integrations_list = array(
				'premium-map-api',
				'premium-youtube-api',
				'premium-map-disable-api',
				'premium-map-cluster',
				'premium-wp-optimize-exclude',
				'premium-map-locale',
				'is-beta-tester',
			);

		}

		return self::$integrations_list;
	}

	/**
	 * Admin Enqueue Scripts
	 *
	 * Enqueue the required assets on our admin pages
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $hook The current admin page hook.
	 */
	public function admin_enqueue_scripts( $hook ) {

		$enabled_elements = self::get_enabled_elements();
		$action           = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';

		wp_enqueue_style(
			'pa_admin_icon',
			PREMIUM_ADDONS_URL . 'admin/assets/fonts/style.css',
			array(),
			PREMIUM_ADDONS_VERSION,
			'all'
		);

		wp_enqueue_style(
			'pa-notice',
			PREMIUM_ADDONS_URL . 'admin/assets/css/notice.css',
			array(),
			PREMIUM_ADDONS_VERSION,
			'all'
		);

		wp_enqueue_style(
			'pa-admin',
			PREMIUM_ADDONS_URL . 'admin/assets/css/admin.css',
			array(),
			PREMIUM_ADDONS_VERSION,
			'all'
		);

		if ( false !== strpos( $hook, 'premium-addons' ) ) {

			wp_enqueue_style(
				'pa-sweetalert-style',
				PREMIUM_ADDONS_URL . 'admin/assets/js/sweetalert2/sweetalert2.min.css',
				array(),
				PREMIUM_ADDONS_VERSION,
				'all'
			);

			wp_enqueue_script(
				'pa-admin',
				PREMIUM_ADDONS_URL . 'admin/assets/js/admin.js',
				array( 'jquery' ),
				PREMIUM_ADDONS_VERSION,
				true
			);

			wp_enqueue_script(
				'pa-sweetalert-core',
				PREMIUM_ADDONS_URL . 'admin/assets/js/sweetalert2/core.js',
				array( 'jquery' ),
				PREMIUM_ADDONS_VERSION,
				true
			);

			wp_enqueue_script(
				'pa-sweetalert',
				PREMIUM_ADDONS_URL . 'admin/assets/js/sweetalert2/sweetalert2.min.js',
				array( 'jquery', 'pa-sweetalert-core' ),
				PREMIUM_ADDONS_VERSION,
				true
			);

			$theme_slug = Helper_Functions::get_installed_theme();

			$is_second_run = get_option( 'pa_complete_wizard' ) ? false : true;

			$localized_data = array(
				'settings'               => array(
					'ajaxurl'           => admin_url( 'admin-ajax.php' ),
					'nonce'             => wp_create_nonce( 'pa-settings-tab' ),
					'unused_nonce'      => wp_create_nonce( 'pa-disable-unused' ),
					'generate_nonce'    => wp_create_nonce( 'pa-generate-nonce' ),
					'site_cursor_nonce' => wp_create_nonce( 'pa-site-cursor-nonce' ),
					'isSecondRun'       => $is_second_run,
					'theme'             => $theme_slug,
					'i18n'              => array(
						'successMsg' => __( 'Your submission was successful.', 'premium-addons-for-elementor' ),
						'failMsg'    => __( 'Your submission failed because of an error', 'premium-addons-for-elementor' ),
					),
				),
				'premiumRollBackConfirm' => array(
					'home_url' => home_url(),
					'i18n'     => array(
						'rollback_to_previous_version' => __( 'Rollback to Previous Version', 'premium-addons-for-elementor' ),
						/* translators: %s: PA stable version */
						'rollback_confirm'             => sprintf( __( 'Are you sure you want to reinstall version %s?', 'premium-addons-for-elementor' ), PREMIUM_ADDONS_STABLE_VERSION ),
						'yes'                          => __( 'Continue', 'premium-addons-for-elementor' ),
						'cancel'                       => __( 'Cancel', 'premium-addons-for-elementor' ),
					),
				),
			);

			// Only add savedFeatures if it's the second run.
			if ( $is_second_run ) {
				$localized_data['settings']['savedFeatures'] = get_option( 'pa_saved_features', array() );
			}

			// Add PAPRO Rollback Confirm message if PAPRO installed.
			if ( Helper_Functions::check_papro_version() ) {
				/* translators: %s: PA stable version */
				$localized_data['premiumRollBackConfirm']['i18n']['papro_rollback_confirm'] = sprintf( __( 'Are you sure you want to reinstall version %s?', 'premium-addons-for-elementor' ), PREMIUM_ADDONS_STABLE_VERSION );
			}

			wp_localize_script( 'pa-admin', 'premiumAddonsSettings', $localized_data );

		}

		if ( false !== strpos( $action, 'page=pa-setup-wizard' ) ) {
			wp_enqueue_style(
				'pa-wizard',
				PREMIUM_ADDONS_URL . 'admin/assets/css/setup-wizard.css',
				array(),
				PREMIUM_ADDONS_VERSION,
				'all'
			);

			wp_enqueue_script(
				'pa-wizard',
				PREMIUM_ADDONS_URL . 'admin/assets/js/setup-wizard.js',
				array( 'jquery' ),
				PREMIUM_ADDONS_VERSION,
				true
			);

			wp_localize_script(
				'pa-wizard',
				'paWizardSettings',
				array(
					'ajaxurl'       => admin_url( 'admin-ajax.php' ),
					'nonce'         => wp_create_nonce( 'pa-wizard-nonce' ),
					'exitWizardURL' => admin_url( 'plugins.php' ),
					'isSecondRun'   => get_option( 'pa_complete_wizard' ) ? false : true,
					'dashboardURL'  => admin_url( 'admin.php' ) . '?page=premium-addons#tab=elements',
					'newPageURL'    => Plugin::$instance->documents->get_create_new_post_url(),
				),
			);

		}

		if ( 'nav-menus.php' === $hook && $enabled_elements['premium-nav-menu'] ) {

			wp_enqueue_style(
				'pa-font-awesome',
				ELEMENTOR_ASSETS_URL . 'lib/font-awesome/css/font-awesome.min.css',
				array(),
				'4.7.0',
				'all'
			);

			wp_enqueue_style( 'wp-color-picker' );

			wp_enqueue_style(
				'jquery-fonticonpicker',
				PREMIUM_ADDONS_URL . 'admin/assets/css/jquery-fonticonpicker.css',
				array(),
				PREMIUM_ADDONS_VERSION,
				'all'
			);

			wp_enqueue_script(
				'jquery-fonticonpicker',
				PREMIUM_ADDONS_URL . 'admin/assets/js/jquery-fonticonpicker.js',
				array( 'jquery' ),
				PREMIUM_ADDONS_VERSION,
				true
			);

			wp_enqueue_script(
				'pa-icon-list',
				PREMIUM_ADDONS_URL . 'admin/assets/js/premium-icons-list.js',
				array(),
				PREMIUM_ADDONS_VERSION,
				true
			);

			wp_enqueue_script(
				'mega-content-handler',
				PREMIUM_ADDONS_URL . 'admin/assets/js/mega-content-handler.js',
				array( 'jquery' ),
				PREMIUM_ADDONS_VERSION,
				true
			);

			wp_enqueue_script(
				'menu-editor',
				PREMIUM_ADDONS_URL . 'admin/assets/js/menu-editor.js',
				array( 'jquery', 'wp-color-picker' ),
				PREMIUM_ADDONS_VERSION,
				true
			);

			$pa_menu_localized = array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'pa-menu-nonce' ),
			);

			$menu_content_localized = array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'pa-live-editor' ),
			);

			wp_localize_script( 'mega-content-handler', 'paMegaContent', $menu_content_localized );
			wp_localize_script( 'menu-editor', 'paMenuSettings', $pa_menu_localized );

			// menu screen popups.
			include_once PREMIUM_ADDONS_PATH . 'admin/includes/templates/nav-menu-settings.php';
		}
	}

	/**
	 * Get PA menu item settings.
	 * Retrieve menu items settings from postmeta table.
	 *
	 * @access public
	 * @since 4.9.4
	 */
	public function get_pa_menu_item_settings() {

		check_ajax_referer( 'pa-menu-nonce', 'security' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'User is not authorized!' );
		}

		if ( ! isset( $_POST['item_id'] ) ) {
			wp_send_json_error( 'Settings are not set!' );
		}

		$item_id       = sanitize_text_field( wp_unslash( $_POST['item_id'] ) );
		$item_settings = json_decode( get_post_meta( $item_id, 'pa_megamenu_item_meta', true ) );

		wp_send_json_success( $item_settings );
	}

	/**
	 * Save PA menu item settings.
	 * Save/Update menu items settings in postmeta table.
	 *
	 * @access public
	 * @since 4.9.4
	 */
	public function save_pa_menu_item_settings() {

		check_ajax_referer( 'pa-menu-nonce', 'security' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'User is not authorized!' );
		}

		if ( ! isset( $_POST['settings'] ) ) {
			wp_send_json_error( 'Settings are not set!' );
		}

		$settings = array_map(
			function ( $setting ) {
				return htmlspecialchars( $setting, ENT_QUOTES );
			},
			wp_unslash( $_POST['settings'] ) // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		);

		update_post_meta( $settings['item_id'], 'pa_megamenu_item_meta', wp_json_encode( $settings, JSON_UNESCAPED_UNICODE ) );

		wp_send_json_success( $settings );
	}

	/**
	 * Save Pa Mega Item Content.
	 * Saves mega content's id in postmeta table.
	 *
	 * @access public
	 * @since 4.9.4
	 */
	public function save_pa_mega_item_content() {

		check_ajax_referer( 'pa-live-editor', 'security' );

		if ( ! current_user_can( 'edit_theme_options' ) ) {
			wp_send_json_error( 'Insufficient user permission' );
		}

		if ( ! isset( $_POST['template_id'] ) ) {
			wp_send_json_error( 'template id is not set!' );
		}

		if ( ! isset( $_POST['menu_item_id'] ) ) {
			wp_send_json_error( 'item id is not set!' );
		}

		$item_id = sanitize_text_field( wp_unslash( $_POST['menu_item_id'] ) );
		$temp_id = sanitize_text_field( wp_unslash( $_POST['template_id'] ) );

		update_post_meta( $item_id, 'pa_mega_content_temp', $temp_id );

		wp_send_json_success( 'Item Mega Content Saved' );
	}

	/**
	 * Insert action links.
	 *
	 * Adds action links to the plugin list table
	 *
	 * Fired by `plugin_action_links` filter.
	 *
	 * @param array $links plugin action links.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function insert_action_links( $links ) {

		$is_papro_active = Helper_Functions::check_papro_version();

		$settings_link = sprintf( '<a href="%1$s">%2$s</a>', admin_url( 'admin.php?page=' . self::$page_slug . '#tab=elements' ), __( 'Settings', 'premium-addons-for-elementor' ) );

		$rollback_link = sprintf( '<a href="%1$s">%2$s%3$s</a>', wp_nonce_url( admin_url( 'admin-post.php?action=premium_addons_rollback' ), 'premium_addons_rollback' ), __( 'Rollback to v', 'premium-addons-for-elementor' ), PREMIUM_ADDONS_STABLE_VERSION );

		$new_links = array( $settings_link );

		if ( ! $is_papro_active ) {

			$link = Helper_Functions::get_campaign_link( 'https://premiumaddons.com/black-friday/#bfdeals', 'plugins-page', 'wp-dash', 'get-pro' );

			$pro_link = sprintf( '<a href="%s" target="_blank" style="color: #FF6000; font-weight: bold;">%s</a>', $link, __( 'Save $105', 'premium-addons-for-elementor' ) );
			array_push( $new_links, $pro_link );
		}

		$new_links = array_merge( $links, $new_links );

		return $new_links;
	}

	/**
	 * Plugin row meta.
	 *
	 * Extends plugin row meta links
	 *
	 * Fired by `plugin_row_meta` filter.
	 *
	 * @since 3.8.4
	 * @access public
	 *
	 * @param array  $meta array of the plugin's metadata.
	 * @param string $file path to the plugin file.
	 *
	 *  @return array An array of plugin row meta links.
	 */
	public function plugin_row_meta( $meta, $file ) {

		if ( Helper_Functions::is_hide_row_meta() ) {
			return $meta;
		}

		if ( PREMIUM_ADDONS_BASENAME === $file ) {

			$link = Helper_Functions::get_campaign_link( 'https://premiumaddons.com/support', 'plugins-page', 'wp-dash', 'get-support' );

			$row_meta = array(
				'docs'   => '<a href="' . esc_attr( $link ) . '" aria-label="' . esc_attr( __( 'View Premium Addons for Elementor Documentation', 'premium-addons-for-elementor' ) ) . '" target="_blank">' . __( 'Docs & FAQs', 'premium-addons-for-elementor' ) . '</a>',
				'videos' => '<a href="https://www.youtube.com/leap13" aria-label="' . esc_attr( __( 'View Premium Addons Video Tutorials', 'premium-addons-for-elementor' ) ) . '" target="_blank">' . __( 'Video Tutorials', 'premium-addons-for-elementor' ) . '</a>',
				'rate'   => '<a href="https://wordpress.org/support/plugin/premium-addons-for-elementor/reviews/#new-post" aria-label="' . esc_attr( __( 'Rate plugin', 'premium-addons-for-elementor' ) ) . '" target="_blank">' . __( 'Rate the plugin ★★★★★', 'premium-addons-for-elementor' ) . '</a>',
			);

			$meta = array_merge( $meta, $row_meta );
		}

		return $meta;
	}

	/**
	 * Set Admin Tabs
	 *
	 * @access private
	 * @since 3.20.8
	 */
	private function set_admin_tabs() {

		$slug = self::$page_slug;

		self::$tabs = array(
			'general'         => array(
				'id'       => 'general',
				'slug'     => $slug . '#tab=general',
				'title'    => __( 'General', 'premium-addons-for-elementor' ),
				'href'     => '#tab=general',
				'template' => PREMIUM_ADDONS_PATH . 'admin/includes/templates/general',
			),
			'elements'        => array(
				'id'       => 'elements',
				'slug'     => $slug . '#tab=elements',
				'title'    => __( 'Widgets & Add-ons', 'premium-addons-for-elementor' ),
				'href'     => '#tab=elements',
				'template' => PREMIUM_ADDONS_PATH . 'admin/includes/templates/modules-settings',
			),
			'addons'          => array(
				'id'       => 'addons',
				'slug'     => $slug . '#tab=addons',
				'title'    => __( 'Global Addons', 'premium-addons-for-elementor' ),
				'href'     => '#tab=addons',
				'template' => PREMIUM_ADDONS_PATH . 'admin/includes/templates/addons',
			),
			'integrations'    => array(
				'id'       => 'integrations',
				'slug'     => $slug . '#tab=integrations',
				'title'    => __( 'Integrations', 'premium-addons-for-elementor' ),
				'href'     => '#tab=integrations',
				'template' => PREMIUM_ADDONS_PATH . 'admin/includes/templates/integrations',
			),
			'version-control' => array(
				'id'       => 'vcontrol',
				'slug'     => $slug . '#tab=vcontrol',
				'title'    => __( 'Version Control', 'premium-addons-for-elementor' ),
				'href'     => '#tab=vcontrol',
				'template' => PREMIUM_ADDONS_PATH . 'admin/includes/templates/version-control',
			),
			'white-label'     => array(
				'id'       => 'white-label',
				'slug'     => $slug . '#tab=white-label',
				'title'    => __( 'White Labeling', 'premium-addons-for-elementor' ),
				'href'     => '#tab=white-label',
				'template' => PREMIUM_ADDONS_PATH . 'admin/includes/templates/white-label',
			),
			'info'            => array(
				'id'       => 'system-info',
				'slug'     => $slug . '#tab=system-info',
				'title'    => __( 'System Info', 'premium-addons-for-elementor' ),
				'href'     => '#tab=system-info',
				'template' => PREMIUM_ADDONS_PATH . 'admin/includes/templates/info',
			),
		);

		if ( ! Helper_Functions::check_papro_version() ) {

			self::$tabs['license'] = array(
				'id'       => 'license',
				'slug'     => $slug . '#tab=license',
				'title'    => __( 'License', 'premium-addons-for-elementor' ),
				'href'     => '#tab=license',
				'template' => PREMIUM_ADDONS_PATH . 'admin/includes/templates/license',
			);

		}

		self::$tabs = apply_filters( 'pa_admin_register_tabs', self::$tabs );
	}

	/**
	 * Add Menu Tabs
	 *
	 * Create Submenu Page
	 *
	 * @since 3.20.9
	 * @access public
	 *
	 * @return void
	 */
	public function add_menu_tabs() {

		$this->set_admin_tabs();

		$plugin_name = Helper_Functions::name();

		call_user_func(
			'add_menu_page',
			$plugin_name,
			$plugin_name,
			'manage_options',
			self::$page_slug,
			array( $this, 'render_setting_tabs' ),
			'',
			100
		);

		foreach ( self::$tabs as $tab ) {

			call_user_func(
				'add_submenu_page',
				self::$page_slug,
				$tab['title'],
				$tab['title'],
				'manage_options',
				$tab['slug'],
				'__return_null'
			);
		}

		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			call_user_func(
				'add_submenu_page',
				self::$page_slug,
				__( 'PA Setup Wizard', 'premium-addons-for-elementor' ),
				__( 'Run Setup Wizard', 'premium-addons-for-elementor' ),
				'manage_options',
				'pa-setup-wizard',
				array( $this, 'pa_init_setup_wizard' )
			);
		}

		$is_papro_active = Helper_Functions::check_papro_version();

		if ( ! $is_papro_active ) {
			call_user_func(
				'add_submenu_page',
				self::$page_slug,
				'<span style="color: #FF6000;" class="pa_pro_upgrade">Get PRO (Up to $105 OFF)</span>',
				'<span style="color: #FF6000;" class="pa_pro_upgrade">Get PRO (Up to $105 OFF)</span>',
				'manage_options',
				'https://premiumaddons.com/pro/#get-pa-pro',
				''
			);
		}

		// To remove the main page link from the tabs.
		remove_submenu_page( self::$page_slug, self::$page_slug );
	}

	/**
	 * Initializes the setup wizard Add the PRO popup template.
	 *
	 * @access public
	 * @since 3.20.8
	 */
	public function pa_init_setup_wizard() {

		include_once PREMIUM_ADDONS_PATH . 'admin/includes/setup-wizard/main-view.php';
		// Add the PRO popup template.
		include_once PREMIUM_ADDONS_PATH . 'admin/includes/templates/pro-popup.php';
	}

	/**
	 * Render Setting Tabs.
	 *
	 * Render the final HTML content for admin setting tabs.
	 *
	 * @access public
	 * @since 3.20.8
	 */
	public function render_setting_tabs() {

		// add the PRO popup template.
		include_once PREMIUM_ADDONS_PATH . 'admin/includes/templates/pro-popup.php';

		?>
		<div class="pa-settings-wrap">
			<?php do_action( 'pa_before_render_admin_tabs' ); ?>
			<div class="pa-settings-tabs">
				<ul class="pa-settings-tabs-list">
					<?php
					foreach ( self::$tabs as $key => $tab ) {
						$link          = '<li class="pa-settings-tab">';
							$link     .= '<a id="pa-tab-link-' . esc_attr( $tab['id'] ) . '"';
							$link     .= ' href="' . esc_url( $tab['href'] ) . '">';
								$link .= '<i class="pa-dash-' . esc_attr( $tab['id'] ) . '"></i>';
								$link .= '<span>' . esc_html( $tab['title'] ) . '</span>';
							$link     .= '</a>';
						$link         .= '</li>';

						echo $link; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					}
					?>
				</ul>
			</div> <!-- Settings Tabs -->

			<div class="pa-settings-sections">
				<?php
				foreach ( self::$tabs as $key => $tab ) {
					echo '<div id="pa-section-' . esc_attr( $tab['id'] ) . '" class="pa-section pa-section-' . esc_attr( $key ) . '">';
						include_once $tab['template'] . '.php';
					echo '</div>';
				}
				?>
			</div> <!-- Settings Sections -->
			<?php do_action( 'pa_after_render_admin_tabs' ); ?>
		</div> <!-- Settings Wrap -->
		<?php
	}

	/**
	 * Render Dashboard Header
	 *
	 * @since 4.0.0
	 * @access public
	 */
	public function render_dashboard_header() {

		$show_logo = Helper_Functions::is_hide_logo();

		?>

		<div class="papro-admin-notice">
			<?php if ( ! $show_logo ) : ?>
				<div class="papro-admin-notice-left">


						<div class="papro-admin-notice-logo">
							<img class="pa-notice-logo" src="<?php echo esc_attr( PREMIUM_ADDONS_URL . 'admin/images/papro-notice-logo.png' ); ?>">
						</div>

						<a href="https://premiumaddons.com/" target="_blank"></a>

				</div>
			<?php endif; ?>

			<?php
				$banner_content = $this->get_banner_strings();

			if ( is_array( $banner_content ) ) :
				?>
					<div class="papro-admin-notice-right">
						<div class="papro-admin-notice-info">
							<h4>
								<?php echo wp_kses_post( $banner_content['title'] ); ?>
							</h4>
							<p>
								<?php echo wp_kses_post( $banner_content['desc'] ); ?>
							</p>
						</div>
						<div class="papro-admin-notice-cta">
							<a class="papro-notice-btn" href="<?php echo esc_url( $banner_content['cta'] ); ?>" target="_blank">
								<?php echo wp_kses_post( $banner_content['btn'] ); ?>
							</a>
						</div>
					</div>
				<?php endif; ?>
		</div>

		<?php
	}

	/**
	 * Retrieves banner strings.
	 *
	 * @access public
	 * @return array|null
	 */
	public function get_banner_strings() {

		$license_info = get_transient( 'pa_license_info' );

		if ( ! Helper_Functions::check_papro_version() || ! $license_info ) {
			return array(
				'title' => __( 'Get Premium Addons PRO', 'premium-addons-for-elementor' ),
				'desc'  => __( 'Supercharge your Elementor with PRO Widgets & Addons that you won\'t find anywhere else.', 'premium-addons-for-elementor' ) . '<span class="papro-sale-notice">' . __( 'save up to $105!', 'premium-addons-for-elementor' ) . '</span>',
				'btn'   => __( 'Get Pro', 'premium-addons-for-elementor' ),
				'cta'   => 'https://premiumaddons.com/get/papro/#get-pa-pro',
			);

		} if( isset( $license_info['id'] ) && '4' !== $license_info['id'] ) {

			$upgrade_link = Helper_Functions::get_campaign_link( 'http://premiumaddons.com/docs/upgrade-premium-addons-license/', 'dashboard-banner', 'wp-dash', 'upgrade-pro' );

			return array(
				'title' => __( 'Upgrade to Lifetime!', 'premium-addons-for-elementor' ),
				'desc'  => __( 'Pay only the difference and enjoy an <span class="papro-sale-notice"> EXTRA 35% OFF</span> when you upgrade your Premium Addons Pro license to Lifetime — no renewals, no hassle, just lifetime access forever.', 'premium-addons-for-elementor' ),
				'btn'   => __( 'Upgrade Now', 'premium-addons-for-elementor' ),
				'cta'   => $upgrade_link,
			);

		}
	}

	/**
	 * Save Settings.
	 *
	 * Save elements settings using AJAX.
	 *
	 * @access public
	 * @since 3.20.8
	 */
	public function save_settings() {

		check_ajax_referer( 'pa-settings-tab', 'security' );

		if ( ! isset( $_POST['fields'] ) ) {
			return;
		}

		parse_str( sanitize_text_field( wp_unslash( $_POST['fields'] ) ), $settings );

		$defaults = self::get_default_keys();

		$elements = array_fill_keys( array_keys( array_intersect_key( $settings, $defaults ) ), true );

		update_option( 'pa_save_settings', $elements );

		// Clear cache and static property.
		wp_cache_delete( 'pa_elements', 'premium_addons' );
		self::$enabled_elements = null;

		// Save the global addons only if it's the second run.
		$is_second_run = get_option( 'pa_complete_wizard' ) ? false : true;
		if ( $is_second_run ) {
			self::update_global_addons_option( $settings );
		} else {
			update_option( 'pa_complete_wizard', false );
		}

		wp_send_json_success();
	}

	private static function update_global_addons_option( $settings ) {

		$global_addons = array(
			'premium-mscroll',
			'premium-templates',
			'pa-display-conditions',
			'premium-equal-height',
			'premium-global-cursor',
			'premium-global-badge',
			'premium-shape-divider',
			'premium-global-tooltips',
			'premium-floating-effects',
			'premium-cross-domain',
			'premium-duplicator',
			'premium-wrapper-link',
			'premium-assets-generator',
		);

		$features = array();
		foreach ( $global_addons as $feature ) {
			if ( isset( $settings[ $feature ] ) && 'on' === $settings[ $feature ] ) {
				$features[] = $feature;
			}
		}

		update_option( 'pa_saved_features', $features );
	}

	/**
	 * Save Integrations Control Settings
	 *
	 * Stores integration and version control settings
	 *
	 * @since 3.20.8
	 * @access public
	 */
	public function save_additional_settings() {

		check_ajax_referer( 'pa-settings-tab', 'security' );

		if ( ! isset( $_POST['fields'] ) ) {
			return;
		}

		parse_str( sanitize_text_field( wp_unslash( $_POST['fields'] ) ), $settings );

		$new_settings = array(
			'premium-map-api'             => sanitize_text_field( $settings['premium-map-api'] ),
			'premium-youtube-api'         => sanitize_text_field( $settings['premium-youtube-api'] ),
			'premium-map-disable-api'     => intval( $settings['premium-map-disable-api'] ? 1 : 0 ),
			'premium-map-cluster'         => intval( $settings['premium-map-cluster'] ? 1 : 0 ),
			'premium-wp-optimize-exclude' => intval( $settings['premium-wp-optimize-exclude'] ? 1 : 0 ),
			'premium-map-locale'          => sanitize_text_field( $settings['premium-map-locale'] ),
			'is-beta-tester'              => intval( $settings['is-beta-tester'] ? 1 : 0 ),
		);

		update_option( 'pa_maps_save_settings', $new_settings );

		wp_send_json_success( $settings );
	}

	/**
	 * Save Global Button Value
	 *
	 * Saves value for elements global switcher
	 *
	 * @since 4.0.0
	 * @access public
	 */
	public function save_global_btn_value() {

		check_ajax_referer( 'pa-settings-tab', 'security' );

		if ( ! isset( $_POST['isGlobalOn'] ) ) {
			wp_send_json_error();
		}

		$global_btn_value = sanitize_text_field( wp_unslash( $_POST['isGlobalOn'] ) );

		update_option( 'pa_global_btn_value', $global_btn_value );

		wp_send_json_success();
	}

	/**
	 * Get default Elements
	 *
	 * @since 3.20.9
	 * @access private
	 *
	 * @return $default_keys array keys defaults
	 */
	private static function get_default_keys() {

		$elements = self::get_elements_keys();

		$keys = array();

		// Now, we need to fill our array with elements keys.

		foreach ( $elements as $elem ) {

			array_push( $keys, $elem['key'] );

			if ( isset( $elem['draw_svg'] ) ) {
				array_push( $keys, 'svg_' . $elem['key'] );
			}
		}

		$default_keys = array_fill_keys( $keys, true );

		$default_keys['pa_mc_temp'] = false;

		return $default_keys;
	}

	/**
	 * Get Pro Elements.
	 * Return PAPRO Widgets.
	 *
	 * @since 4.5.3
	 * @access public
	 *
	 * @return array
	 */
	public static function get_pro_elements() {

		$elements = self::get_elements_list();

		$pro_elements = array();

		$all_elements = $elements['cat-1'];

		if ( count( $all_elements['elements'] ) ) {
			foreach ( $all_elements['elements'] as $elem ) {
				if ( isset( $elem['is_pro'] ) && ! isset( $elem['is_global'] ) ) {
					$elem['categories'] = '["premium-elements"]';
					array_push( $pro_elements, $elem );
				}
			}
		}

		return $pro_elements;
	}

	/**
	 * Get PA Free Elements.
	 * Return PA Widgets.
	 *
	 * @since 4.6.1
	 * @access public
	 *
	 * @return array
	 */
	public static function get_free_widgets_names() {

		$elements = self::get_elements_list()['cat-1']['elements'];

		$pa_elements = array();

		if ( count( $elements ) ) {
			foreach ( $elements as $elem ) {
				if ( ! isset( $elem['is_pro'] ) && ! isset( $elem['is_global'] ) && isset( $elem['name'] ) ) {
					array_push( $pa_elements, $elem['name'] );
				}
			}
		}

		return $pa_elements;
	}

	/**
	 * Get Info By Key.
	 *
	 * Returns elements by its key.
	 *
	 * @since 4.10.49
	 * @access public
	 *
	 * @param string $key element key.
	 *
	 * @return array
	 */
	public static function get_info_by_key( $key ) {

		$elements = self::get_elements_list()['cat-1']['elements'];

		$element = false;

		foreach ( $elements as $elem ) {

			if ( $key === $elem['name'] ) {
				$element = $elem;
				break;
			}
		}

		return $element;
	}

	/**
	 * Get Global Elements Switchers.
	 * Construct an associative array of addon_switcher => 'yes' pairs
	 * Example :
	 *      + array( 'premium_gradient_switcher' => yes').
	 *
	 * @since 4.6.1
	 * @access public
	 *
	 * @return array
	 */
	public static function get_global_elements_switchers() {

		$elements = self::get_elements_list()['cat-4'];

		$global_elems = array();

		if ( count( $elements['elements'] ) ) {
			foreach ( $elements['elements'] as $elem ) {
				if ( isset( $elem['is_pro'] ) && isset( $elem['is_global'] ) ) {
					$global_elems[ str_replace( '-', '_', $elem['key'] ) . '_switcher' ] = 'yes';
				}
			}
		}

		return $global_elems;
	}

	/**
	 * Get Default Integrations
	 *
	 * @since 3.20.9
	 * @access private
	 *
	 * @return $default_keys array default keys
	 */
	private static function get_default_integrations() {

		$settings = self::get_integrations_list();

		$default_keys = array_fill_keys( $settings, true );

		// Beta Tester should NOT be enabled by default.
		$default_keys['is-beta-tester'] = false;

		return $default_keys;
	}

	/**
	 * Get enabled widgets
	 *
	 * @since 3.20.9
	 * @access public
	 *
	 * @return array $enabled_keys enabled elements
	 */
	public static function get_enabled_elements() {

		$cache_key = 'pa_elements';
		$cached    = wp_cache_get( $cache_key, 'premium_addons' );

		if ( false !== $cached ) {
			self::$enabled_elements = $cached;
			return self::$enabled_elements;
		}

		// Check static property as fallback for multiple calls in same request.
		if ( null !== self::$enabled_elements ) {
			return self::$enabled_elements;
		}

		$defaults = self::get_default_keys();

		$enabled_keys = get_option( 'pa_save_settings', $defaults );

		foreach ( $defaults as $key => $value ) {

			if ( 'pa_mc_temp' !== $key && ! isset( $enabled_keys[ $key ] ) ) {
				$defaults[ $key ] = 0;
			} elseif ( 'pa_mc_temp' === $key && isset( $enabled_keys[ $key ] ) && $enabled_keys[ $key ] ) {
				$defaults[ $key ] = 1;
			}
		}

		self::$enabled_elements = $defaults;
		wp_cache_set( $cache_key, $defaults, 'premium_addons', HOUR_IN_SECONDS );

		return self::$enabled_elements;
	}

	/**
	 * Check Elementor By Key
	 *
	 * @since 4.10.52
	 * @access public
	 *
	 * @return string $key element key.
	 */
	public static function check_element_by_key( $key ) {

		if ( ! $key ) {
			return;
		}

		$settings = self::get_enabled_elements();

		if ( ! isset( $settings[ $key ] ) ) {
			return false;
		}

		return $settings[ $key ];
	}

	/**
	 * Check SVG Draw.
	 *
	 * @since 4.9.26
	 * @access public
	 *
	 * @param string $key element key.
	 *
	 * @return boolean $is_enabled is option enabled.
	 */
	public static function check_svg_draw( $key ) {

		$is_enabled = self::check_element_by_key( 'svg_' . $key );

		return $is_enabled;
	}

	/**
	 * Check If Premium Templates is enabled
	 *
	 * @since 3.6.0
	 * @access public
	 *
	 * @return boolean
	 */
	public static function check_premium_templates() {

		$settings = self::get_enabled_elements();

		if ( ! isset( $settings['premium-templates'] ) ) {
			return true;
		}

		$is_enabled = $settings['premium-templates'];

		return $is_enabled;
	}


	/**
	 * Check If Premium Duplicator is enabled
	 *
	 * @since 3.20.9
	 * @access public
	 *
	 * @return boolean
	 */
	public static function check_duplicator() {

		$settings = self::get_enabled_elements();

		if ( ! isset( $settings['premium-duplicator'] ) ) {
			return true;
		}

		$is_enabled = $settings['premium-duplicator'];

		return $is_enabled;
	}

	/**
	 * Check If Premium Duplicator is enabled
	 *
	 * @since 4.9.4
	 * @access public
	 *
	 * @return boolean
	 */
	public static function check_dynamic_assets() {

		$settings = self::get_enabled_elements();

		if ( ! isset( $settings['premium-assets-generator'] ) ) {
			return false;
		}

		$is_enabled = $settings['premium-assets-generator'];

		return $is_enabled;
	}

	/**
	 * Get Integrations Settings.
	 *
	 * Get plugin integrations settings.
	 *
	 * @since 3.20.9
	 * @access public
	 *
	 * @return array $settings integrations settings.
	 */
	public static function get_integrations_settings() {

		if ( null === self::$integrations_settings ) {

			$defaults = self::get_default_integrations();

			$enabled_keys = get_option( 'pa_maps_save_settings', $defaults );

			foreach ( $defaults as $key => $value ) {

				if ( isset( $enabled_keys[ $key ] ) ) {

					$defaults[ $key ] = $enabled_keys[ $key ];
				}
			}

			self::$integrations_settings = $defaults;

		}

		return self::$integrations_settings;
	}

	/**
	 * Run PA Rollback
	 *
	 * Trigger PA Rollback actions
	 *
	 * @since 4.2.5
	 * @access public
	 */
	public function run_pa_rollback() {

		check_admin_referer( 'premium_addons_rollback' );

		$plugin_slug = basename( PREMIUM_ADDONS_FILE, '.php' );

		$pa_rollback = new PA_Rollback(
			array(
				'version'     => PREMIUM_ADDONS_STABLE_VERSION,
				'plugin_name' => PREMIUM_ADDONS_BASENAME,
				'plugin_slug' => $plugin_slug,
				'package_url' => sprintf( 'https://downloads.wordpress.org/plugin/%s.%s.zip', $plugin_slug, PREMIUM_ADDONS_STABLE_VERSION ),
			)
		);

		$pa_rollback->run();

		wp_die(
			'',
			esc_html( __( 'Rollback to Previous Version', 'premium-addons-for-elementor' ) ),
			array(
				'response' => 200,
			)
		);
	}

	/**
	 * Disable unused widgets.
	 *
	 * @access public
	 * @since 4.5.8
	 */
	public function get_unused_widgets() {

		check_ajax_referer( 'pa-disable-unused', 'security' );

		if ( ! current_user_can( 'install_plugins' ) ) {
			wp_send_json_error();
		}

		$pa_elements = self::get_pa_elements_names();

		$used_widgets = self::get_used_widgets();

		$unused_widgets = array_diff( $pa_elements, array_keys( $used_widgets ) );

		wp_send_json_success( $unused_widgets );
	}

	/**
	 * Disables Elementor Custom Mini Cart Template.
	 *
	 * @access public
	 * @since 4.11.6
	 * @see ElementorPro\Modules\Woocommerce\Module [elementor-pro\modules\woocommerce\module.php].
	 */
	public function disable_elementor_mc_template() {

		check_ajax_referer( 'pa-settings-tab', 'security' );

		update_option( 'elementor_use_mini_cart_template', 'no' );

		wp_send_json_success( 'Elementor Mini Cart Template Disabled.' );
	}

	/**
	 * Clear Cached Assets.
	 *
	 * Deletes assets options from DB And
	 * deletes assets files from uploads/premium-addons-for-elementor via AJAX
	 * diretory.
	 *
	 * @access public
	 * @since 4.9.3
	 */
	public function clear_cached_assets() {

		check_ajax_referer( 'pa-generate-nonce', 'security' );

		$post_id = isset( $_POST['id'] ) ? sanitize_text_field( wp_unslash( $_POST['id'] ) ) : '';

		$this->clear_dynamic_assets_data( $post_id );

		wp_send_json_success( 'Cached Assets Cleared' );
	}

	/**
	 * Clear Dynamic Assets Data.
	 *
	 * Deletes assets options from DB And
	 * deletes assets files from uploads/premium-addons-for-elementor
	 * diretory.
	 *
	 * @access public
	 * @since 4.10.51
	 *
	 * @param string $id post ID.
	 */
	public function clear_dynamic_assets_data( $id = '' ) {

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( __( 'You are not allowed to do this action', 'premium-addons-for-elementor' ) );
		}

		if ( empty( $id ) ) {
			$this->delete_assets_options();
		}

		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			Plugin::$instance->files_manager->clear_cache();
		}

		// Purge All LS Cache
		do_action( 'litespeed_purge_all', 'Premium Addons for Elementor' );

		$this->delete_assets_files( $id );
	}

	/**
	 * Clear Cached Assets.
	 *
	 * Deletes assets options from DB And
	 * deletes assets files from uploads/premium-addons-for-elementor
	 * diretory.
	 *
	 * @access public
	 * @since 4.9.3
	 */
	public function clear_site_cursor_settings() {

		check_ajax_referer( 'pa-site-cursor-nonce', 'security' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( __( 'You are not allowed to do this action', 'premium-addons-for-elementor' ) );
		}

		delete_option( 'pa_site_custom_cursor' );

		wp_send_json_success( 'Site Cursor Settings Cleared' );
	}

	/**
	 * Delete Assets Options.
	 *
	 * @access public
	 * @since 4.9.3
	 */
	public function delete_assets_options() {

		global $wpdb;

		$query = $wpdb->prepare(
			"DELETE FROM $wpdb->options
			 WHERE (option_name LIKE %s OR option_name LIKE %s)
			 AND autoload = %s",
			'%pa_elements_%',
			'%pa_edit_%',
			'no'
		);

		$wpdb->query( $query ); // phpcs:ignore
	}

	/**
	 * Delete Assets Files.
	 *
	 * @access public
	 * @since 4.6.1
	 *
	 * @param string $id post id.
	 */
	public function delete_assets_files( $id ) {

		$path = PREMIUM_ASSETS_PATH;

		if ( ! is_dir( $path ) || ! file_exists( $path ) ) {
			return;
		}

		if ( empty( $id ) ) {
			foreach ( scandir( $path ) as $file ) {
				if ( '.' === $file || '..' === $file ) {
					continue;
				}

				wp_delete_file( Helper_Functions::get_safe_path( $path . DIRECTORY_SEPARATOR . $file ) );
			}
		} else {

			$id = Helper_Functions::generate_unique_id( 'pa_assets_' . $id );

			foreach ( glob( PREMIUM_ASSETS_PATH . '/*' . $id . '*' ) as $file ) {
				wp_delete_file( Helper_Functions::get_safe_path( $file ) );
			}
		}
	}

	/**
	 * Get PA widget names.
	 *
	 * @access public
	 * @since 4.5.8
	 *
	 * @return array
	 */
	public static function get_pa_elements_names() {

		$names = self::$elements_names;

		if ( null === $names ) {

			$names = array_map(
				function ( $item ) {
					return isset( $item['name'] ) ? $item['name'] : 'global';
				},
				self::get_elements_list()['cat-1']['elements']
			);

			$names = array_filter(
				$names,
				function ( $name ) {
					return 'global' !== $name;
				}
			);

		}

		return $names;
	}

	/**
	 * Get used widgets.
	 *
	 * @access public
	 * @since 4.5.8
	 *
	 * @return array
	 */
	public static function get_used_widgets() {

		$used_widgets = array();

		if ( class_exists( 'Elementor\Modules\Usage\Module' ) ) {

			$module = Module::instance();

			$module->recalc_usage();

			$elements = $module->get_formatted_usage( 'raw' );

			$pa_elements = self::get_pa_elements_names();

			if ( is_array( $elements ) || is_object( $elements ) ) {

				foreach ( $elements as $post_type => $data ) {

					foreach ( $data['elements'] as $element => $count ) {

						if ( in_array( $element, $pa_elements, true ) ) {

							if ( isset( $used_widgets[ $element ] ) ) {
								$used_widgets[ $element ] += $count;
							} else {
								$used_widgets[ $element ] = $count;
							}
						}
					}
				}
			}
		}

		return $used_widgets;
	}

	/**
	 * Subscribe Newsletter
	 *
	 * Adds an email to Premium Addons subscribers list
	 *
	 * @since 4.7.0
	 *
	 * @access public
	 */
	public function subscribe_newsletter() {

		check_ajax_referer( 'pa-settings-tab', 'security' );

		if ( ! self::check_user_can( 'manage_options' ) ) {
			wp_send_json_error();
		}

		$email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';

		$api_url = 'https://premiumaddons.com/wp-json/mailchimp/v2/add';

		$request = add_query_arg(
			array(
				'email' => $email,
			),
			$api_url
		);

		$response = wp_remote_get(
			$request,
			array(
				'timeout'   => 15,
				'sslverify' => true,
			)
		);

		$body = wp_remote_retrieve_body( $response );
		$body = json_decode( $body, true );

		wp_send_json_success( $body );
	}

	/**
	 * Get PA News
	 *
	 * Gets a list of the latest three blog posts
	 *
	 * @since 4.7.0
	 *
	 * @access public
	 */
	public function get_pa_news() {

		$posts = get_transient( 'pa_news' );

		if ( empty( $posts ) ) {

			$api_url = 'https://premiumaddons.com/wp-json/wp/v2/posts';

			$request = add_query_arg(
				array(
					'per_page'   => 3,
					'categories' => 32,
				),
				$api_url
			);

			$response = wp_remote_get(
				$request,
				array(
					'timeout'   => 5,
					'sslverify' => true,
				)
			);

			if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
				set_transient( 'pa_news', true, WEEK_IN_SECONDS );
				return;
			}

			$body  = wp_remote_retrieve_body( $response );
			$posts = json_decode( $body, true );

			set_transient( 'pa_news', $posts, WEEK_IN_SECONDS );

		}

		return $posts;
	}

	/**
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
