<?php
/**
 * CartFlows Admin.
 *
 * @package CartFlows
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Class Cartflows_Admin.
 */
class Cartflows_Admin {

	/**
	 * Instance
	 *
	 * @access private
	 * @var object Class object.
	 * @since 1.0.0
	 */
	private static $instance;

	/**
	 * Initiator
	 *
	 * @since 1.0.0
	 * @return object initialized object of class.
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor
	 */
	public function __construct() {

		$this->init_hooks();
	}

	/**
	 * Init Hooks.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function init_hooks() {

		if ( ! is_admin() ) {
			return;
		}

		/* Add lite version class to body */
		add_action( 'admin_body_class', array( $this, 'add_admin_body_class' ) );

		add_filter( 'plugin_action_links_' . CARTFLOWS_BASE, array( $this, 'add_action_links' ) );

		add_action( 'admin_init', array( $this, 'flush_rules_after_save_permalinks' ) );

		add_filter( 'post_row_actions', array( $this, 'remove_flow_actions' ), 99, 2 );

		add_action( 'wp_dashboard_setup', array( $this, 'dashboard_widget' ) );

		add_action( 'init', array( $this, 'run_scheduled_docs_job' ) );
		add_action( 'cartflows_update_knowledge_base_data', array( $this, 'cartflows_update_knowledge_base_data' ) );
		add_action( 'admin_post_cartflows_rollback', array( $this, 'post_cartflows_rollback' ) );

		add_filter( 'bsf_core_stats', array( $this, 'get_specific_stats' ) );

