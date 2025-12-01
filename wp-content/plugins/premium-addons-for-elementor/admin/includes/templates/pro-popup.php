<?php

/**
 * PA Dashboard PRO Popup.
 *
 * @package Templates
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div id="pa-dash-pro-popup-cta" style="display:none;">
	<div class="pa-popup-outer-body">
		<div class="popup-body">
			<span class="pa-popup-close">
				<svg class="pa-popup-close-icon" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" width="24.5" height="24" viewBox="0 0 24.5 24"><defs><style>.pa-popup-close-icon-1{fill:#979797;fill-rule:evenodd;}</style></defs><path id="Close_Bold" class="pa-popup-close-icon-1" d="M12.5,14.12l5.3,5.3c.59.59,1.54.59,2.12,0s.59-1.54,0-2.12l-5.3-5.3,5.3-5.3c.59-.59.59-1.54,0-2.12-.59-.59-1.54-.59-2.12,0l-5.3,5.3-5.3-5.3c-.59-.59-1.54-.59-2.12,0-.59.59-.59,1.54,0,2.12l5.3,5.3-5.3,5.3c-.59.59-.59,1.54,0,2.12.59.59,1.54.59,2.12,0l5.3-5.3Z"/></svg>
			</span>
			<span class="pa-popup-widget-icon">
				<span class="pa-popup-badge-wrapper">
					<span class="pa-popup-badge">PRO</span>
				</span>
				<i class=""></i>
				<img style="display:none" src="<?php echo esc_attr( PREMIUM_ADDONS_URL . 'admin/images/pa-logo-symbol.png' ); ?>" alt="Premium Addons">
			</span>
			<span class="pa-popup-desc">
				<span class="primary-des">
					<span class="pa-widget-name"></span>
					<?php esc_html_e( ' requires', 'premium-addons-for-elementor' ); ?>
				</span>
				<span class="secondary-des"><?php esc_html_e( 'Premium Addons PRO', 'premium-addons-for-elementor' ); ?></span>
			</span>
			<div class="pa-popup-cta-wrapper">
				<a class="pa-popup-cta" target="_blank" aria-label="<?php echo esc_attr_e( 'check features', 'premium-addons-for-elementor' ); ?>"><?php echo esc_html_e( 'CHECK FEATURES', 'premium-addons-for-elementor' ); ?></a>
				<span class="pa-popup-separator"></span>
				<a class="pa-popup-cta" target="_blank" aria-label="<?php echo esc_attr_e( 'upgrade now', 'premium-addons-for-elementor' ); ?>"><?php echo esc_html_e( 'Go PRO (SAVE $105)', 'premium-addons-for-elementor' ); ?></a>
			</div>
		</div>
	</div>
</div>
