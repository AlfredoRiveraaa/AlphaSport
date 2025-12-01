<?php

use PremiumAddons\Includes\Helper_Functions;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

add_action(
	'in_admin_header',
	function () {

		if ( Helper_Functions::check_papro_version() || time() > strtotime( '09:59:59pm 8th December, 2025' ) || ( $GLOBALS["pagenow"] !== 'index.php' && get_current_screen()->id !== 'toplevel_page_premium-addons' ) || get_transient( 'pa_cm25_pointer_dismiss' ) ) {
			return;
		}

		wp_enqueue_script( 'jquery' );
		wp_enqueue_style( 'wp-pointer' );
		wp_enqueue_script( 'wp-pointer' );

		$pointer_priority = get_option( '_pa_plugin_pointer_priority' );

		if ( empty( $pointer_priority ) || $pointer_priority > 1 ) {
			update_option( '_pa_plugin_pointer_priority', 1 );
			$pointer_priority = 1;
		}

		if ( absint( $pointer_priority ) === 1 ) {
			?>
			<script>
                jQuery(
                    function () {
                        jQuery('#toplevel_page_premium-addons').pointer(
                            {
                                content:
                                    "<h3 style='font-weight: 600;'>Premium Addons: Cyber Monday</h3>" +
                                    "<p style='margin: 1em 0;'>Unlock the full power of Elementor with 90+ advanced elements and 580+ templates. Build smarter and faster.</p>" +
                                    "<p><a class='button button-primary' href='https://premiumaddons.com/black-friday/#bfdeals' target='_blank'>Save $105 Now</a></p>",

                                position:
                                    {
                                        edge: 'left',
                                        align: 'center'
                                    },

                                pointerClass:
                                    'wp-pointer',

                                close: function () {
                                    jQuery.post(
                                        ajaxurl,
                                        {
                                            pointer: 'pa',
                                            action: 'dismiss-wp-pointer',
                                        }
                                    );
                                },

                            }
                        ).pointer('open');
                    }
                );
			</script>
			<?php
		}
	}
);

add_action(
	'admin_init',
	function () {
		if ( isset( $_POST['action'] ) && 'dismiss-wp-pointer' == $_POST['action'] && isset( $_POST['pointer'] ) && 'pa' == $_POST['pointer'] ) {
			set_transient( 'pa_cm25_pointer_dismiss', true, DAY_IN_SECONDS * 30 );
			delete_option( '_pa_plugin_pointer_priority' );
		}
	}
);
