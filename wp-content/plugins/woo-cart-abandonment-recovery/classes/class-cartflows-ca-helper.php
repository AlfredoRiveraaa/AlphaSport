<?php
/**
 * Cart Abandonment
 *
 * @package Woocommerce-Cart-Abandonment-Recovery
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Cart abandonment tracking class.
 */
class Cartflows_Ca_Helper {
	/**
	 * Member Variable
	 *
	 * @var object instance
	 */
	private static $instance;

	/**
	 *  Constructor function that initializes required actions and hooks.
	 */
	public function __construct() {
	}

	/**
	 *  Initiator
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Get checkout url.
	 *
	 * @param  int    $post_id    post id.
	 * @param  string $token_data token data.
	 * @return string
	 */
	public function get_checkout_url( $post_id, $token_data ) {

		$token        = $this->wcf_generate_token( (array) $token_data );
		$global_param = get_option( 'wcf_ca_global_param', false );
		$checkout_url = get_permalink( $post_id );

		$token_param  = [
			'wcf_ac_token' => $token,
		];
		$checkout_url = add_query_arg( $token_param, $checkout_url );

		if ( ! empty( $global_param ) ) {

			$query_param  = [];
			$global_param = preg_split( "/[\f\r\n]+/", $global_param );

			foreach ( $global_param as $key => $param ) {

				$param_parts                            = explode( '=', $param );
				$query_param[ trim( $param_parts[0] ) ] = trim( $param_parts[1] );
			}
			$checkout_url = add_query_arg( $query_param, $checkout_url );
		}

		return esc_url( $checkout_url );
	}

	/**
	 *  Geberate the token for the given data.
	 *
	 * @param array $data data.
	 */
	public function wcf_generate_token( $data ) {
		return urlencode( base64_encode( http_build_query( $data ) ) );
	}

	/**
	 * Get the acceptable order statuses.
	 */
	public function get_acceptable_order_statuses() {

		$excluded_order_statuses = get_option( 'wcf_ca_excludes_orders', [] );
		if ( ! is_array( $excluded_order_statuses ) ) {
			$excluded_order_statuses = [];
		}
		$acceptable_order_statuses = array_map( 'strtolower', $excluded_order_statuses );

		return $acceptable_order_statuses;
	}

	/**
	 * Generate comma separated products.
	 *
	 * @param string $cart_contents user cart details.
	 */
	public function get_comma_separated_products( $cart_contents ) {
		$cart_comma_string = '';
		if ( ! $cart_contents ) {
			return $cart_comma_string;
		}
		$cart_data = maybe_unserialize( $cart_contents );

		$cart_length = count( $cart_data );
		$index       = 0;
		foreach ( $cart_data as $key => $product ) {

			if ( ! isset( $product['product_id'] ) ) {
				continue;
			}

			$cart_product = wc_get_product( $product['product_id'] );

			if ( $cart_product ) {
				$cart_comma_string .= $cart_product->get_title();
				if ( $cart_length - 2 === $index ) {
					$cart_comma_string .= ' & ';
				} elseif ( $cart_length - 1 !== $index ) {
					$cart_comma_string .= ', ';
				}
				$index++;
			}
		}
		return $cart_comma_string;
	}

