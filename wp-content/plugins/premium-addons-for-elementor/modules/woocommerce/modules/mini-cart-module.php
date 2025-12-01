<?php
/**
 * Class: Mini_Cart_Module
 * Name: Woocommerce Mini Cart
 * PA WooCommerce Modules.
 *
 * @package PA
 */

namespace PremiumAddons\Modules\Woocommerce\Modules;

use PremiumAddons\Includes\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // If this file is called directly, abort.
}

/**
 * Class Mini Cart Module.
 */
class Mini_Cart_Module extends Module_Base {

	/**
	 * Instance variable
	 *
	 * @var $instance.
	 */
	private static $instance = null;

	/**
	 * Instance.
	 *
	 * @return object self::$instance
	 */
	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Get Module Name.
	 *
	 * @since 4.7.0
	 * @access public
	 *
	 * @return string Module name.
	 */
	public function get_name() {
		return 'woocommerce-mini-cart';
	}

	/**
	 * Constructor.
	 *
	 * @since 4.7.0
	 * @access public
	 */
	public function __construct() {
		parent::__construct();

		if ( ! defined( 'ELEMENTOR_PRO_VERSION' ) ) {
			add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'pa_maybe_init_cart' ) );
		}

		add_action( 'wp_ajax_pa_update_mc_qty', array( $this, 'pa_update_mc_qty' ) );
		add_action( 'wp_ajax_nopriv_pa_update_mc_qty', array( $this, 'pa_update_mc_qty' ) );

		add_action( 'wp_ajax_pa_delete_cart_item', array( $this, 'pa_delete_cart_item' ) );
		add_action( 'wp_ajax_nopriv_pa_delete_cart_item', array( $this, 'pa_delete_cart_item' ) );

		add_action( 'wp_ajax_pa_delete_cart_items', array( $this, 'pa_delete_cart_items' ) );
		add_action( 'wp_ajax_nopriv_pa_delete_cart_items', array( $this, 'pa_delete_cart_items' ) );

		add_action( 'wp_ajax_pa_apply_coupon', array( $this, 'pa_apply_coupon' ) );
		add_action( 'wp_ajax_nopriv_pa_apply_coupon', array( $this, 'pa_apply_coupon' ) );

		add_action( 'wp_ajax_pa_remove_coupon', array( $this, 'pa_remove_coupon' ) );
		add_action( 'wp_ajax_nopriv_pa_remove_coupon', array( $this, 'pa_remove_coupon' ) );

		$enabled_keys = get_option( 'pa_save_settings', array() );

		$mc_custom_temp_enabled = isset( $enabled_keys['pa_mc_temp'] ) ? $enabled_keys['pa_mc_temp'] : false;

		if ( $mc_custom_temp_enabled ) {
			add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'pa_add_mini_cart_fragments' ) );
		}
	}

	/**
	 * Inits a new session of the woocommerce cart in case it's not initiated.
	 * This is added in case Elementor PRO isn't activated to solve the editor errors related to the cart.
	 */
	public function pa_maybe_init_cart() {
		$has_cart = is_a( WC()->cart, 'WC_Cart' );

		if ( ! $has_cart ) {
			$session_class = apply_filters( 'woocommerce_session_handler', 'WC_Session_Handler' );
			WC()->session  = new $session_class();
			WC()->session->init();
			WC()->cart     = new \WC_Cart();
			WC()->customer = new \WC_Customer( get_current_user_id(), true );
		}
	}

	/**
	 * Adds our custom mini cart fragments to the woocommerce fragments.
	 * These fragments will be updated when the cart is updated or the fragments are refreshed.
	 *
	 * @param array $fragments The existing fragments passed by WooCommerce.
	 *
	 * @return array Modified fragments with custom mini cart content.
	 */
	public function pa_add_mini_cart_fragments( $fragments ) {

		$product_count = WC()->cart->get_cart_contents_count();

		$discount_total = WC()->cart->get_discount_total();
		$raw_subtotal   = WC()->cart->get_subtotal();

		$display_incl_tax = 'yes' === get_option( 'woocommerce_calc_taxes' );

		if ( $display_incl_tax ) {
			$raw_subtotal += WC()->cart->get_taxes_total();
		}

		$raw_subtotal_amount = number_format( floatval( $raw_subtotal ) - $discount_total, 2, '.', '' );
		// TODO: check if we can show/hide the tax label through the args passed here.
		$subtotal = wc_price( floatVal( $raw_subtotal ) - $discount_total, array( 'in_span' => false ) );

		$count_txt = 1 === $product_count ? ' item' : ' items';

		$empty_count_cls = ! $product_count ? 'pa-hide-badge' : '';

		$fragments['.pa-woo-mc__count-placeholder']                                    = '<span class="pa-woo-mc__count-placeholder" style="display:none">' . $product_count . '</span>';
		$fragments['.pa-woo-mc__text-wrapper .pa-woo-mc__subtotal-placeholder']        = '<span class="pa-woo-mc__subtotal-placeholder" style="display:none">' . $raw_subtotal_amount . '</span>';
		$fragments['.pa-woo-mc__progressbar-wrapper .pa-woo-mc__subtotal-placeholder'] = '<span class="pa-woo-mc__subtotal-placeholder" style="display:none">' . $raw_subtotal_amount . '</span>';

		$fragments['.pa-woo-mc__outer-container .pa-woo-mc__badge:not(.pa-has-txt, .pa-counting)'] = '<span class="pa-woo-mc__badge ' . $empty_count_cls . '">' . $product_count . '</span>';
		$fragments['.pa-woo-mc__outer-container .pa-woo-mc__badge.pa-has-txt:not(.pa-counting)']   = '<span class="pa-woo-mc__badge pa-has-txt ' . $empty_count_cls . '">' . $product_count . '<span class="pa-woo-mc__badge-txt">' . $count_txt . '</span></span>';

		$fragments['.pa-woo-mc__cart-footer .pa-woo-mc__cart-count'] = '<span class="pa-woo-mc__cart-count">' . $product_count . '</span>';
		$fragments['.pa-woo-mc__cart-header .pa-woo-mc__cart-count'] = '<span class="pa-woo-mc__cart-count">' . $product_count . '</span>';

		$fragments['.pa-woo-mc__outer-container .pa-woo-mc__text-wrapper .pa-woo-mc__subtotal:not(.pa-counting)'] = '<span class="pa-woo-mc__subtotal">' . $subtotal . '</span>';

		$fragments['.pa-woo-mc__cart-footer .pa-woo-mc__subtotal'] = '<span class="pa-woo-mc__subtotal">' . $subtotal . '</span>';

		return $fragments;
	}

	/**
	 * Update a mini cart item's quantity.
	 */
	public function pa_update_mc_qty() {

		check_ajax_referer( 'pa-mini-cart-nonce', 'nonce' );

		if ( ! isset( $_POST['itemKey'] ) || ! isset( $_POST['quantity'] ) ) {
			return;
		}

		$item_key = sanitize_text_field( wp_unslash( $_POST['itemKey'] ) );
		$quantity = absint( wp_unslash( $_POST['quantity'] ) );

		if ( $quantity > 0 && WC()->cart->get_cart_item( $_POST['itemKey'] ) ) {
			WC()->cart->set_quantity( $_POST['itemKey'], $_POST['quantity'], true );
		}

		\WC_AJAX::get_refreshed_fragments();

		wp_send_json_success();
	}

	/**
	 * Delete a cart item by item key.
	 */
	public function pa_delete_cart_item() {

		check_ajax_referer( 'pa-mini-cart-nonce', 'nonce' );

		if ( ! isset( $_POST['itemKey'] ) ) {
			return;
		}

		$item_key = sanitize_text_field( $_POST['itemKey'] );

		if ( WC()->cart->get_cart_item( $_POST['itemKey'] ) ) {
			WC()->cart->remove_cart_item( $_POST['itemKey'] );
		}

		\WC_AJAX::get_refreshed_fragments();

		wp_send_json_success();
	}

	/**
	 * Removes all the items from the cart.
	 */
	public function pa_delete_cart_items() {

		check_ajax_referer( 'pa-mini-cart-nonce', 'nonce' );

		WC()->cart->empty_cart();

		\WC_AJAX::get_refreshed_fragments();

		wp_send_json_success();
	}

		/**
		 * Delete a cart item by item key.
		 */
	public function pa_apply_coupon() {

		check_ajax_referer( 'pa-mini-cart-nonce', 'nonce' );

		if ( empty( $_POST['couponCode'] ) ) {
			return;
		}

		$coupon_code = sanitize_text_field( $_POST['couponCode'] );

		$coupon = new \WC_Coupon( $coupon_code );

		if ( $coupon->is_valid() ) {

			if ( ! WC()->cart->has_discount( $coupon_code ) ) {

				WC()->cart->apply_coupon( $coupon_code );

				wp_send_json_success( 'Coupon was applied successfully.' );

				\WC_AJAX::get_refreshed_fragments();

			} else {
				wp_send_json_error( 'This code was already applied.', 409 );
			}
		} else {
			wp_send_json_error( 'Invalid Coupon!', 422 );
		}
	}

	public function pa_remove_coupon() {
		check_ajax_referer( 'pa-mini-cart-nonce', 'nonce' );

		if ( ! isset( $_POST['couponCode'] ) ) {
			wp_send_json_error( 'No coupon code provided.', 400 );
		}

		$coupon_code = sanitize_text_field( $_POST['couponCode'] );

		if ( WC()->cart->has_discount( $coupon_code ) ) {

			WC()->cart->remove_coupon( $coupon_code );

			\WC_AJAX::get_refreshed_fragments();

		} else {
			wp_send_json_error( 'Coupon not found or already removed.', 404 );
		}
	}
}
