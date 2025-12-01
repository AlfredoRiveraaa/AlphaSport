<?php
/**
 * CartFlows Admin Notices.
 *
 * @package CartFlows
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Class Cartflows_Admin_Notices.
 */
class Cartflows_Admin_Notices {

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
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Add the notices script.
		add_action( 'admin_enqueue_scripts', array( $this, 'notices_scripts' ) );

		// Group the admin notices actions.
		$this->register_admin_notices();

		// Group the ajax action callbacks.
		$this->register_ajax_callbacks();
	}

	/**
	 * Registers admin notices for CartFlows.
	 *
	 * Hooks the methods responsible for displaying admin and NPS notices
	 * to appropriate WordPress admin actions.
	 *
	 * @since 2.1.17
	 * @return void
	 */
	public function register_admin_notices() {
		add_action( 'admin_head', array( $this, 'show_admin_notices' ) );

		add_action( 'admin_head', array( $this, 'show_sale_notice_on_woocommerce_pages' ), 20 );

		add_action( 'admin_footer', array( $this, 'show_nps_notice' ), 999 );
	}

	/**
	 * Registers AJAX callbacks for various CartFlows admin notices.
	 *
	 * This method hooks AJAX actions to their corresponding handler functions,
	 * allowing notices (such as Gutenberg, weekly report email, and custom offer notices)
	 * to be dismissed or acknowledged via AJAX requests in the WordPress admin area.
	 *
	 * @since 2.1.17
	 * @return void
	 */
	public function register_ajax_callbacks() {
		add_action( 'wp_ajax_cartflows_ignore_gutenberg_notice', array( $this, 'ignore_gb_notice' ) );
		add_action( 'wp_ajax_cartflows_disable_weekly_report_email_notice', array( $this, 'disable_weekly_report_email_notice' ) );
		add_action( 'wp_ajax_cartflows_dismiss_custom_offer_notice', array( $this, 'dismiss_custom_offer_notice' ) );
		// Register AJAX callback for dismissing the sale notice.
		add_action( 'wp_ajax_cartflows_dismiss_sale_notice', array( $this, 'dismiss_sale_notice' ) );
	}
	
	/**
	 * Dismiss sale notice via AJAX.
	 *
	 * @return void
	 */
	public function dismiss_sale_notice() {
		// Check if the current user has permission to manage options.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'You do not have permission to perform this action.', 'cartflows' ),
					'code'    => 'no_permission',
				) 
			);
		}

		// Validate the nonce.
		$nonce_valid = isset( $_POST['security'] ) && check_ajax_referer( 'cartflows-dismiss-sale-notice', 'security', false );
		if ( ! $nonce_valid ) {
			wp_send_json_error(
				array(
					'message' => __( 'Nonce verification failed. Please reload the page and try again.', 'cartflows' ),
					'code'    => 'nonce_failed',
				) 
			);
		}

		// Update the option to mark the sale notice as dismissed.
		$updated = update_option( 'cartflows_show_sale_notice', 'no' );
		if ( false === $updated ) {
			wp_send_json_error(
				array(
					'message' => __( 'Could not update option. Please try again.', 'cartflows' ),
					'code'    => 'option_update_failed',
				) 
			);
		}

		// Send success response.
		wp_send_json_success(
			array(
				'message' => __( 'Sale notice dismissed successfully.', 'cartflows' ),
			) 
		);
	}
	
	/**
	 * Show the weekly email Notice
	 *
	 * @return void
	 */
	public function show_weekly_report_email_settings_notice() {

		if ( ! $this->allowed_screen_for_notices() ) {
			return;
		}

		$is_show_notice = get_option( 'cartflows_show_weekly_report_email_notice', 'no' );

		if ( 'yes' === $is_show_notice && current_user_can( 'manage_options' ) ) {

			$setting_url = admin_url( 'admin.php?page=cartflows&path=settings#other_settings' );

			/* translators: %1$s Software Title, %2$s Plugin, %3$s Anchor opening tag, %4$s Anchor closing tag, %5$s Software Title. */
			$message = sprintf( __( '%1$sCartFlows:%2$s We just introduced an awesome new feature, weekly store revenue reports via email. Now you can see how many revenue we are generating for your store each week, without having to log into your website. You can set the email address for these email from %3$shere.%4$s', 'cartflows' ), '<strong>', '</strong>', '<a class="wcf-redirect-to-settings" target="_blank" href=" ' . esc_url( $setting_url ) . ' ">', '</a>' );
			$output  = '<div class="wcf-notice weekly-report-email-notice wcf-dismissible-notice notice notice-info is-dismissible">';
			$output .= '<p>' . $message . '</p>';
			$output .= '</div>';

			echo wp_kses_post( $output );
		}

	}

	/**
	 * Disable the weekly email Notice
	 *
	 * @return void
	 */
	public function disable_weekly_report_email_notice() {

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		check_ajax_referer( 'cartflows-disable-weekly-report-email-notice', 'security' );
		delete_option( 'cartflows_show_weekly_report_email_notice' );
		wp_send_json_success();
	}

	/**
	 *  After save of permalinks.
	 */
	public function notices_scripts() {

		if ( ! $this->allowed_screen_for_notices() || ! current_user_can( 'cartflows_manage_flows_steps' ) ) {
			return;
		}

		wp_enqueue_style( 'cartflows-custom-notices', CARTFLOWS_URL . 'admin/assets/css/notices.css', array(), CARTFLOWS_VER );

		wp_enqueue_script( 'cartflows-notices', CARTFLOWS_URL . 'admin/assets/js/ui-notice.js', array( 'jquery' ), CARTFLOWS_VER, true );

		$localize_vars = array(
			'ignore_gb_notice'                   => wp_create_nonce( 'cartflows-ignore-gutenberg-notice' ),
			'dismiss_weekly_report_email_notice' => wp_create_nonce( 'cartflows-disable-weekly-report-email-notice' ),
			'dismiss_custom_offer_notice'        => wp_create_nonce( 'cartflows-dismiss-custom-offer-notice' ),
			'dismiss_sale_notice_nonce'          => wp_create_nonce( 'cartflows-dismiss-sale-notice' ),
		);

		wp_localize_script( 'cartflows-notices', 'cartflows_notices', $localize_vars );
	}

	/**
	 *  After save of permalinks.
	 */
	public function show_admin_notices() {

		if ( ! $this->allowed_screen_for_notices() || ! current_user_can( 'cartflows_manage_flows_steps' ) ) {
			return;
		}

		global  $wp_version;

		if ( version_compare( $wp_version, '5.0', '>=' ) && is_plugin_active( 'gutenberg/gutenberg.php' ) ) {
			add_action( 'admin_notices', array( $this, 'gutenberg_plugin_deactivate_notice' ) );
		}

		add_action( 'admin_notices', array( $this, 'show_weekly_report_email_settings_notice' ) );
		add_action( 'admin_notices', array( $this, 'show_custom_offer_notice' ) );

		$image_path = esc_url( CARTFLOWS_URL . 'assets/images/cartflows-logo-small.jpg' );
		Astra_Notices::add_notice(
			array(
				'id'                   => 'cartflows-5-start-notice',
				'type'                 => 'info',
				'class'                => 'cartflows-5-star',
				'show_if'              => true,
				/* translators: %1$s white label plugin name and %2$s deactivation link */
				'message'              => sprintf(
					'<div class="notice-image" style="display: flex;">
                        <img src="%1$s" class="custom-logo" alt="CartFlows Icon" itemprop="logo" style="max-width: 90px; border-radius: 50px;"></div>
                        <div class="notice-content">
                            <div class="notice-heading">
                                %2$s
                            </div>
                            <div class="notice-description">
								%3$s
							</div>
                            <div class="astra-review-notice-container">
                                <a href="%4$s" class="astra-notice-close astra-review-notice button-primary" target="_blank">
									<span class="dashicons dashicons-yes"></span>
                                	%5$s
                                </a>

								<a href="#" data-repeat-notice-after="%6$s" class="astra-notice-close astra-review-notice">
									<span class="dashicons dashicons-calendar"></span>
                                	%7$s
                                </a>

                                <a href="#" class="astra-notice-close astra-review-notice">
								    <span class="dashicons dashicons-smiley"></span>
                                	<u>%8$s</u>
                                </a>
                            </div>
                        </div>',
					$image_path,
					__( 'Hi there! You recently used CartFlows to build a sales funnel &mdash; Thanks a ton!', 'cartflows' ),
					__( 'It would be awesome if you could leave us a 5-star review-it helps us grow and guide others in choosing CartFlows!', 'cartflows' ),
					'https://wordpress.org/support/plugin/cartflows/reviews/?filter=5#new-post',
					__( 'Ok, you deserve it', 'cartflows' ),
					MONTH_IN_SECONDS,
					__( 'Nope, maybe later', 'cartflows' ),
					__( 'I already did', 'cartflows' )
				),
				'repeat-notice-after'  => MONTH_IN_SECONDS,
				'display-notice-after' => ( 2 * WEEK_IN_SECONDS ), // Display notice after 2 weeks.
			)
		);

		
	}

	/**
	 * Render CartFlows NPS Survey Notice.
	 *
	 * @since 2.1.6
	 * @return void
	 */
	public function show_nps_notice() {

		Nps_Survey::show_nps_notice(
			'nps-survey-cartflows',
			array(
				'show_if'          => $this->should_display_nps_survey_notice(),
				'dismiss_timespan' => 2 * WEEK_IN_SECONDS,
				'display_after'    => 0,
				'plugin_slug'      => 'cartflows',
				'show_on_screens'  => array( 'edit-cartflows_flow', 'toplevel_page_cartflows' ),
				'message'          => array(

					// Step 1 i.e rating input.
					'logo'                  => esc_url( CARTFLOWS_URL . 'admin-core/assets/images/cartflows-icon.svg' ),
					'plugin_name'           => __( 'CartFlows', 'cartflows' ),
					'nps_rating_message'    => __( 'How likely are you to recommend #pluginname to your friends or colleagues?', 'cartflows' ),

					// Step 2A i.e. positive.
					'feedback_content'      => __( 'Could you please do us a favor and give us a 5-star rating on WordPress? It would help others choose CartFlows with confidence. Thank you!', 'cartflows' ),
					'plugin_rating_link'    => esc_url( 'https://wordpress.org/support/plugin/cartflows/reviews/?filter=5#new-post' ),

					// Step 2B i.e. negative.
					'plugin_rating_title'   => __( 'Thank you for your feedback', 'cartflows' ),
					'plugin_rating_content' => __( 'We value your input. How can we improve your experience?', 'cartflows' ),
				),
			)
		);
	}

	/**
	 * Show Deactivate gutenberg plugin notice.
	 *
	 * @since 1.1.19
	 *
	 * @return void
	 */
	public function gutenberg_plugin_deactivate_notice() {

		$ignore_notice = get_option( 'wcf_ignore_gutenberg_notice', false );

		if ( 'yes' !== $ignore_notice ) {
			printf(
				'<div class="notice notice-error wcf_notice_gutenberg_plugin is-dismissible"><p>%s</p>%s</div>',
				wp_kses_post(
					sprintf(
					/* translators: %1$s: HTML, %2$s: HTML */
						__( 'Heads up! The Gutenberg plugin is not recommended on production sites as it may contain non-final features that cause compatibility issues with CartFlows and other plugins. %1$s Please deactivate the Gutenberg plugin %2$s to ensure the proper functioning of your website.', 'cartflows' ),
						'<strong>',
						'</strong>'
					)
				),
				''
			);
		}
	}

	/**
	 * Ignore admin notice.
	 */
	public function ignore_gb_notice() {

		if ( ! current_user_can( 'cartflows_manage_flows_steps' ) ) {
			return;
		}

		check_ajax_referer( 'cartflows-ignore-gutenberg-notice', 'security' );

		update_option( 'wcf_ignore_gutenberg_notice', 'yes' );
	}

	/**
	 * Check allowed screen for notices.
	 *
	 * @since 1.0.0
	 *
	 * @param array $exclude_page_ids Optional. Array of screen IDs to exclude from displaying notices.
	 * @return bool True if the notice should be displayed, false otherwise.
	 */
	public function allowed_screen_for_notices( $exclude_page_ids = array() ) {

		$screen          = get_current_screen();
		$screen_id       = $screen ? $screen->id : '';
		$allowed_screens = array(
			'toplevel_page_cartflows',
			'dashboard',
			'plugins',
		);

		// Exclude any page ids passed in $exclude_page_ids from $allowed_screens.
		if ( ! empty( $exclude_page_ids ) && is_array( $exclude_page_ids ) ) {
			$allowed_screens = array_diff( $allowed_screens, $exclude_page_ids );
		}

		if ( in_array( $screen_id, $allowed_screens, true ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Check if the user has completed the onboarding, skipped the onboarding on ready step, and the store checkout is imported.
	 *
	 * @since 2.1.6
	 * @return bool
	 */
	public function should_display_nps_survey_notice() {

		$is_store_checkout_imported = (bool) get_option( '_cartflows_wizard_store_checkout_set', false );   // Must be true.
		$onboarding_completed       = (bool) get_option( 'wcf_setup_complete', false );                     // Must be true.
		$is_first_funnel_imported   = (bool) get_option( 'wcf_first_flow_imported', false );                // Must be true.
		$total_funnels              = intval( wp_count_posts( CARTFLOWS_FLOW_POST_TYPE )->publish );        // Must be greater than or equal to 1.

		/**
		 * Show the notice in two conditions.
		 * 1. If completed the onboarding steps/process of plugin and sets their first store checkout funnel successfully.
		 * 2. If sets up the first funnel manually and makes it live.
		 */
		return ( true === $is_store_checkout_imported && true === $onboarding_completed ) || ( true === $is_first_funnel_imported && ! empty( $total_funnels ) && 1 >= $total_funnels ) || ( ! empty( $total_funnels ) && 1 >= $total_funnels );
	}

	/**
	 * Show custom notice with Stripe-like UI.
	 *
	 * @return void
	 */
	public function show_custom_offer_notice() {

		// Return if the display conditions are not matched.
		if ( ! $this->should_show_bfcm_sale_notice() ) {
			return;
		}

		$image_path   = esc_url( CARTFLOWS_URL . 'admin/assets/images/launch-offer-banner.png' );
		$heading_icon = esc_url( CARTFLOWS_URL . 'admin/assets/images/loudspeaker.png' );

		$output = sprintf(
			'<div class="notice notice-info wcf-mega-notice wcf-custom-notice is-dismissible" style="background-image: url(%1$s);">	
			<div class="wcf-mega-notice-content">
					<div class="wcf-mega-notice-heading">
						<img src="%2$s" /> <div>%3$s</div>
					</div>
					<div class="wcf-mega-notice-description">
						<div class="wcf-mega-notice-description-row">
							<div><span class="dashicons dashicons-yes"></span> <span>%4$s</span></div>
							<div><span class="dashicons dashicons-yes"></span> <span>%5$s</span></div>
							<div><span class="dashicons dashicons-yes"></span> <span>%6$s</span></div>
							<div><span class="dashicons dashicons-yes"></span> <span>%7$s</span></div>
							<div><span class="dashicons dashicons-yes"></span> <span>%8$s</span></div>
							<div><span class="dashicons dashicons-yes"></span> <span>%9$s</span></div>
						</div>
					</div>
					<div class="wcf-mega-notice-action-container">
						<a href="%10$s" class="wcf-mega-notice-btn">
							%11$s
						</a>
					</div>
				</div>
				<div class="wcf-mega-notice-side-content">
					<a href="%12$s" class="wcf-mega-notice-secondary-btn">
						%13$s
					</a>
					<span>%14$s</span>
				</div>
			</div>',
			$image_path,                                                        // %1$s
			$heading_icon,                                                      // %2$s
			__( 'Unlock CartFlows Pro This Black Friday and Save up to 40%', 'cartflows' ), // %3$s
			__( 'One click upsells and order bumps', 'cartflows' ),             // %4$s
			__( 'Fully customizable checkout pages', 'cartflows' ),              // %5$s
			__( '20+ funnel templates', 'cartflows' ),                           // %6$s
			__( 'Advanced analytics to track funnel performance', 'cartflows' ), // %7$s
			__( 'Premium support and updates', 'cartflows' ),                    // %8$s
			__( '20+ more features that increase revenue', 'cartflows' ),        // %9$s
			'https://cartflows.com/pricing/?utm_source=free-cartflows&utm_medium=offer-banner&utm_campaign=launch-campaign', // %10$s
			__( 'Upgrade to Pro', 'cartflows' ),                                 // %11$s
			'https://cartflows.com/pricing/?utm_source=free-cartflows&utm_medium=offer-banner&utm_campaign=launch-campaign', // %12$s
			__( 'Limited Time Offer', 'cartflows' ),                             // %13$s
			__( 'Trusted by 200k+ users', 'cartflows' )                          // %14$s
		);

		echo wp_kses_post( $output );
	}

	/**
	 * Display the CartFlows Black Friday / Cyber Monday (BFCM) sale notice on WooCommerce admin pages.
	 *
	 * Registers and shows a custom sale promotional notice using the Astra Notices framework,
	 * styled with CartFlows branding and unique offer content.
	 *
	 * The notice is only displayed if the current admin context matches the required conditions
	 * determined by should_show_bfcm_sale_woocommerce_notice().
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function show_sale_notice_on_woocommerce_pages() {

		if ( ! $this->should_show_bfcm_sale_woocommerce_notice() ) {
			return;
		}

		// Register the Notices CSS.
		wp_enqueue_style( 'cartflows-custom-notices', CARTFLOWS_URL . 'admin/assets/css/notices.css', array(), CARTFLOWS_VER );

		$image_path = esc_url( CARTFLOWS_URL . 'assets/images/cartflows-logo-small.jpg' );
		Astra_Notices::add_notice(
			array(
				'id'                         => 'cartflows-bfcm-sale-notice',
				'type'                       => 'info',
				'class'                      => 'cartflows-5-star wcf-bfcm-sale-notice',
				'show_if'                    => true,
				/* translators: %1$s white label plugin name and %2$s deactivation link */
				'message'                    => sprintf(
					'<div class="notice-image" style="display: flex;">
                        <img src="%1$s" class="custom-logo" alt="CartFlows Icon" itemprop="logo" style="max-width: 90px; border-radius: 50px;"></div>
                        <div class="notice-content">
                            <div class="notice-heading">
                                %2$s
                            </div>
                            <div class="notice-description">
								<div class="wcf-sale-notice-description-row">
									<div><span class="dashicons dashicons-yes"></span> <span>%3$s</span></div>
									<div><span class="dashicons dashicons-yes"></span> <span>%4$s</span></div>
									<div><span class="dashicons dashicons-yes"></span> <span>%5$s</span></div>
									<div><span class="dashicons dashicons-yes"></span> <span>%6$s</span></div>
									<div><span class="dashicons dashicons-yes"></span> <span>%7$s</span></div>
									<div><span class="dashicons dashicons-yes"></span> <span>%8$s</span></div>
								</div>
							</div>
                            <div class="astra-review-notice-container">
                                <a href="%9$s" class="astra-notice-close astra-review-notice button-primary" target="_blank">
									<span class="dashicons dashicons-yes"></span>
                                	%10$s
                                </a>

								<a href="#" data-repeat-notice-after="%11$s" class="astra-notice-close astra-review-notice hidden-button">
									<span class="dashicons dashicons-calendar"></span>
                                	%12$s
                                </a>

                                <a href="#" class="astra-notice-close astra-review-notice">
								    <span class="dashicons dashicons-smiley"></span>
                                	<u>%13$s</u>
                                </a>
                            </div>
                        </div>',
					$image_path,
					__( 'Unlock CartFlows Pro This Black Friday and Save up to 40%', 'cartflows' ),
					__( 'One click upsells and order bumps', 'cartflows' ),
					__( 'Fully customizable checkout pages', 'cartflows' ),
					__( '20+ funnel templates', 'cartflows' ),
					__( 'Advanced analytics to track funnel performance', 'cartflows' ),
					__( 'Premium support and updates', 'cartflows' ),
					__( '20+ more features that increase revenue', 'cartflows' ),
					'https://cartflows.com/pricing/?utm_source=free-cartflows&utm_medium=offer-banner&utm_campaign=launch-campaign',
					__( 'Upgrade to Pro', 'cartflows' ),
					MONTH_IN_SECONDS,
					__( 'Nope, maybe later', 'cartflows' ),
					__( 'I already did', 'cartflows' )
				),
				'repeat-notice-after'        => false,
				'display-with-other-notices' => true,
				'display-notice-after'       => false, // Display notice after 2 weeks.
			)
		);
	}
	
	/**
	 * Determines if the Black Friday/Cyber Monday (BFCM) sale notice should be shown to the user.
	 *
	 * This method performs three main checks to decide whether to display the BFCM promotional notice:
	 *   1. Checks if the notice has been manually dismissed via the 'cartflows_show_custom_offer_notice'
	 *      option (expects 'no' for hidden, anything else for visible).
	 *   2. Ensures the current time falls within the hardcoded BFCM sale period
	 *      (from '2025-11-17 00:00:00' to '2025-11-21 00:00:00', server's timezone).
	 *   3. Limits notice display to approximately 25% of sites by hashing the site URL to a number between 0–99,
	 *      and only showing it if the number is ≤ 25 (simple "random" rollout based on site).
	 *
	 * @return bool True if the BFCM sale notice should be shown, false otherwise.
	 */
	public function should_show_bfcm_sale_notice() {

		$show_notice = true;

		// Return if the current page is the allowed page or the current user don't have the admin access.
		if ( ! $this->allowed_screen_for_notices( array( 'plugins' ) ) || ! current_user_can( 'cartflows_manage_flows_steps' ) ) {
			$show_notice = false;
			return $show_notice;
		}

		// Return if the notice is disabled.
		if ( 'no' === get_option( 'cartflows_show_custom_offer_notice' ) ) {
			$show_notice = false;
		}

		return $this->is_sale_date_expired();
	}

	/**
	 * Determines if the Black Friday/Cyber Monday (BFCM) WooCommerce sale notice should be shown to the user.
	 *
	 * This method checks:
	 *   1. Whether the current user is on one of the allowed WooCommerce admin screens.
	 *   2. Whether the current user has the 'cartflows_manage_flows_steps' capability.
	 *   3. Whether the current time falls within the specified BFCM sale period
	 *      (from '2025-11-17 00:00:00' to '2025-11-21 00:00:00', server's timezone).
	 *
	 * @return bool True if the WooCommerce BFCM sale notice should be shown, false otherwise.
	 */
	public function should_show_bfcm_sale_woocommerce_notice() {

		// Validate screen and capability first — cheap operations.
		$screen    = get_current_screen();
		$screen_id = $screen ? $screen->id : '';
	
		if ( ! current_user_can( 'cartflows_manage_flows_steps' ) ) {
			return false;
		}
	
		$allowed_screens = array(
			'woocommerce_page_wc-orders',
			'woocommerce_page_wc-reports',
			'edit-shop_coupon',
		);
	
		if ( ! in_array( $screen_id, $allowed_screens, true ) ) {
			return false;
		}
	
		// Check if inside sale window.
		return $this->is_sale_date_expired();
	}

	/**
	 * Checks whether the current date falls within the Black Friday / Cyber Monday (BFCM) sale period.
	 *
	 * This method compares the current WordPress time to the defined sale start ('2025-11-17 00:00:00')
	 * and end ('2025-11-21 00:00:00') timestamps, considering the site's timezone. Returns true 
	 * if the current date is within (inclusive) the sale period, false otherwise.
	 *
	 * @return bool True if the sale period is active, false otherwise.
	 */
	public function is_sale_date_expired() {

		// Cache computed timestamps for this request.
		static $start = null;
		static $end   = null;
	
		if ( null === $start || null === $end ) {
	
			// Get site timezone only once.
			$timezone = wp_timezone();
	
			// Prepare DateTime only once per request.
			$start = ( new DateTime( '2025-11-17 00:00:00', $timezone ) )->getTimestamp();
			$end   = ( new DateTime( '2025-12-04 23:59:59', $timezone ) )->getTimestamp();
		}
	
		// Current WP time.
		// phpcs:ignore WordPress.DateTime.CurrentTimeTimestamp.Requested
		$now = current_time( 'timestamp' );
	
		// Check if inside sale window.
		return ( $now >= $start && $now <= $end );
	}

	/**
	 * Dismiss custom notice via AJAX.
	 *
	 * @return void
	 */
	public function dismiss_custom_offer_notice() {

		// Check if the current user has permission to manage options.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'You do not have permission to perform this action.', 'cartflows' ),
					'code'    => 'no_permission',
				) 
			);
		}

		// Validate the nonce.
		$nonce_valid = isset( $_POST['security'] ) && check_ajax_referer( 'cartflows-dismiss-custom-offer-notice', 'security', false );
		if ( ! $nonce_valid ) {
			wp_send_json_error(
				array(
					'message' => __( 'Nonce verification failed. Please reload the page and try again.', 'cartflows' ),
					'code'    => 'nonce_failed',
				) 
			);
		}

		// Update the option to mark the custom offer notice as dismissed.
		$updated = update_option( 'cartflows_show_custom_offer_notice', 'no' );
		if ( false === $updated ) {
			wp_send_json_error(
				array(
					'message' => __( 'Could not update option. Please try again.', 'cartflows' ),
					'code'    => 'option_update_failed',
				) 
			);
		}

		// Send success response.
		wp_send_json_success(
			array(
				'message' => __( 'Custom offer notice dismissed successfully.', 'cartflows' ),
			) 
		);

	}
}

Cartflows_Admin_Notices::get_instance();