	/**
	 * Count abandoned carts
	 *
	 * @since 1.1.5
	 */
	public function abandoned_cart_count() {
		global $wpdb;
		$cart_abandonment_table_name = $wpdb->prefix . CARTFLOWS_CA_CART_ABANDONMENT_TABLE;
		// Can't use placeholders for table/column names, it will be wrapped by a single quote (') instead of a backquote (`).
		return $wpdb->get_var(
			$wpdb->prepare( "SELECT COUNT(`id`) FROM {$cart_abandonment_table_name}  WHERE `order_status` = %s", WCF_CART_ABANDONED_ORDER ) //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		);
	}

	/**
	 * Get start and end date for given interval.
	 *
	 * @param  string $interval interval .
	 * @return array
	 */
	public function get_start_end_by_interval( $interval ) {

		if ( 'today' === $interval ) {
			$start_date = gmdate( 'Y-m-d' );
			$end_date   = gmdate( 'Y-m-d' );
		} else {

			$days = $interval;

			$start_date = gmdate( 'Y-m-d', strtotime( '-' . $days . ' days' ) );
			$end_date   = gmdate( 'Y-m-d' );
		}

		return [
			'start' => $start_date,
			'end'   => $end_date,
		];
	}

	/**
	 * Get the checkout details for the user.
	 *
	 * @param string $wcf_session_id checkout page session id.
	 * @since 1.0.0
	 */
	public function get_checkout_details( $wcf_session_id ) {
		global $wpdb;
		$cart_abandonment_table = $wpdb->prefix . CARTFLOWS_CA_CART_ABANDONMENT_TABLE;
		// Can't use placeholders for table/column names, it will be wrapped by a single quote (') instead of a backquote (`).
		return $wpdb->get_row(
			$wpdb->prepare( "SELECT * FROM {$cart_abandonment_table} WHERE session_id = %s", $wcf_session_id ) //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		);
	}

	/**
	 * Fetch all the scheduled emails with templates for the specific session.
	 *
	 * @param string $session_id session id.
	 * @param bool   $fetch_sent sfetch sent emails.
	 * @return array|object|null
	 */
	public function fetch_scheduled_emails( $session_id, $fetch_sent = false ) {
		global $wpdb;
		$email_history_table  = $wpdb->prefix . CARTFLOWS_CA_EMAIL_HISTORY_TABLE;
		$email_template_table = $wpdb->prefix . CARTFLOWS_CA_EMAIL_TEMPLATE_TABLE;
		// Can't use placeholders for table/column names, it will be wrapped by a single quote (') instead of a backquote (`).
		$query = $wpdb->prepare( "SELECT * FROM  {$email_history_table} as eht INNER JOIN {$email_template_table} as ett ON eht.template_id = ett.id WHERE ca_session_id = %s", sanitize_text_field( $session_id ) ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared

		if ( $fetch_sent ) {
			$query .= ' AND email_sent = 1';
		}
		// Query is prepared above.
		return $wpdb->get_results(
			$query //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		);
	}

	/**
	 * Sanitize text field.
	 *
	 * @param string $key field key to sanitize.
	 * @param string $method method type.
	 */
	public function sanitize_text_filter( $key, $method = 'POST' ) {

		$sanitized_value = '';
		//phpcs:disable WordPress.Security.NonceVerification
		if ( 'POST' === $method && isset( $_POST[ $key ] ) ) {
			$sanitized_value = sanitize_text_field( wp_unslash( $_POST[ $key ] ) );
		}

		if ( 'GET' === $method && isset( $_GET[ $key ] ) ) {
			$sanitized_value = sanitize_text_field( wp_unslash( $_GET[ $key ] ) );
		}
		//phpcs:enable WordPress.Security.NonceVerification
		return $sanitized_value;
	}

	/**
	 * Conditional file extentions.
	 *
	 * @return array
	 */
	public function get_js_file_ext() {

		return [
			'folder'   => SCRIPT_DEBUG ? 'js' : 'min-js',
			'file_ext' => SCRIPT_DEBUG ? 'js' : 'min.js',
		];
	}

	/**
	 * Checks the current page to see if it contains checkout block.
	 *
	 * @param int|null $post_id The current post ID.
	 * @return bool
	 * @since 1.3.0
	 */
	public static function is_block_checkout( $post_id = null ) {
		return has_block( 'woocommerce/checkout', $post_id );
	}

	/**
	 * Get plugin status.
	 *
	 * @since 2.0.0
	 *
	 * @param  string $plugin_init_file Plguin init file.
	 * @return mixed
	 */
	public function get_plugin_status( $plugin_init_file ) {

		$installed_plugins = get_plugins();

		if ( ! isset( $installed_plugins[ $plugin_init_file ] ) ) {
			return 'not-installed';
		}
		if ( is_plugin_active( $plugin_init_file ) ) {
			return 'active';
		}
			return 'inactive';
	}

	/**
	 *  Get Attributable revenue.
	 *  Represents the revenue generated by this campaign.
	 *
	 * @param string $from_date from date.
	 * @param string $to_date to date.
	 * @param string $type abondened|completed.
	 */
	public function get_report_by_type( $from_date, $to_date, $type = WCF_CART_ABANDONED_ORDER ) {
		global $wpdb;
		$cart_abandonment_table = $wpdb->prefix . CARTFLOWS_CA_CART_ABANDONMENT_TABLE;
		$minutes                = wcf_ca()->utils->get_cart_abandonment_tracking_cut_off_time();
		// Can't use placeholders for table/column names, it will be wrapped by a single quote (') instead of a backquote (`).
		return $wpdb->get_row(
			$wpdb->prepare( "SELECT  SUM(`cart_total`) as revenue, count('*') as no_of_orders  FROM {$cart_abandonment_table} WHERE `order_status` = %s AND DATE(`time`) >= %s AND DATE(`time`) <= %s  ", $type, $from_date, $to_date ), // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			ARRAY_A
		);
	}

	/**
	 * Retrieve a list of WordPress user roles excluding the 'Customer' role.
	 *
	 * This function fetches all the available WordPress user roles and removes the 'Customer' role from the list.
	 * It returns an array of role names that can be used for various purposes such as role-based access control or user management.
	 *
	 * @return array An array of WordPress user role names excluding 'Customer'.
	 */
	public function get_wordpress_user_roles() {

		$roles_obj         = new WP_Roles();
		$roles_names_array = $roles_obj->get_names();
		return array_diff( $roles_names_array, [ 'Customer' ] );
	}

	/**
	 * Retrieve a list of WooCommerce order statuses excluding 'Refunded', 'Draft', and 'Cancelled'.
	 *
	 * This function fetches all the available WooCommerce order statuses, removes the 'wc-' prefix from the status keys,
	 * and excludes the 'Refunded', 'Draft', and 'Cancelled' statuses from the list. It returns an array of order status names
	 * that can be used for various purposes such as order management or reporting.
	 *
	 * @return array An array of WooCommerce order status names excluding 'Refunded', 'Draft', and 'Cancelled'.
	 */
	public function get_order_statuses() {
		$order_status = []; // Initialize an empty array to store order statuses.

		if ( ! function_exists( 'WC' ) ) { // Check if WooCommerce is not active.
			return $order_status; // Return an empty array if WooCommerce is not active.
		}

		$order_status     = wc_get_order_statuses(); // Fetch all WooCommerce order statuses.
		$new_order_status = str_replace( 'wc-', '', array_keys( $order_status ) );
		$order_status     = array_combine( $new_order_status, $order_status ); // Combine new keys with original values.

		// Return the modified array of order statuses.
		return \array_diff( $order_status, [ 'Refunded', 'Draft', 'Cancelled' ] );
	}

	/**
	 * Save single option with value.
	 *
	 * Simplified function for saving a single option with a specific value.
	 * Useful for AJAX handlers that receive option_name and value separately.
	 *
	 * @param string $option_key Option key to save.
	 * @param mixed  $value      Value to save.
	 * @param bool   $network    Whether to save as network option.
	 *
	 * @return bool True on success, false on failure.
	 * @since 1.3.3
	 */
	public function save_meta_fields( $option_key, $value, $network = false ) {
		// Sanitize the value using universal sanitization.
		$sanitized_value = wcf_ca()->options->sanitize_setting_value( $option_key, $value );

		// Save option (network or regular).
		if ( $network && is_multisite() ) {
			return update_site_option( $option_key, $sanitized_value );
		}
		
		return update_option( $option_key, $sanitized_value );
	}

	/**
	 * Robust unserialize for order fields, handles double-serialization and returns arrays as-is.
	 *
	 * @param mixed $value Serialized string or array.
	 * @return mixed
	 */
	public function wcar_safe_unserialize( $value ) {
		if ( is_array( $value ) ) {
			return $value;
		}
		if ( is_serialized( $value ) ) {
			$value = maybe_unserialize( $value );
			if ( is_string( $value ) && is_serialized( $value ) ) {
				$value = maybe_unserialize( $value );
			}
		}
		return $value;
	}

	/**
	 * Function get the CartFlows upgrade to PRO link.
	 *
	 * @param string $page      The page name which needs to be displayed.
	 * @param string $custom_url The Another URL if wish to send.
	 * @return string $url The modified URL.
	 */
	public static function get_upgrade_to_pro_url( $page = 'cart-abandonment', $custom_url = '' ) {

		$custom_page = $page ? $page . '/' : '';

		$base_url = CARTFLOWS_CA_DOMAIN_URL . $custom_page;
		$url      = empty( $custom_url ) ? $base_url : esc_url( $custom_url );

		$partner_id = get_option( 'cartflows_ca_partner_url_param', '' );
		$partner_id = is_string( $partner_id ) ? sanitize_text_field( $partner_id ) : '';

		if ( ! empty( $partner_id ) ) {
			return add_query_arg( array( 'wcar' => $partner_id ), $url );
		}

		// Modify the utm_source parameter using the UTM ready link function to include tracking information.
		if ( class_exists( '\BSF_UTM_Analytics' ) && is_callable( '\BSF_UTM_Analytics::get_utm_ready_link' ) ) {
			$url = \BSF_UTM_Analytics::get_utm_ready_link( $url, 'woo-cart-abandonment-recovery' );
		}

		return esc_url( $url );
	}

}

Cartflows_Ca_Helper::get_instance();

// ==========================================
// WCAR Pro Plugin Status Functions.
// ==========================================

if ( ! function_exists( '_is_wcar_pro' ) ) {
	/**
	 * Check if WCAR Pro plugin is installed and activated.
	 * Similar to CartFlows _is_cartflows_pro() function.
	 *
	 * @return bool True if Pro plugin is active, false otherwise.
	 */
	function _is_wcar_pro() {
		return defined( 'WCAR_PRO_FILE' );
	}
}

if ( ! function_exists( '_is_wcar_pro_license_activated' ) ) {
	/**
	 * Check if WCAR Pro license is activated.
	 * Similar to CartFlows _is_cartflows_pro_license_activated() function.
	 *
	 * @return bool True if license is activated, false otherwise.
	 */
	function _is_wcar_pro_license_activated() {
		if ( _is_wcar_pro() && class_exists( 'WCAR_Pro_Licence' ) ) {
			return 'activated' === strtolower( WCAR_Pro_Licence::get_instance()->activate_status );
		}
		
		return false;
	}
}
