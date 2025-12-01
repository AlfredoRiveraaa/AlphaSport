<?php

	/**
	 * PA Setup Wizard Community View.
	 *
	 * @package Setup Wizard.
	 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$user      = wp_get_current_user();
$user_mail = isset( $user->user_email ) ? $user->user_email : '';
?>
	<span class="pa-welcome-msg-wrapper pa-wz-flex pa-wz-flex-d-col pa-wz-justify-content-center">
		<span class="pa-welcome-msg-primary"><?php echo esc_html__( 'Be Part of Premium Addons Community!', 'premium-addons-for-elementor' ); ?></span>
		<span class="pa-welcome-msg-secondary"><?php echo esc_html__( 'Stay connected, get exclusive tips, and be the first to know about new features.', 'premium-addons-for-elementor' ); ?></span>
	</span>

	<div class="pa-wz-commnunity-outer-wrapper pa-wz-flex">
		<div class="pa-wz-subscribe-wrapper pa-wz-flex pa-wz-justify-content-between pa-wz-sc-block">
			<div class="pa-wz-sb-inner-wrapper">
				<div class="pa-wz-sb-heading pa-wz-flex">
					<svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" width="24.36" height="15.83" viewBox="0 0 24.36 15.83"><defs><style>.pa-evelope-1{fill:#EF9800;}</style></defs><g id="Envelope"><path class="pa-evelope-1" d="M24.2,1.01l-8.83,6.51,8.09,8.09c.56-.31.9-.89.9-1.53V1.74c0-.25-.05-.5-.16-.73ZM14.66,8.04c-.7.57-1.55,1.13-2.48,1.08-.92.04-1.78-.51-2.47-1.09-.42.41-7.69,7.69-7.8,7.8h20.52c-.09-.09-7.38-7.38-7.79-7.79Z"/><path class="pa-evelope-1" d="M10.88,7.83c.77.57,1.83.57,2.61,0L23.65.34C23.35.12,22.99,0,22.62,0H1.74c-.29,0-.58.07-.84.22l9.98,7.61ZM.26.82C.09,1.1,0,1.42,0,1.74v12.35c0,.64.35,1.22.9,1.53L9.02,7.5.26.82Z"/></g></svg>
					<div class="pa-wz-sb-heading-inner pa-wz-flex pa-wz-flex-d-col">
						<span class="pa-wz-title-primary"><?php echo esc_html__( 'Join Our Newsletter', 'premium-addons-for-elementor' ); ?></span>
						<span class="pa-wz-title-secondary"><?php echo __( 'Learn about our latest updates and special offers!', 'premium-addons-for-elementor' ); ?></span>
					</div>
				</div>

				<form class="pa-newsletter-form pa-wizard-form pa-wz-flex-d-col">
					<input id="pa_news_email" type="email" placeholder="<?php esc_attr_e( 'Enter Your Email', 'premium-addons-for-elementor' ); ?>" value="<?php echo esc_attr( $user_mail ); ?>">
					<button type="submit" class="pa-btn pa-wz-flex pa-wz-justify-content-center pa-wz-align-items-center">
						<?php esc_html_e( 'Subscribe', 'premium-addons-for-elementor' ); ?>
						<svg class="pa-wz-news-svg" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" width="24" height="24" viewBox="0 0 24 24"><defs><style>.pa-wz-send-1{fill:#fff;}</style></defs><path id="Send" class="pa-wz-send-1" d="M13.95,22c.69,0,1.17-.59,1.53-1.51l6.25-16.33c.17-.44.27-.83.27-1.16,0-.62-.38-1-1-1-.32,0-.72.1-1.16.27L3.42,8.56c-.8.31-1.42.79-1.42,1.49,0,.88.67,1.17,1.58,1.45l5.16,1.57c.61.19.95.17,1.37-.21L20.58,3.07c.12-.11.27-.1.36,0,.1.1.1.24,0,.36l-9.75,10.51c-.37.39-.4.72-.22,1.36l1.52,5.04c.29.96.58,1.67,1.47,1.67Z"/></svg>
						<svg class="pa-wz-spinner" style="fill:#fff; display:none;" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" viewBox="0 0 512 512"><path d="M304 48a48 48 0 1 0 -96 0 48 48 0 1 0 96 0zm0 416a48 48 0 1 0 -96 0 48 48 0 1 0 96 0zM48 304a48 48 0 1 0 0-96 48 48 0 1 0 0 96zm464-48a48 48 0 1 0 -96 0 48 48 0 1 0 96 0zM142.9 437A48 48 0 1 0 75 369.1 48 48 0 1 0 142.9 437zm0-294.2A48 48 0 1 0 75 75a48 48 0 1 0 67.9 67.9zM369.1 437A48 48 0 1 0 437 369.1 48 48 0 1 0 369.1 437z"/></svg>
					</button>
				</form>
			</div>

			<!-- an image or doc links  -->
			<img src="<?php echo esc_url( 'https://premiumtemplates.io/wp-content/uploads/wizard/newsletter.png' ); ?>" alt="<?php echo esc_attr_e( 'newsletter', 'premium-addons-for-elementor' ); ?>">

		</div>

		<!-- Facebook -->
		<div class="pa-wz-social-wrapper pa-wz-sc-block pa-wz-fb pa-wz-flex pa-wz-flex-d-col">
			<svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" width="50" height="50" viewBox="0 0 24 24"><defs><style>.pa-wz-fb-1{fill:#1877f2;}</style></defs><path  class="pa-wz-fb-1" d="M22.21,12.06c0-5.64-4.57-10.21-10.21-10.21S1.79,6.42,1.79,12.06c0,5.1,3.73,9.32,8.61,10.09v-7.13h-2.59v-2.95h2.59v-2.25c0-2.56,1.52-3.97,3.86-3.97,1.12,0,2.29.2,2.29.2v2.51h-1.29c-1.27,0-1.66.79-1.66,1.59v1.92h2.83l-.45,2.95h-2.38v7.13c4.88-.77,8.61-4.99,8.61-10.09Z"/></svg>
			<h4><?php esc_html_e( 'Meet Like-Minded Creators', 'premium-addons-for-elementor' ); ?></h4>
			<a class="pa-btn pa-wz-flex" href="https://facebook.com/groups/PremiumAddons" target="_blank">
				<?php esc_html_e( 'Join Facebook Group', 'premium-addons-for-elementor' ); ?>
				<svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" width="14" height="11.99" viewBox="0 0 14 11.99"><defs><style>.pa-wz-fb-11{fill:#231f20;}</style></defs><path class="pa-wz-fb-11" d="M14,5.99c0-.53-.22-1.03-.59-1.4L9.12.29c-.19-.19-.44-.29-.7-.29s-.52.1-.7.29c-.09.09-.17.2-.22.33-.05.12-.08.25-.08.38s.03.26.08.38c.05.12.13.23.22.33l3.29,3.28H1c-.27,0-.52.11-.71.29-.19.19-.29.44-.29.71s.11.52.29.71c.19.19.44.29.71.29h10l-3.29,3.29c-.19.19-.29.44-.3.71,0,.27.1.52.29.71.19.19.44.29.71.3.27,0,.52-.1.71-.29l4.29-4.3c.38-.37.59-.88.59-1.41Z"/></svg>
			</a>
		</div>

		<!-- YouTube -->
		<div class="pa-wz-social-wrapper pa-wz-sc-block pa-wz-yt pa-wz-flex pa-wz-flex-d-col">
			<svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" width="50" height="50" viewBox="0 0 24.36 24"><defs><style>.pa-wz-yt-1{fill:#ce1312;fill-rule:evenodd;}</style></defs><path id="YouTube" class="pa-wz-yt-1" d="M9.66,15.18v-6.89s6.58,3.46,6.58,3.46l-6.58,3.43ZM24.12,7.09s-.24-1.69-.97-2.43c-.93-.98-1.96-.98-2.44-1.04-3.41-.25-8.52-.25-8.52-.25h-.01s-5.11,0-8.52.25c-.48.06-1.51.06-2.44,1.04-.73.74-.97,2.43-.97,2.43,0,0-.24,1.99-.24,3.97v1.86c0,1.99.24,3.97.24,3.97,0,0,.24,1.69.97,2.43.93.98,2.14.95,2.69,1.05,1.95.19,8.28.25,8.28.25,0,0,5.12,0,8.53-.26.48-.06,1.51-.06,2.44-1.04.73-.74.97-2.43.97-2.43,0,0,.24-1.98.24-3.97v-1.86c0-1.98-.24-3.97-.24-3.97Z"/></svg>
			<h4><?php esc_html_e( 'Watch & Learn', 'premium-addons-for-elementor' ); ?></h4>
			<a class="pa-btn pa-wz-flex" href="https://www.youtube.com/channel/UCXcJ9BeO2sKKHor7Q9VglTQ?sub_confirmation=1" target="_blank">
				<?php esc_html_e( 'Subscribe', 'premium-addons-for-elementor' ); ?>
				<svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" width="14" height="11.99" viewBox="0 0 14 11.99"><defs><style>.pa-wz-yt-11{fill:#231f20;}</style></defs><path class="pa-wz-yt-11" d="M14,5.99c0-.53-.22-1.03-.59-1.4L9.12.29c-.19-.19-.44-.29-.7-.29s-.52.1-.7.29c-.09.09-.17.2-.22.33-.05.12-.08.25-.08.38s.03.26.08.38c.05.12.13.23.22.33l3.29,3.28H1c-.27,0-.52.11-.71.29-.19.19-.29.44-.29.71s.11.52.29.71c.19.19.44.29.71.29h10l-3.29,3.29c-.19.19-.29.44-.3.71,0,.27.1.52.29.71.19.19.44.29.71.3.27,0,.52-.1.71-.29l4.29-4.3c.38-.37.59-.88.59-1.41Z"/></svg>
			</a>
		</div>
	</div>
