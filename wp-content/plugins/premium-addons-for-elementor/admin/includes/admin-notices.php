<?php
/**
 * PA Admin Notices.
 */

namespace PremiumAddons\Admin\Includes;

use PremiumAddons\Includes\Helper_Functions;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

/**
 * Class Admin_Notices
 */
class Admin_Notices {

	/**
	 * Premium Addons Stories
	 *
	 * @var stories
	 */
	private $stories = array();

	/**
	 * Class object
	 *
	 * @var instance
	 */
	private static $instance = null;

	/**
	 * Elementor slug
	 *
	 * @var elementor
	 */
	private static $elementor = 'elementor';

	/**
	 * PAPRO Slug
	 *
	 * @var papro
	 */
	private static $papro = 'premium-addons-pro';

	/**
	 * Notices Keys
	 *
	 * @var notices
	 */
	private static $notices = null;

	/**
	 * Constructor for the class
	 */
	public function __construct() {

		add_action( 'admin_init', array( $this, 'init' ) );

		add_action( 'admin_notices', array( $this, 'admin_notices' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

		add_action( 'wp_ajax_pa_reset_admin_notice', array( $this, 'reset_admin_notice' ) );

		add_action( 'wp_ajax_pa_dismiss_admin_notice', array( $this, 'dismiss_admin_notice' ) );

		self::$notices = array(
			'pa-review',
			'bf25-last-not'
		);

		if ( Helper_Functions::check_hide_notifications() ) {
			return;
		}

		add_action( 'wp_dashboard_setup', array( $this, 'show_story_widget' ), 111 );
	}

	/**
	 * Init
	 *
	 * Init required functions
	 *
	 * The redirection happens on the first admin page after activation ( the plugins page ).
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function init() {

		$this->handle_review_notice();

		if ( defined( 'ELEMENTOR_VERSION' ) && get_transient( 'pa_activation_redirect' ) ) {

			delete_transient( 'pa_activation_redirect' );

			$redirect = add_query_arg(
				array(
					'page' => 'pa-setup-wizard', // this mean it should've been added first.
				),
				admin_url( 'admin.php' )
			);

			wp_safe_redirect( $redirect );

			exit;
		}
	}

	/**
	 * Init notices check functions.
	 */
	public function admin_notices() {

		// Skip rendering notices during AJAX requests.
		if ( wp_doing_ajax() ) {
			return;
		}

		$this->required_plugins_check();

		// Make sure "Already did" was not clicked before.
		$show_review = get_option( 'pa_review_notice' );
		if ( '1' !== $show_review ) {

			$cache_key = 'pa_review_notice';

			$response = get_transient( $cache_key );

			if ( false == $response ) {
				$this->show_review_notice();
			}
		}

		if ( Helper_Functions::check_hide_notifications() ) {
			return;
		}

		// $this->get_black_friday_notice();

	}

	/**
	 * Handle Review Notice
	 *
	 * Checks if review message is dismissed.
	 *
	 * @access public
	 * @return void
	 */
	public function handle_review_notice() {

		if ( ! isset( $_GET['pa_review'] ) ) {
			return;
		}

		if ( 'opt_out' === $_GET['pa_review'] ) {
			check_admin_referer( 'opt_out' );

			update_option( 'pa_review_notice', '1' );
		}

		wp_safe_redirect( remove_query_arg( 'pa_review' ) );

		exit;
	}

	/**
	 * Required plugin check
	 *
	 * Shows an admin notice when Elementor is missing.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function required_plugins_check() {

		// Early return if Elementor is already active.
		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			return;
		}

		$elementor_path = sprintf( '%1$s/%1$s.php', self::$elementor );

		$message = '';

		if ( ! Helper_Functions::is_plugin_installed( $elementor_path ) ) {

			if ( ! Admin_Helper::check_user_can( 'install_plugins' ) ) {
				return;
			}

			$install_url = wp_nonce_url( self_admin_url( sprintf( 'update.php?action=install-plugin&plugin=%s', self::$elementor ) ), 'install-plugin_elementor' );

			$message = sprintf( '<p>%s</p>', __( 'Premium Addons for Elementor is not working because you need to Install Elementor plugin.', 'premium-addons-for-elementor' ) );

			$message .= sprintf( '<p><a href="%s" class="button-primary">%s</a></p>', $install_url, __( 'Install Now', 'premium-addons-for-elementor' ) );

		} elseif ( Admin_Helper::check_user_can( 'activate_plugins' ) ) {

			$activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $elementor_path . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $elementor_path );

			$message = '<p>' . __( 'Premium Addons for Elementor is not working because you need to activate Elementor plugin.', 'premium-addons-for-elementor' ) . '</p>';

			$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $activation_url, __( 'Activate Now', 'premium-addons-for-elementor' ) ) . '</p>';
		} else {
			return;
		}

		if ( ! empty( $message ) ) {
			$this->render_admin_notices( $message );
		}
	}

	/**
	 * Get Review Text
	 *
	 * Gets admin review notice HTML.
	 *
	 * @since 2.8.4
	 * @access public
	 *
	 * @param string $review_url plugin page.
	 * @param string $optout_url redirect url.
	 */
	public function get_review_text( $review_url, $optout_url ) {

		$notice = sprintf(
			'<p>' . __( 'Could we take just 2 minutes of your time? We\'d be incredibly grateful if you could give ', 'premium-addons-for-elementor' ) .
			'<b>' . __( 'Premium Addons for Elementor', 'premium-addons-for-elementor' ) . '</b> a 5 Stars Rating on WordPress.org. Your support helps us continue creating even more amazing free features in the future!</p>
            <div>
                <a class="button pa-review-btn button-primary" href="%s" target="_blank"><span>' . __( 'Sure, leave a Review', 'premium-addons-for-elementor' ) . '</span></a>
                <a class="button" href="%2$s"><span>' . __( 'I Already Did', 'premium-addons-for-elementor' ) . '</span></a>
                <a class="button button-secondary pa-notice-reset"><span>' . __( 'Maybe Later', 'premium-addons-for-elementor' ) . '</span></a>
            </div>',
			$review_url,
			$optout_url
		);

		return $notice;
	}

	/**
	 * Checks if review admin notice is dismissed
	 *
	 * @since 2.6.8
	 * @return void
	 */
	public function show_review_notice() {

		$review_url = 'https://wordpress.org/support/plugin/premium-addons-for-elementor/reviews/#new-post';

		$optout_url = wp_nonce_url( add_query_arg( 'pa_review', 'opt_out' ), 'opt_out' );
		?>

		<div class="error pa-notice-wrap pa-review-notice" data-notice="pa-review">
			<div class="pa-img-wrap">
				<img src="<?php echo esc_url( PREMIUM_ADDONS_URL . 'admin/images/pa-logo-symbol.png' ); ?>">
			</div>
			<div class="pa-text-wrap">
				<?php echo wp_kses_post( $this->get_review_text( $review_url, $optout_url ) ); ?>
			</div>
			<div class="pa-notice-close">
				<a href="<?php echo esc_url( $optout_url ); ?>"><span class="dashicons dashicons-dismiss"></span></a>
			</div>
		</div>

		<?php
	}

	public function get_black_friday_notice() {

        $time     = time();

        if ( $time > 1765497600 || '1' === get_option( 'bf25-last-not' ) ) {
			return;
		}

		$is_papro_active = Helper_Functions::check_papro_version();

		$license_key = get_option( 'papro_license_key' );

		$link = Helper_Functions::get_campaign_link( 'https://premiumaddons.com/black-friday/#bfdeals', 'wp-dash', 'bf25-notification', 'cm25' );

		$promotion_type = 'new';

        if ( $is_papro_active ) {

			$license_data = get_transient( 'pa_license_info' );

            if( isset( $license_data['status'] ) && 'valid' === $license_data['status'] ) {

				if( isset( $license_data['id'] ) && '4' === $license_data['id'] ) {
					return;
				} else {

					$promotion_type = 'upgrade';

					$link = Helper_Functions::get_campaign_link( 'https://premiumaddons.com/docs/upgrade-premium-addons-license/', 'wp-dash', 'bf25-notification', 'cm25' );
				}

            }

		}

		$message = $this->get_promotion_message( $promotion_type );

		?>

		<div class="error pa-notice-wrap pa-new-feature-notice pa-review-notice">
			<div class="pa-img-wrap">
				<img src="<?php echo PREMIUM_ADDONS_URL . 'admin/images/pa-logo-symbol.png'; ?>">
			</div>
			<div class="pa-text-wrap">
				<p>
					<?php echo wp_kses_post( $message['message'] ); ?>
					<a class="button pa-cta-btn button-primary" href="<?php echo esc_url( $link ); ?>" target="_blank">
						<span><?php echo wp_kses_post( $message['cta'] ); ?></span>
					</a>
				</p>
			</div>
			<div class="pa-notice-close" data-notice="bf25-last-not">
				<span class="dashicons dashicons-dismiss"></span>
			</div>
		</div>

		<?php
	}

	/**
	 * Get Promotion Message
	 *
	 * @since 4.11.43
	 * @access private
	 *
	 * @param string $type promotion type.
	 * @return array
	 */
	private function get_promotion_message( $type = 'new' ) {

		if ( 'upgrade' === $type ) {
			return array(
				'message' => __( 'Get a <b>FLAT 35% OFF</b> when you upgrade to <b>Premium Addons Pro Lifetime</b>. Use code <b>BFUL2025</b> at checkout – <b>expires soon!</b>', 'premium-addons-for-elementor' ),
				'cta'    => __( 'Upgrade Now', 'premium-addons-for-elementor' ),
			);

		}

		return array(
			'message' => __( '<b>Cyber Monday – Save Up To $105 on Premium Addons Pro</b>.', 'premium-addons-for-elementor' ),
			'cta'    => __( 'Catch The Deal', 'premium-addons-for-elementor' ),
		);
	}

	/**
	 * Renders an admin notice error message
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @param string $message notice text.
	 * @param string $class notice class.
	 * @param string $handle notice handle.
	 *
	 * @return void
	 */
	private function render_admin_notices( $message, $class = '', $handle = '' ) {
		?>
			<div class="error pa-new-feature-notice <?php echo esc_attr( $class ); ?>" data-notice="<?php echo esc_attr( $handle ); ?>">
				<?php echo wp_kses_post( $message ); ?>
			</div>
		<?php
	}



	/**
	 * Register admin scripts
	 *
	 * @since 3.2.8
	 * @access public
	 */
	public function admin_enqueue_scripts() {

		// Skip loading scripts during AJAX or REST requests.
		if ( wp_doing_ajax() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
			return;
		}

		wp_enqueue_script(
			'pa-dashboard',
			PREMIUM_ADDONS_URL . 'admin/assets/js/pa-dashboard.js',
			array( 'jquery' ),
			PREMIUM_ADDONS_VERSION,
			true
		);

		wp_localize_script(
			'pa-dashboard',
			'PaNoticeSettings',
			array(
				'ajaxurl' => esc_url( admin_url( 'admin-ajax.php' ) ),
				'nonce'   => wp_create_nonce( 'pa-notice-nonce' ),
			)
		);
	}

	/**
	 * Set transient for admin notice
	 *
	 * @since 3.2.8
	 * @access public
	 *
	 * @return void
	 */
	public function reset_admin_notice() {

		check_ajax_referer( 'pa-notice-nonce', 'nonce' );

		if ( ! Admin_Helper::check_user_can( 'manage_options' ) ) {
			wp_send_json_error();
		}

		$key = isset( $_POST['notice'] ) ? sanitize_text_field( wp_unslash( $_POST['notice'] ) ) : '';

		if ( ! empty( $key ) && in_array( $key, self::$notices, true ) ) {

			$cache_key = 'pa_review_notice';

			set_transient( $cache_key, true, WEEK_IN_SECONDS );

			wp_send_json_success();

		} else {

			wp_send_json_error();

		}
	}

	/**
	 * Dismiss admin notice
	 *
	 * @since 3.11.7
	 * @access public
	 *
	 * @return void
	 */
	public function dismiss_admin_notice() {

		check_ajax_referer( 'pa-notice-nonce', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error();
		}

		$key = isset( $_POST['notice'] ) ? sanitize_text_field( wp_unslash( $_POST['notice'] ) ) : '';

		if ( ! empty( $key ) && in_array( $key, self::$notices, true ) ) {

			// Make sure new features notices will not appear again.
			if ( false != strpos( $key, 'not' ) ) {
				update_option( $key, '1' );
			} else {
				set_transient( $key, true, 20 * DAY_IN_SECONDS );

			}

			wp_send_json_success();

		} else {

			wp_send_json_error();

		}
	}

	/**
	 * Check Status
	 *
	 * @since 4.10.15
	 * @access public
	 */
	public function check_status( $key ) {

		$status = false;

		$api_params = array(
			'edd_action' => 'check_license',
			'license'    => $key,
			'item_id'    => 361,
		);

		$response = wp_remote_get(
			'http://my.leap13.com',
			array(
				'timeout'   => 15,
				'sslverify' => false,
				'body'      => $api_params,
			)
		);

		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
			return false;
		}

		$body = wp_remote_retrieve_body( $response );

		$body = json_decode( $body, true );

		if ( isset( $body['license'] ) && 'valid' === $body['license'] ) {
			$status = true;
		}

		return $status;
	}

	/**
	 * Get PA Stories
	 *
	 * Gets a list of the latest three blog posts
	 *
	 * @since 4.10.64
	 *
	 * @access public
	 */
	public function get_pa_stories() {

		$stories = get_transient( 'pa_stories_bf' );

		if ( ! $stories ) {

			$api_url = 'https://premiumaddons.com/wp-json/stories/v2/get';

			$response = wp_remote_get(
				$api_url,
				array(
					'timeout'   => 15,
					'sslverify' => true,
				)
			);

			if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
				set_transient( 'pa_stories_bf', true, WEEK_IN_SECONDS );
				return false;
			}

			$body    = wp_remote_retrieve_body( $response );
			$stories = json_decode( $body, true );

			set_transient( 'pa_stories_bf', $stories, WEEK_IN_SECONDS );

		}

		$this->stories = $stories;

		return $stories;
	}