		$this->do_not_cache_admin_pages_actions();
	}

	/**
	 * Do not cache admin pages actions.
	 *
	 * @since 1.6.13
	 * @return void
	 */
	public function do_not_cache_admin_pages_actions() {

		add_action( 'admin_init', array( $this, 'add_admin_cache_restrict_const' ) );
		add_action( 'admin_head', array( $this, 'add_admin_cache_restrict_meta' ) );
	}

	/**
	 * Run scheduled job for CartFLows knowledge base data.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function run_scheduled_docs_job() {
		if ( false === as_next_scheduled_action( 'cartflows_update_knowledge_base_data' ) && ! wp_installing() ) {
			as_schedule_recurring_action( time(), WEEK_IN_SECONDS, 'cartflows_update_knowledge_base_data' );
		}
	}

	/**
	 * Cartflows's REST knowledge base data.
	 *
	 * @since 2.0.0
	 * @return mixed
	 */
	public static function cartflows_update_knowledge_base_data() {
		$docs_json = json_decode( wp_remote_retrieve_body( wp_remote_get( 'https://cartflows.com//wp-json/powerful-docs/v1/get-docs' ) ) );
		Cartflows_Helper::update_admin_settings_option( 'cartflows_docs_data', $docs_json );
	}

	/**
	 * Init dashboard widgets.
	 */
	public function dashboard_widget() {

		if ( current_user_can( 'manage_options' ) && '1' === get_option( 'wcf_setup_skipped', false ) && '1' !== get_option( 'wcf_setup_complete', false ) ) {
			wp_add_dashboard_widget(
				'cartFlows_setup_dashboard_widget',
				__( 'CartFlows Setup', 'cartflows' ),
				array( $this, 'status_widget' )
			);
		}
	}

	/**
	 * Show status widget.
	 */
	public function status_widget() {

		$admin_url = admin_url( 'index.php' ) . '?page=cartflow-setup&';

		$steps = array(
			'welcome'         => $admin_url . 'step=welcome',
			'page-builder'    => $admin_url . 'step=page-builder',
			'plugin-install'  => $admin_url . 'step=plugin-install',
			'global-checkout' => $admin_url . 'step=global-checkout',
			'optin'           => $admin_url . 'step=optin',
			'ready'           => $admin_url . 'step=ready',
		);

		$exit_step = get_option( 'wcf_exit_setup_step', 'welcome' );

		$incompleted_steps = array_search( $exit_step, array_keys( $steps ), true );
		$remianing_steps   = array_slice( $steps, 0, $incompleted_steps );

		$completed_tasks_count = count( $remianing_steps ) + 1;
		$tasks_count           = count( $steps );
		$button_link           = ! empty( $exit_step ) ? $steps[ $exit_step ] : '';

		$progress_percentage = ( $completed_tasks_count / $tasks_count ) * 100;
		$circle_r            = 6.5;
		$circle_dashoffset   = ( ( 100 - $progress_percentage ) / 100 ) * ( pi() * ( $circle_r * 2 ) );

		wp_enqueue_style( 'cartflows-dashboard-widget', CARTFLOWS_URL . 'admin/assets/css/admin-widget.css', array(), CARTFLOWS_VER );

		?>

		<div class="cartflows-dashboard-widget-finish-setup">
			<span class='progress-wrapper'>
				<svg class="circle-progress" width="17" height="17" version="1.1" xmlns="http://www.w3.org/2000/svg">
				<circle r="6.5" cx="10" cy="10" fill="transparent" stroke-dasharray="40.859" stroke-dashoffset="0"></circle>
				<circle class="bar" r="6.5" cx="190" cy="10" fill="transparent" stroke-dasharray="40.859" stroke-dashoffset="<?php echo esc_attr( $circle_dashoffset ); ?>" transform='rotate(-90 100 100)'></circle>
				</svg>
				<span><?php echo esc_html_e( 'Step', 'cartflows' ); ?> <?php echo esc_html( $completed_tasks_count ); ?> <?php echo esc_html_e( 'of', 'cartflows' ); ?> <?php echo esc_html( $tasks_count ); ?></span>
			</span>

			<div class="description">
				<div class="wcf-left-column">
					<p>
						<?php echo esc_html_e( 'You\'re almost there! Once you complete CartFlows setup you can start receiving orders from flows.', 'cartflows' ); ?>
					</p>
					<a href='<?php echo esc_url( $button_link ); ?>' class='button button-primary'><?php echo esc_html_e( 'Complete Setup', 'cartflows' ); ?></a>
				</div>
				<div class="wcf-right-column">
					<img src="<?php echo esc_url( CARTFLOWS_URL . 'admin/assets/images/cartflows-home-widget.svg' ); ?>" />
				</div>
			</div>
		</div>
		<?php

	}

	/**
	 * Add the clone link to action list for flows row actions
	 *
	 * @param array  $actions Actions array.
	 * @param object $post Post object.
	 *
	 * @return array
	 */
	public function remove_flow_actions( $actions, $post ) {

		if ( current_user_can( 'edit_posts' ) && isset( $post ) && CARTFLOWS_FLOW_POST_TYPE === $post->post_type ) {

			if ( isset( $actions['duplicate'] ) ) { // Duplicate page plugin remove.
				unset( $actions['duplicate'] );
			}

			if ( isset( $actions['edit_as_new_draft'] ) ) { // Duplicate post plugin remove.
				unset( $actions['edit_as_new_draft'] );
			}
		}

		return $actions;
	}

	/**
	 *  After save of permalinks.
	 */
	public static function flush_rules_after_save_permalinks() {

		$has_saved_permalinks = get_option( 'cartflows_permalink_refresh' );
		if ( $has_saved_permalinks ) {
			// Required to flush rules when permalink changes.
			flush_rewrite_rules(); //phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.flush_rewrite_rules_flush_rewrite_rules
			delete_option( 'cartflows_permalink_refresh' );
		}

	}

	/**
	 * Show action on plugin page.
	 *
	 * @param  array $links links.
	 * @return array
	 */
	public function add_action_links( $links ) {

		$mylinks = array(
			'<a target="_blank" href="' . esc_url( 'https://cartflows.com/docs/?utm_source=plugin-page&utm_medium=free-cartflows&utm_campaign=go-pro' ) . '">' . __( 'Docs', 'cartflows' ) . '</a>',
		);

		if ( current_user_can( 'cartflows_manage_settings' ) ) {

			$default_url = add_query_arg(
				array(
					'page'     => CARTFLOWS_SLUG,
					'settings' => true,
				),
				admin_url( 'admin.php' )
			);

			array_unshift( $mylinks, '<a href="' . esc_url( $default_url ) . '">' . __( 'Settings', 'cartflows' ) . '</a>' );
		}

		if ( ! _is_cartflows_pro() ) {
			array_push( $mylinks, '<a style="color: #39b54a; font-weight: 700;" target="_blank" href="' . esc_url( 'https://cartflows.com/pricing/?utm_source=plugin-page&utm_medium=free-cartflows&utm_campaign=go-pro' ) . '"> ' . __( 'Get CartFlows Pro', 'cartflows' ) . ' </a>' );
		}

		return array_merge( $links, $mylinks );
	}

	/**
	 * Check is flow admin.
	 *
	 * @since 1.0.0
	 * @return boolean
	 */
	public static function is_flow_edit_admin() {

		$current_screen = get_current_screen();

		if (
			is_object( $current_screen ) &&
			isset( $current_screen->post_type ) &&
			( CARTFLOWS_FLOW_POST_TYPE === $current_screen->post_type ) &&
			isset( $current_screen->base ) &&
			( 'post' === $current_screen->base )
		) {
			return true;
		}
		return false;
	}

	/**
	 * Admin body classes.
	 *
	 * Body classes to be added to <body> tag in admin page
	 *
	 * @param String $classes body classes returned from the filter.
	 * @return String body classes to be added to <body> tag in admin page
	 */
	public function add_admin_body_class( $classes ) {

		$classes .= ' cartflows-' . CARTFLOWS_VER;

		if ( isset( $_GET['action'] ) && in_array( sanitize_text_field( wp_unslash( $_GET['action'] ) ), array( 'wcf-log', 'wcf-license' ) ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$classes .= ' wcf-debug-page ';
		}

		return $classes;
	}

	/**
	 * Check allowed screen for notices.
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function allowed_screen_for_notices() {

		$screen          = get_current_screen();
		$screen_id       = $screen ? $screen->id : '';
		$allowed_screens = array(
			'toplevel_page_cartflows',
			'edit-cartflows_flow',
		);

		if ( in_array( $screen_id, $allowed_screens, true ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Function to add the meta and headers for not to cache the CartFlows wp-admin page.
	 *
	 * @return void
	 */
	public function add_admin_cache_restrict_meta() {

		if ( ! $this->allowed_screen_for_notices() ) {
			return;
		}

		echo '<!-- Added by CartFlows -->';
		echo '<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" /> <meta http-equiv="Pragma" content="no-cache" /> <meta http-equiv="Expires" content="0" />';
		echo '<!-- Added by CartFlows -->';
	}

	/**
	 * Function to add the Do Not Cache Constants on the CartFlows admin pages.
	 *
	 * @return void
	 */
	public function add_admin_cache_restrict_const() {
		// Ignoring nonce verification as using SuperGlobal variables on WordPress hooks.
		if ( isset( $_GET['page'] ) && ( 'cartflows' === sanitize_text_field( $_GET['page'] ) || false !== strpos( sanitize_text_field( $_GET['page'] ), 'cartflows_' ) ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended
			wcf()->utils->get_cache_headers();
		}
	}

	/**
	 * CartFlows version rollback.
	 *
	 * Rollback to previous CartFlows version.
	 *
	 * Fired by `admin_post_cartflows_rollback` action.
	 *
	 * @since 2.1.6
	 * @access public
	 * @return void
	 */
	public function post_cartflows_rollback() {

		if ( ! current_user_can( 'install_plugins' ) ) {
			wp_die(
				esc_html__( 'You do not have permission to access this page.', 'cartflows' ),
				esc_html__( 'Rollback to Previous Version', 'cartflows' ),
				array(
					'response' => 200,
				)
			);
		}

		check_admin_referer( 'cartflows_rollback' );

		$rollback_versions = \Cartflows_Helper::get_rollback_versions();
		$update_version    = isset( $_GET['version'] ) ? sanitize_text_field( $_GET['version'] ) : '';

		if ( empty( $update_version ) || ! in_array( $update_version, $rollback_versions, true ) ) {
			wp_die( esc_html__( 'Error occurred, The version selected is invalid. Try selecting different version.', 'cartflows' ) );
		}

		$plugin_slug = basename( CARTFLOWS_FILE, '.php' );

		$rollback = new Cartflows_Rollback(
			array(
				'version'     => $update_version,
				'plugin_name' => CARTFLOWS_BASE,
				'plugin_slug' => $plugin_slug,
				'package_url' => sprintf( 'https://downloads.wordpress.org/plugin/%s.%s.zip', $plugin_slug, $update_version ),
			)
		);

		$rollback->run();

		wp_die(
			'',
			esc_html__( 'Rollback to Previous Version', 'cartflows' ),
			array(
				'response' => 200,
			)
		);
	}

	/**
	 * Pass addon specific stats to analytics.
	 *
	 * @since 2.1.8
	 * @param array $stats_data Default stats array.
	 * @return array $stats_data Default stats with addon specific stats array.
	 */
	public function get_specific_stats( $stats_data ) {

		$step_specific_features = array();

		if ( apply_filters( 'cartflows_enable_non_sensitive_data_tracking', get_option( 'cf_analytics_optin', false ) ) ) {

			// Combine all features to make a full list.
			$theme_data = $this->get_active_theme();

			// Prepare default data to be tracked.
			$stats_data['plugin_data']['cartflows']                   = $this->get_default_stats( $theme_data );
			$stats_data['plugin_data']['cartflows']['numeric_values'] = $this->get_numeric_data_stats();
			$stats_data['plugin_data']['cartflows']['boolean_values'] = $this->get_boolean_data_stats( $theme_data );

			// Filter to add more options if any.
			$stats_data = apply_filters( 'cartflows_get_specific_stats', $stats_data );
		}

		return $stats_data;

	}

	/**
	 * Retrieves default statistics for CartFlows.
	 *
	 * This function collects and returns default statistics for CartFlows, including the website domain, site language, CartFlows version, WooCommerce version, default page builder, active theme, active gateways, and social tracking features.
	 *
	 * @since 2.1.8
	 * @param array $theme_data Array containing active theme information.
	 * @return array $default_data An array of default statistics for CartFlows.
	 */
	public function get_default_stats( $theme_data ) {
		$bsf_internal_referer = get_option( 'bsf_product_referers', array() );
		$store_location       = '';
		$woo_version          = '';

		if ( wcf()->is_woo_active ) {
			$store_location = wc_get_base_location();
			$woo_version    = defined( 'WC_VERSION' ) ? WC_VERSION : '';
		}

		$default_data = array(
			'website-domain'             => str_ireplace( array( 'http://', 'https://' ), '', home_url() ),
			'site_language'              => get_locale(),
			'cartflows-lite-version'     => CARTFLOWS_VER,
			'cartflows-pro-version'      => _is_cartflows_pro() ? CARTFLOWS_PRO_VER : '',
			'woocommerce-version'        => $woo_version,
			'default-page-builder'       => Cartflows_Helper::get_common_setting( 'default_page_builder' ),
			'active-theme'               => $theme_data['parent_theme'],
			'active-gateways'            => wcf()->is_woo_active ? $this->get_active_gateways() : '',
			'social-tracking'            => $this->get_all_social_features_tracking_data(),
			'store-country'              => ! empty( $store_location['country'] ) ? $store_location['country'] : '',
			'documentation-search-terms' => get_option( 'cartflows_kb_searches', array() ),
			'internal_referer'           => ! empty( $bsf_internal_referer['cartflows'] ) ? $bsf_internal_referer['cartflows'] : '',
			// NPS Survey status for analytics tracking (first display, dismiss, submit, etc.).
			'nps-survey-status'          => get_option( 'nps-survey-cartflows', array() ),
			// Add KPI for CartFlows Pro license key presence.
			'pro_license_key_exists'     => $this->check_pro_license_key_exists() ? true : false,
		);

		return $default_data;
	}

	/**
	 * Retrieves numeric data statistics for CartFlows.
	 *
	 * This function collects and returns numeric data statistics for CartFlows, including the total number of flows, and the count of each step type (optin, landing, checkout, upsell, downsell, thankyou).
	 *
	 * @since 2.1.8
	 * @return array An array of numeric data statistics for CartFlows.
	 */
	public function get_numeric_data_stats() {

		// Get the total numbers of each steps.
		$steps_counts = $this->get_all_steps_count();

		// Get the total flows where the Instant Checkout feature is enabled.
		$flows_with_instant_checkout = $this->get_funnels_with_instant_layout();

		// Get the total of optin and checkout steps where the custom field editor is enabled.
		$custom_fields_data = $this->get_custom_fields_enabled_data();

		// Retrieve the funnel creation method statistics from the database.
		$funnel_building_behavior = get_option(
			'cartflows_funnel_creation_method',
			array(
				'scratch'             => 0,
				'ready_made_template' => 0,
			)
		);

		// Return the prepared data.
		return array(
			'total_flows'            => wp_count_posts( CARTFLOWS_FLOW_POST_TYPE )->publish,
			'total_ic_funnels'       => strval( $flows_with_instant_checkout ),
			'optin_step_count'       => strval( $steps_counts['optin'] ),          // Total count of 'optin' steps created.
			'landing_step_count'     => strval( $steps_counts['landing'] ),        // Total count of 'landing' steps created.
			'checkout_step_count'    => strval( $steps_counts['checkout'] ),       // Total count of 'checkout' steps created.
			'upsell_step_count'      => strval( $steps_counts['upsell'] ),         // Total count of 'upsell' steps created.
			'downsell_step_count'    => strval( $steps_counts['downsell'] ),       // Total count of 'downsell' steps created.
			'thankyou_step_count'    => strval( $steps_counts['thankyou'] ),       // Total count of 'thank' you steps created.
			'optin_custom_fields'    => strval( $custom_fields_data['optin'] ),    // Total optin steps with custom field editor is enabled.
			'checkout_custom_fields' => strval( $custom_fields_data['checkout'] ), // Total checkout steps with custom field editor is enabled.
			'funnels_from_scratch'   => strval( $funnel_building_behavior['scratch'] ), // Number of funnels created from scratch.
			'funnels_from_template'  => strval( $funnel_building_behavior['ready_made_template'] ),        // Number of funnels created from ready-made templates.
		);
	}

	/**
	 * Retrive the active theme name
	 *
	 * @since 2.1.8
	 * @return array Array of active parent or child theme.
	 */
	public function get_active_theme() {

		$theme             = wp_get_theme();
		$parent_theme_name = '';
		$child_theme_name  = '';

		if ( isset( $theme->parent_theme ) && ! empty( $theme->parent_theme ) ) {
			$parent_theme_name = $theme->parent_theme;
			$child_theme_name  = $theme->name ? $theme->name : '';
		} else {
			$parent_theme_name = $theme->name;
		}

		return array(
			'parent_theme' => ! empty( $child_theme_name ) ? $child_theme_name : $parent_theme_name,
			'child_theme'  => ! empty( $child_theme_name ) ? true : false,
		);
	}

	/**
	 * Retrieve the count of all steps in the system.
	 *
	 * This function queries the database to count the number of steps of each type (optin, landing, checkout, upsell, downsell, thankyou).
	 * It returns an array with the count of each step type.
	 *
	 * @since x.x.x
	 * @return array An array containing the count of each step type.
	 */
	public function get_all_steps_count() {
		
		// Default data.
		$default_step_counts = array(
			'optin'    => 0,
			'landing'  => 0,
			'checkout' => 0,
			'upsell'   => 0,
			'downsell' => 0,
			'thankyou' => 0,
		);

		global $wpdb;

		// Prepare the database query to make get the step counts.
		//phpcs:disable WordPress.DB.DirectDatabaseQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		// Reason: This is a direct database query which fetch only the number of steps.
		$step_counts = $wpdb->get_results(
			$wpdb->prepare( 
				"SELECT JSON_OBJECTAGG(meta_value, count) as counts
				FROM (
					SELECT pm.meta_value, COUNT(*) as count
					FROM $wpdb->posts p
					INNER JOIN $wpdb->postmeta pm ON p.ID = pm.post_id
					WHERE p.post_type = %s 
					AND p.post_status = %s 
					AND pm.meta_key = %s
					AND pm.meta_value IN ('optin', 'landing', 'checkout', 'upsell', 'downsell', 'thankyou')
					GROUP BY pm.meta_value
				) as subquery",
				CARTFLOWS_STEP_POST_TYPE,
				'publish',
				'wcf-step-type'
			),
			ARRAY_A
		);
		//phpcs:enable WordPress.DB.DirectDatabaseQuery, WordPress.DB.DirectDatabaseQuery.NoCaching

		// Merge the updated counts with the defaults.
		$all_step_count = wp_parse_args( json_decode( $step_counts[0]['counts'] ), $default_step_counts );
		return $all_step_count;

	}

	/**
	 * Retrieve the count of funnels with instant layout enabled.
	 *
	 * This function queries the database to count the number of funnels that have the instant layout style enabled.
	 * It returns the count of such funnels.
	 *
	 * @since x.x.x
	 * @return int $posts_count The count of funnels with instant layout enabled.
	 */
	public function get_funnels_with_instant_layout() {
		$posts_count = 0;

		$args = array(
			'post_type'      => CARTFLOWS_FLOW_POST_TYPE,
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'meta_query'     => array(  //phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query -- Meta query required as we need to fetch count of instant layout funnels.
				array(
					'key'     => 'instant-layout-style',
					'value'   => 'yes',
					'compare' => 'LIKE',
				),
			), 
		);

		$query       = new WP_Query( $args );
		$posts_count = isset( $query->found_posts ) ? $query->found_posts : 0;

		wp_reset_postdata();

		return $posts_count;
	}

	/**
	 * Retrieve the count of steps with custom fields enabled.
	 *
	 * This function queries the database to count the number of steps (optin and checkout) that have custom fields enabled.
	 * It returns the count of such steps.
	 *
	 * @since x.x.x
	 * @return array $all_step_count The count of steps with custom fields enabled, categorized by step type.
	 */
	public function get_custom_fields_enabled_data() {

		// Default data.
		$default_step_counts = array(
			'optin'    => 0,
			'checkout' => 0,
		);

		global $wpdb;

		// Prepare the database query to make get the step counts.
		//phpcs:disable WordPress.DB.DirectDatabaseQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		// Reason: This is a direct database query which fetch only the number of steps where the custom field editor is enabled.
		$step_counts = $wpdb->get_results(
			$wpdb->prepare( 
				"SELECT JSON_OBJECTAGG(subquery.step_type, subquery.count) AS counts
				FROM ( SELECT step.meta_value AS step_type, 
						COUNT(DISTINCT p.ID) AS count
					FROM $wpdb->posts p
					INNER JOIN $wpdb->postmeta step 
						ON p.ID = step.post_id 
						AND step.meta_key = %s 
						AND step.meta_value IN ('optin', 'checkout')
					INNER JOIN $wpdb->postmeta custom 
						ON p.ID = custom.post_id 
						AND (
							(step.meta_value = 'optin' AND custom.meta_key = %s) 
							OR 
							(step.meta_value = 'checkout' AND custom.meta_key = %s)
						) 
						AND custom.meta_value = %s
					WHERE p.post_type = %s 
					AND p.post_status = %s
					GROUP BY step.meta_value
				) AS subquery",
				'wcf-step-type',
				'wcf-optin-enable-custom-fields',
				'wcf-custom-checkout-fields',
				'yes',
				CARTFLOWS_STEP_POST_TYPE,
				'publish'
			),
			ARRAY_A
		);
		//phpcs:enable WordPress.DB.DirectDatabaseQuery, WordPress.DB.DirectDatabaseQuery.NoCaching

		// Merge the updated counts with the defaults.
		$all_step_count = wp_parse_args( json_decode( $step_counts[0]['counts'] ), $default_step_counts );
		return $all_step_count;

	}

	/**
	 * Retrive the list of active gateways
	 *
	 * @since 2.1.8
	 * @return array Array of active payment gateways.
	 */
	public function get_active_gateways() {

		if ( ! wcf()->is_woo_active ) {
			return '';
		}

		$gateways         = WC()->payment_gateways->get_available_payment_gateways();
		$enabled_gateways = array();

		if ( is_array( $gateways ) ) {
			foreach ( $gateways as $gateway ) {

				if ( 'yes' === $gateway->enabled ) {
					$gateway_key                      = strtolower( str_replace( array( ' ', '(', ')' ), '_', $gateway->get_title() ) );
					$enabled_gateways[ $gateway_key ] = $gateway->get_title();
				}
			}
		}

		return $enabled_gateways;

	}

	/**
	 * Retrieve all social tracking data.
	 *
	 * This function retrieves and returns all social tracking data including Facebook, Google Analytics, TikTok, Pinterest, Google Ads, and Snapchat settings.
	 *
	 * @since 2.1.8
	 * @return array Array of all social tracking data.
	 */
	public function get_all_social_features_tracking_data() {

		$fb_setting         = Cartflows_Helper::get_facebook_settings();
		$ga_setting         = Cartflows_Helper::get_google_analytics_settings();
		$tik_pixel_settings = Cartflows_Helper::get_tiktok_settings();
		$pinterest_settings = Cartflows_Helper::get_pinterest_settings();
		$gads_settings      = Cartflows_Helper::get_google_ads_settings();
		$snapchat_settings  = Cartflows_Helper::get_snapchat_settings();

		// remove the IDs from the array.
		unset( $fb_setting['facebook_pixel_id'] );
		unset( $ga_setting['google_analytics_id'] );
		unset( $tik_pixel_settings['tiktok_pixel_id'] );
		unset( $pinterest_settings['pinterest_tag_id'] );
		unset( $gads_settings['google_ads_id'] );
		unset( $snapchat_settings['snapchat_pixel_id'] );

		return array(
			'fb-tracking'        => $fb_setting,
			'ga-tracking'        => $ga_setting,
			'tik-pixel-settings' => $tik_pixel_settings,
			'pinterest-settings' => $pinterest_settings,
			'gads-settings'      => $gads_settings,
			'snapchat-settings'  => $snapchat_settings,
		);

	}
	/**
	 * Retrieve all global features tracking data.
	 *
	 * This function retrieves and returns all global features tracking data including cartflows stats report emails, plugin data deletion, store checkout settings, override global checkout, disallow indexing, PayPal reference transactions, and pre-checkout offer settings.
	 *
	 * @since 2.1.8
	 * @param array $theme_data Array containing active theme information.
	 * @return array Array of all global features tracking data.
	 */
	public function get_boolean_data_stats( $theme_data ) {

		$common_settings = Cartflows_Helper::get_common_settings();

		return array(
			'override-global-checkout'      => ! empty( $common_settings['override_global_checkout'] ) && 'enable' === $common_settings['override_global_checkout'] ? true : false,
			'disallow-indexing'             => ! empty( $common_settings['disallow_indexing'] ) && 'enable' === $common_settings['disallow_indexing'] ? true : false,
			'paypal-reference-transactions' => ! empty( $common_settings['paypal_reference_transactions'] ) && 'enable' === $common_settings['paypal_reference_transactions'],
			'cartflows-stats-report-emails' => 'enable' === get_option( 'cartflows_stats_report_emails', 'enable' ),
			'cartflows-delete-plugin-data'  => 'enable' === get_option( 'cartflows_delete_plugin_data' ),
			'pre-checkout-offer'            => 'enable' === get_option( 'cartflows_stats_report_emails', 'enable' ),
			'store-checkout-set'            => ! empty( intval( Cartflows_Helper::get_global_setting( '_cartflows_store_checkout' ) ) ),
			'is-child-theme'                => $theme_data['child_theme'],
			'suretriggers_active'           => is_plugin_active( 'suretriggers/suretriggers.php' ),
		);

	}

	/**
	 * Check if CartFlows Pro license key exists.
	 *
	 * @return bool
	 */
	private function check_pro_license_key_exists() {
		if ( _is_cartflows_pro() && class_exists( 'CartFlows_Pro_Licence' ) ) {
			$license_data = get_option( 'wc_am_client_' . \CartFlows_Pro_Licence::get_instance()->product_id . '_api_key', array() );
			return ! empty( $license_data['api_key'] );
		}
		return false;
	}
}

Cartflows_Admin::get_instance();
