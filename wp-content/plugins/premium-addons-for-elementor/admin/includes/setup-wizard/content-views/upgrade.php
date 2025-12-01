<?php

	/**
	 * PA Setup Wizard Upgrade View.
	 *
	 * @package Setup Wizard.
	 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use PremiumAddons\Includes\Helper_Functions;

$pro_elements = array(
	array(
		'icon'  => 'pa-dash-premium-hscroll',
		'title' => __( 'Horizontal Scroll', 'premium-addons-for-elementor' ),
		'demo'  => Helper_Functions::get_campaign_link( 'https://premiumaddons.com/elementor-horizontal-scroll-widget/', 'hscroll', 'wp-dash', 'wizard' ),
	),
	array(
		'icon'  => 'premium-mscroll',
		'title' => __( 'Magic Scroll', 'premium-addons-for-elementor' ),
		'demo'  => Helper_Functions::get_campaign_link( 'https://premiumaddons.com/elementor-magic-scroll-global-addon/', 'magic', 'wp-dash', 'wizard' ),
	),
	array(
		'icon'  => 'pa-dash-premium-vscroll',
		'title' => __( 'Vertical Scroll', 'premium-addons-for-elementor' ),
		'demo'  => Helper_Functions::get_campaign_link( 'https://premiumaddons.com/vertical-scroll-widget-for-elementor-page-builder/', 'vscroll', 'wp-dash', 'wizard' ),
	),
	array(
		'icon'  => 'pa-dash-premium-multi-scroll',
		'title' => __( 'Multi Scroll', 'premium-addons-for-elementor' ),
		'demo'  => Helper_Functions::get_campaign_link( 'https://premiumaddons.com/multi-scroll-widget-for-elementor-page-builder/', 'mscroll', 'wp-dash', 'wizard' ),
	),
	array(
		'icon'  => 'pa-dash-premium-color-transition',
		'title' => __( 'Background Transition', 'premium-addons-for-elementor' ),
		'demo'  => Helper_Functions::get_campaign_link( 'https://premiumaddons.com/elementor-background-transition-widget/', 'background-transition', 'wp-dash', 'wizard' ),
	),
	array(
		'icon'  => 'pa-dash-premium-smart-post-listing',
		'title' => __( 'Smart Post Listing', 'premium-addons-for-elementor' ),
		'demo'  => Helper_Functions::get_campaign_link( 'https://premiumaddons.com/elementor-smart-post-listing-widget/', 'spl', 'wp-dash', 'wizard' ),
	),
	array(
		'title' => __( 'Facebook Reviews', 'premium-addons-for-elementor' ),
		'demo'  => Helper_Functions::get_campaign_link( 'https://premiumaddons.com/elementor-facebook-reviews-widget/', 'fb-reviews', 'wp-dash', 'wizard' ),
		'icon'  => 'pa-dash-premium-facebook-reviews',
	),
	array(
		'title' => __( 'Google Reviews', 'premium-addons-for-elementor' ),
		'demo'  => Helper_Functions::get_campaign_link( 'https://premiumaddons.com/elementor-google-reviews-widget/', 'google-reviews', 'wp-dash', 'wizard' ),
		'icon'  => 'pa-dash-premium-google-reviews',
	),
	array(
		'title' => __( 'Off Canvas', 'premium-addons-for-elementor' ),
		'demo'  => Helper_Functions::get_campaign_link( 'https://premiumaddons.com/elementor-off-canvas-widget/', 'off-canvas', 'wp-dash', 'wizard' ),
		'icon'  => 'pa-dash-premium-magic-section',
	),
	array(
		'title' => __( 'Tabs', 'premium-addons-for-elementor' ),
		'demo'  => Helper_Functions::get_campaign_link( 'https://premiumaddons.com/elementor-tabs-widget/', 'tabs', 'wp-dash', 'wizard' ),
		'icon'  => 'pa-dash-premium-tabs',
	),
);
?>


	<div class="pa-wz-upgrade-content-wrapper pa-wz-flex pa-wz-flex-d-col pa-wz-align-items-center pa-wz-justify-content-center">
		<div class="pa-wz-upgrade-intro pa-wz-flex pa-wz-flex-d-col pa-wz-align-items-center pa-wz-justify-content-center">
			<span class="pa-wz-upgrade-msg"><?php echo esc_html_e( 'Upgrade to', 'premium-addons-for-elementor' ); ?></span>
			<img src="<?php echo esc_url( 'https://premiumtemplates.io/wp-content/uploads/wizard/premium-addons-pro-retina.webp' ); ?>" alt="<?php echo esc_attr_e( 'get pro', 'premium-addons-for-elementor' ); ?>">
		</div>
		<div class="pa-wz-upgrade-list pa-wz-flex">
			<div class="pa-wz-inner-list pa-wz-flex pa-wz-flex-d-col">
				<div class="pa-wz-upgrade-item pa-wz-flex pa-wz-align-items-center"><svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" width="28.15" height="23.19" viewBox="0 0 28.15 23.19"><defs><style>.pa-check-1{fill:#231f20;}</style></defs><polygon class="pa-check-1" points="8.35 23.19 0 15.08 3.33 11.64 8.3 16.47 24.77 0 28.15 3.39 8.35 23.19"/></svg></span><?php echo esc_html__( '90+ Widgets & 580+ Templates', 'premium-addons-for-elementor' ); ?></div>
				<div class="pa-wz-upgrade-item pa-wz-flex pa-wz-align-items-center"><svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" width="28.15" height="23.19" viewBox="0 0 28.15 23.19"><defs><style>.pa-check-1{fill:#231f20;}</style></defs><polygon class="pa-check-1" points="8.35 23.19 0 15.08 3.33 11.64 8.3 16.47 24.77 0 28.15 3.39 8.35 23.19"/></svg></span><?php echo esc_html__( '700K+ Active Installs', 'premium-addons-for-elementor' ); ?></div>
				<div class="pa-wz-upgrade-item pa-wz-flex pa-wz-align-items-center"><svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" width="28.15" height="23.19" viewBox="0 0 28.15 23.19"><defs><style>.pa-check-1{fill:#231f20;}</style></defs><polygon class="pa-check-1" points="8.35 23.19 0 15.08 3.33 11.64 8.3 16.47 24.77 0 28.15 3.39 8.35 23.19"/></svg></span><?php echo esc_html__( '5-Star Customer Reviews', 'premium-addons-for-elementor' ); ?></div>
			</div>
			<div class="pa-wz-inner-list pa-wz-flex pa-wz-flex-d-col">
				<div class="pa-wz-upgrade-item pa-wz-flex pa-wz-align-items-center"><svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" width="28.15" height="23.19" viewBox="0 0 28.15 23.19"><defs><style>.pa-check-1{fill:#231f20;}</style></defs><polygon class="pa-check-1" points="8.35 23.19 0 15.08 3.33 11.64 8.3 16.47 24.77 0 28.15 3.39 8.35 23.19"/></svg></span><?php echo esc_html__( 'Documentations & Video Tutorials', 'premium-addons-for-elementor' ); ?></div>
				<div class="pa-wz-upgrade-item pa-wz-flex pa-wz-align-items-center"><svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" width="28.15" height="23.19" viewBox="0 0 28.15 23.19"><defs><style>.pa-check-1{fill:#231f20;}</style></defs><polygon class="pa-check-1" points="8.35 23.19 0 15.08 3.33 11.64 8.3 16.47 24.77 0 28.15 3.39 8.35 23.19"/></svg></span><?php echo esc_html__( 'Active Community & Support', 'premium-addons-for-elementor' ); ?></div>
				<div class="pa-wz-upgrade-item pa-wz-flex pa-wz-align-items-center"><svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" width="28.15" height="23.19" viewBox="0 0 28.15 23.19"><defs><style>.pa-check-1{fill:#231f20;}</style></defs><polygon class="pa-check-1" points="8.35 23.19 0 15.08 3.33 11.64 8.3 16.47 24.77 0 28.15 3.39 8.35 23.19"/></svg></span><?php echo esc_html__( '14 Days Money Back Guarantee', 'premium-addons-for-elementor' ); ?></div>
			</div>
		</div>
		<div class="pa-wz-ticker-outer-wrapper pa-wz-flex pa-wz-flex-d-col pa-wz-align-items-center pa-wz-justify-content-center">
			<span class="pa-wz-ticker-upgrade-heading"><?php echo esc_html__( 'Unique PRO Widgets', 'premium-addons-for-elementor' ); ?></span>
			<div  class="pa-wz-ticker-container pa-wz-flex">
				<div class="pa-wz-ticker-inner-container pa-wz-flex pa-wz-align-items-center">
					<?php
					for ( $i = 0; $i < 2; $i++ ) {
						foreach ( $pro_elements as $elem ) {
							?>
									<div class="pa-wz-ticker-item pa-wz-flex pa-wz-align-items-center">
									<?php if ( 'premium-mscroll' === $elem['icon'] ) { ?>
											<svg id="Icons" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" width="16.5" height="21.42" viewBox="0 0 16.5 21.42"><defs><style>.pa-mg-scroll-1{fill:##3c434a;}</style></defs><title>premium-magic-scroll</title><g id="Magic_Scroll" data-name="Magic Scroll"><path class="pa-mg-scroll-1" d="M16.74,23.21a3.21,3.21,0,0,1-1.64-.53l-2.24-1.33a.86.86,0,0,0-.72,0L9.9,22.68c-1.42.84-2.28.5-2.66.21s-1-1-.61-2.6L7.16,18a.92.92,0,0,0-.2-.67L5.11,15.46a2.33,2.33,0,0,1-.78-2.31,2.31,2.31,0,0,1,2-1.42l2.39-.4A.93.93,0,0,0,9.21,11l1.32-2.64a2.32,2.32,0,0,1,2-1.48,2.32,2.32,0,0,1,2,1.48L15.79,11a.94.94,0,0,0,.5.38l2.4.4a2.25,2.25,0,0,1,2,1.43,2.24,2.24,0,0,1-.78,2.3L18,17.32a.92.92,0,0,0-.19.66l.53,2.31c.37,1.61-.22,2.32-.61,2.6A1.68,1.68,0,0,1,16.74,23.21ZM12.5,19.78a2.24,2.24,0,0,1,1.12.28l2.24,1.33c.59.35.94.35,1,.29s.18-.39,0-1.05l-.53-2.31A2.36,2.36,0,0,1,17,16.26l1.86-1.86c.37-.37.45-.65.41-.78s-.28-.32-.8-.41l-2.38-.4a2.35,2.35,0,0,1-1.61-1.19L13.13,9h0c-.23-.45-.48-.65-.63-.65s-.4.2-.63.65l-1.32,2.64A2.35,2.35,0,0,1,9,12.81l-2.4.4c-.51.08-.74.27-.79.4s0,.42.41.79L8,16.26a2.38,2.38,0,0,1,.59,2.06l-.53,2.31c-.15.66-.05,1,0,1.05s.43.06,1-.29l2.24-1.33A2.22,2.22,0,0,1,12.5,19.78Z" transform="translate(-4.25 -1.79)"/><path class="pa-mg-scroll-1" d="M6.5,10.29a.76.76,0,0,1-.75-.75v-7a.75.75,0,1,1,1.5,0v7A.76.76,0,0,1,6.5,10.29Z" transform="translate(-4.25 -1.79)"/><path class="pa-mg-scroll-1" d="M18.5,10.29a.76.76,0,0,1-.75-.75v-7a.75.75,0,1,1,1.5,0v7A.76.76,0,0,1,18.5,10.29Z" transform="translate(-4.25 -1.79)"/><path class="pa-mg-scroll-1" d="M12.5,5.29a.76.76,0,0,1-.75-.75v-2a.75.75,0,1,1,1.5,0v2A.76.76,0,0,1,12.5,5.29Z" transform="translate(-4.25 -1.79)"/></g></svg>
										<?php } else { ?>
											<i class="<?php echo esc_attr( $elem['icon'] ); ?> pa-element-icon"></i>
										<?php } ?>
										<a href="<?php echo esc_url( $elem['demo'] ); ?>" target="_blank"><span><?php echo esc_html( $elem['title'] ); ?></span></a>
									</div>
								<?php
						}
					}
					?>
				</div>
			</div>
			<span class="pa-wz-ticker-sub-heading"><?php echo esc_html__( 'STARTING FROM', 'premium-addons-for-elementor' ) . wp_kses_post( ' <b>$39 </b>' ); ?>
				<span class="papro-sale-notice"><?php echo esc_html__( '( UP TO $105 OFF! )', 'premium-addons-for-elementor' ); ?></span>
			</span>
			<a class="pa-upgrade-btn" href="<?php echo esc_url( Helper_Functions::get_campaign_link( 'https://premiumaddons.com/pro/#get-pa-pro', 'wizard', 'wp-dash', 'wizard' ) ); ?>" target="_blank" aria-label="<?php echo esc_attr_e( 'upgrade now.', 'premium-addons-for-elementor' ); ?>"><?php echo esc_html_e( 'Upgrade Now', 'premium-addons-for-elementor' ); ?></a>
			<a class="next-arrow pa-wz-free-user" type="button" role="button" aria-label="<?php echo esc_attr_e( 'maybe later..', 'premium-addons-for-elementor' ); ?>" pa-step-id="3"><?php echo esc_html_e( 'maybe later..', 'premium-addons-for-elementor' ); ?></a>
		</div>
	</div>