	public function show_story_widget() {

		$stories = $this->get_pa_stories();

		if ( ! is_array( $stories ) || empty( $stories ) ) {
			return;
		}

		wp_add_dashboard_widget(
			'pa-stories',
			__( 'Premium Addons News', 'premium-addons-for-elementor' ),
			array( $this, 'show' ),
			null,
			null,
			'column3',
			'core'
		);
	}


	public function show() {

		$stories = $this->stories;

		$time = time();

		$papro_path = 'premium-addons-pro/premium-addons-pro-for-elementor.php';

		$license_data = get_transient( 'pa_license_info' );
		$highlight = false;

		if( isset( $license_data['status'] ) && 'valid' === $license_data['status'] ) {

			if( isset( $license_data['id'] ) && '4' !== $license_data['id'] ) {

				$highlight = true;
				array_unshift( $stories['posts'], array(
					'title' => 'Switch to Premium Addons Pro Lifetime, Pay the Difference & Save 35% Today!',
					'link'  => Helper_Functions::get_campaign_link( 'https://premiumaddons.com/docs/upgrade-premium-addons-license/', 'wp-dash', 'bf25-dash-widget', 'cm25' ),
				) );

			}

		}


		?>
			<style>
				.pa-banners-grid {
					margin-bottom: 10px;
				}

				.pa-stories-banner {
					position: relative;
				}

				.pa-stories-banner a {
					position: absolute;
					inset: 0;
				}

				.pa-story-img-container img {
					width: 100%;
					display: block;
				}

				.pa-news-post {
					margin-bottom: 5px;
				}

				.pa-news-post a {
					font-weight: 500;
					color: #0073aa;
					text-decoration: none;
					padding-bottom: 5px;
					display: inline-block;
				}

				.pa-dashboard-widget-block {
					width: 100%;
				}

				.pa-footer-bar {
					border-top: 1px solid #eee;
					padding-top: 1rem;
					display: flex;
					justify-content: space-between;
				}

				.pa-dashboard-widget-block a {
					text-decoration: none;
					font-size: 13px;
					color: #007cba;
				}

				.pa-dashboard-widget-block .dashicons {
					vertical-align: middle;
					font-size: 17px;
				}
			</style>


			<div class="pa-banners-grid">

				<?php foreach ( $stories['banners'] as $index => $banner ) : ?>

					<?php if ( isset( $banner['end'] ) && $time < $banner['end'] ) : ?>

						<div class="pa-stories-banner">
							<div class="pa-story-img-container">
								<img src="<?php echo esc_url( $banner['image'] ); ?>" alt="<?php echo esc_attr( $banner['description'] ); ?>">
							</div>
							<a href="<?php echo esc_url( Helper_Functions::get_campaign_link( $banner['link'], 'wp-dash', 'dash-widget', 'cm25' ) ); ?>" target="_blank" title="<?php echo esc_attr( $banner['description'] ); ?>"></a>
						</div>

					<?php endif; ?>

				<?php endforeach; ?>

			</div>


			<div class="pa-posts-grid">

				<?php foreach ( $stories['posts'] as $index => $post ) : ?>

					<div class="pa-news-post">
						<a style="<?php echo 0 === $index && $highlight ? 'color: #93003f' : '' ?>" target="_blank" href="<?php echo esc_url( $post['link'] ); ?>">
							<?php echo wp_kses_post( $post['title'] ); ?>
						</a>
					</div>

				<?php endforeach; ?>

			</div>

			<div class="pa-dashboard-widget-block">
				<div class="pa-footer-bar">
					<a href="https://my.leap13.com/contact-support" target="_blank" style="color: #27ae60">
						Need Help?
						<span aria-hidden="true" class="dashicons dashicons-external"></span>
					</a>
					<a href="https://www.youtube.com/leap13" target="_blank" style="color: #e1002d">
						YouTube Channel
						<span aria-hidden="true" class="dashicons dashicons-youtube"></span>
					</a>
					<a href="https://www.facebook.com/groups/PremiumAddons" target="_blank" style="color: #1877F2;">
						Facebook Community
						<span aria-hidden="true" class="dashicons dashicons-facebook-alt"></span>
					</a>
				</div>
			</div>

		<?php
	}

	/**
	 * Creates and returns an instance of the class
	 *
	 * @since 2.8.4
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
