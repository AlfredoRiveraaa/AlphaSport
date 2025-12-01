<?php

	/**
	 * PA Setup Wizard Welcome View.
	 *
	 * @package Setup Wizard.
	 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

	$site_types = array(
		'Personal/Company Website'    => array(
			'icon'    => '<svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" width="24" height="24" viewBox="0 0 24 24"><defs><style>.pa-wz-bag-1{fill:none;stroke:#84BC00;stroke-linecap:round;stroke-linejoin:round;stroke-width:1.5px;}</style></defs><g id="Bag"><path class="pa-wz-bag-1" d="M21.2,12.07l1.16,4.89c.18.75.18,1.54,0,2.29-.17.75-.51,1.46-.99,2.07-.48.6-1.1,1.09-1.79,1.43-.7.33-1.46.51-2.24.5H6.66c-.77,0-1.54-.17-2.24-.5-.7-.33-1.31-.82-1.79-1.43-.48-.61-.82-1.31-.99-2.07-.17-.76-.17-1.54,0-2.29l1.16-4.89"/><path class="pa-wz-bag-1" d="M9.75,14.25h-3.17c-1.14.01-2.23-.43-3.05-1.23-.81-.79-1.28-1.88-1.29-3.02v-3.26c0-.4.16-.78.44-1.06.28-.28.66-.44,1.06-.44h16.5c.4,0,.78.16,1.06.44s.44.66.44,1.06v3.26c-.01,1.14-.48,2.22-1.29,3.02-.81.8-1.91,1.24-3.05,1.23h-3.17"/><path class="pa-wz-bag-1" d="M9.75,14.25c0,.6.24,1.17.66,1.59.42.42.99.66,1.59.66s1.17-.24,1.59-.66c.42-.42.66-.99.66-1.59s-.24-1.17-.66-1.59c-.42-.42-.99-.66-1.59-.66s-1.17.24-1.59.66c-.42.42-.66.99-.66,1.59Z"/><path class="pa-wz-bag-1" d="M10.25.75h3.5c.53,0,1.04.21,1.41.59.38.38.59.88.59,1.41v2.5h-7.5v-2.5c0-.53.21-1.04.59-1.41.38-.38.88-.59,1.41-.59Z"/></g></svg>',
			'value'   => 'basic',
			'tooltip' => 'Ideal widgets for portfolios, business sites, and personal branding.',
		),
		'e-Commerce Website'          => array(
			'icon'    => '<svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" width="24" height="24" viewBox="0 0 24 24"><defs><style>.pa-wz-shop-1{fill:none;stroke:#84BC00;stroke-linecap:round;stroke-linejoin:round;stroke-width:1.5px;}</style></defs><g id="eStore"><path class="pa-wz-shop-1" d="M20.25,11.75v2.5c0,.4-.16.78-.44,1.06-.28.28-.66.44-1.06.44H5.25c-.4,0-.78-.16-1.06-.44-.28-.28-.44-.66-.44-1.06v-2.5"/><path class="pa-wz-shop-1" d="M21.75,23.25H2.25c-.26,0-.51-.07-.73-.19-.22-.12-.41-.3-.54-.52-.13-.22-.21-.46-.22-.72-.01-.26.04-.51.16-.74l1.84-3.67c.25-.5.63-.92,1.1-1.21.47-.29,1.02-.45,1.58-.45h13.14c.56,0,1.1.16,1.58.45.47.29.86.71,1.1,1.21l1.84,3.67c.11.23.17.48.16.74-.01.26-.09.5-.22.72-.13.22-.32.4-.54.52-.22.12-.48.19-.73.19Z"/><path class="pa-wz-shop-1" d="M10.5,20.25h3"/><path class="pa-wz-shop-1" d="M.75,6c.04.72.36,1.39.88,1.88.52.49,1.21.77,1.93.77s1.41-.28,1.93-.77c.52-.49.84-1.17.88-1.88,0,.74.3,1.46.82,1.99.53.53,1.24.82,1.99.82s1.46-.3,1.99-.82c.53-.53.82-1.24.82-1.99.04.72.36,1.39.88,1.88.52.49,1.21.77,1.93.77s1.41-.28,1.93-.77c.52-.49.84-1.17.88-1.88,0,.74.3,1.46.82,1.99.53.53,1.24.82,1.99.82s1.46-.3,1.99-.82c.53-.53.82-1.24.82-1.99l-1.37-4.66c-.04-.17-.13-.32-.26-.42-.13-.11-.3-.17-.47-.17H2.85c-.17,0-.34.06-.47.17-.13.11-.23.26-.26.42L.75,6Z"/></g></svg>',
			'value'   => 'ecommerce',
			'tooltip' => 'Well-picked widgets for selling products with WooCommerce integration.',
		),
		'Blog/Magazine Website'       => array(
			'icon'    => '<svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" width="24" height="24" viewBox="0 0 24 24"><defs><style>.pa-wz-mag-1{fill:none;stroke:#84BC00;stroke-linecap:round;stroke-linejoin:round;stroke-width:1.5px;}</style></defs><g id="Newspaper"><path class="pa-wz-mag-1" d="M12,4.57C8.76,2.59,5.08,1.46,1.29,1.29c-.07,0-.14,0-.2.03-.06.02-.12.06-.17.11-.05.05-.09.1-.12.17-.03.06-.04.13-.04.2v16.74c0,.13.05.26.14.35.09.1.22.15.35.16,1.72-.24,3.47-.14,5.15.29,1.68.44,3.25,1.21,4.63,2.26.31.13.65.17.98.13"/><path class="pa-wz-mag-1" d="M12,4.57c3.24-1.98,6.92-3.11,10.72-3.28.07,0,.14,0,.2.03.06.02.12.06.17.11.05.05.09.1.12.17.03.06.04.13.04.2v16.74c0,.13-.05.26-.14.35s-.22.15-.35.16c-1.72-.24-3.47-.14-5.15.29-1.68.44-3.25,1.21-4.63,2.26-.31.13-.65.17-.98.13"/><path class="pa-wz-mag-1" d="M12,4.57v17.19"/><path class="pa-wz-mag-1" d="M2.25,18.97v3.23c0,.08.02.15.05.22.03.07.08.13.14.18s.13.08.2.1.15.02.23,0c3-.63,6.06-.94,9.13-.94,2.89,0,5.76.31,8.58.94.07.02.15.02.23,0,.07-.02.14-.05.2-.1.06-.05.11-.11.14-.18.03-.07.05-.14.05-.22v-3.24"/></g></svg>',
			'value'   => 'blog',
			'tooltip' => 'Powerful widgets for sharing articles, news, and informative content.',
		),
		'Let Me Customize Everything' => array(
			'icon'    => '<svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" width="24" height="24" viewBox="0 0 24 24"><defs><style>.pa-wz-cog-1{stroke-linecap:round;stroke-linejoin:round;}.pa-wz-cog-1,.pa-wz-cog-2{fill:none;stroke:#84BC00;stroke-width:1.5px;}</style></defs><g id="Controls"><path class="pa-wz-cog-1" d="M5.08,12H1.26"/><path class="pa-wz-cog-1" d="M22.74,12h-12.73"/><path class="pa-wz-cog-1" d="M13.99,20.12H1.26"/><path class="pa-wz-cog-1" d="M22.74,20.12h-3.82"/><path class="pa-wz-cog-1" d="M13.99,4.02H1.26"/><path class="pa-wz-cog-1" d="M22.74,4.02h-3.82"/><path class="pa-wz-cog-2" d="M5.08,12c0,.32.06.64.19.94.12.3.31.57.53.8.23.23.5.41.8.53.3.12.62.19.94.19s.64-.06.94-.19c.3-.12.57-.31.8-.53.23-.23.41-.5.53-.8.12-.3.19-.62.19-.94s-.06-.64-.19-.94c-.12-.3-.31-.57-.53-.8-.23-.23-.5-.41-.8-.53-.3-.12-.62-.19-.94-.19s-.64.06-.94.19c-.3.12-.57.31-.8.53-.23.23-.41.5-.53.8-.12.3-.19.62-.19.94Z"/><path class="pa-wz-cog-2" d="M13.99,20.12c0,.65.26,1.28.72,1.74.46.46,1.09.72,1.74.72s1.28-.26,1.74-.72c.46-.46.72-1.09.72-1.74s-.26-1.28-.72-1.74-1.09-.72-1.74-.72-1.28.26-1.74.72c-.46.46-.72,1.09-.72,1.74Z"/><path class="pa-wz-cog-2" d="M13.99,3.88c0,.65.26,1.28.72,1.74.46.46,1.09.72,1.74.72s1.28-.26,1.74-.72c.46-.46.72-1.09.72-1.74s-.26-1.28-.72-1.74c-.46-.46-1.09-.72-1.74-.72s-1.28.26-1.74.72c-.46.46-.72,1.09-.72,1.74Z"/></g></svg>',
			'value'   => 'custom',
			'tooltip' => 'Want full control? Pick and choose exactly what you need.',
		),
	);

	?>

	<span class="pa-welcome-msg-wrapper pa-wz-flex pa-wz-flex-d-col pa-wz-justify-content-center">
		<span class="pa-welcome-msg-primary"><?php echo esc_html__( 'Let\'s Tailor Premium Addons for Your Needs.', 'premium-addons-for-elementor' ); ?></span>
		<span class="pa-welcome-msg-secondary"><?php echo esc_html__( 'What kind of websites are your creating?', 'premium-addons-for-elementor' ); ?></span>
	</span>

	<div class="pa-wz-options-wrapper pa-wz-flex pa-wz-flex-d-col">
		<?php
		foreach ( $site_types as $key => $site ) {
			?>
				<label class="pa-wz-option-wrapper pa-wz-flex pa-wz-align-items-center 
				<?php
				if ( 'basic' === $site['value'] ) {
					echo esc_attr( 'pa-step-active' ); }
				?>
				">
					<span class="pa-site-icon-wrapper">
					<?php echo $site['icon']; ?>
					</span>
					<span class="pa-wz-separator"></span>
					<span class="pa-site-title-wrapper">
						<span class="pa-wz-site-title-txt">
						<?php printf( __( '%1$s', 'premium-addons-for-elementor' ), esc_html( $key ) ); ?>
						</span>
					</span>
					<span class="pa-tooltip-holder pa-wz-flex">
						<svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" width="16.93" height="16.93" viewBox="0 0 16.93 16.93"><defs><style>.pa-site-info-1{fill:#7C7C7C;}</style></defs><path class="pa-site-info-1" d="M8.47,16.93c4.63,0,8.47-3.84,8.47-8.47S13.09,0,8.46,0,0,3.83,0,8.47s3.84,8.47,8.47,8.47ZM8.47,15.52c-3.92,0-7.05-3.14-7.05-7.06S4.54,1.41,8.46,1.41s7.06,3.14,7.06,7.06-3.14,7.06-7.06,7.06ZM8.39,5.59c.61,0,1.08-.48,1.08-1.09s-.47-1.09-1.08-1.09-1.08.48-1.08,1.09.48,1.09,1.08,1.09ZM7.01,13.12h3.38c.34,0,.61-.25.61-.59s-.27-.58-.61-.58h-1.03v-4.23c0-.45-.22-.75-.65-.75h-1.56c-.34,0-.61.26-.61.58,0,.34.27.59.61.59h.89v3.8h-1.03c-.34,0-.61.26-.61.58,0,.34.27.59.61.59Z"/></svg>
						<span class="pa-site-tooltip-content pa-wz-flex pa-wz-align-items-center pa-wz-justify-content-center">
							<span><?php printf( __( '%1$s', 'premium-addons-for-elementor' ), esc_html( $site['tooltip'] ) ); ?></span>
							<svg xmlns='http://www.w3.org/2000/svg' preserveAspectRatio="none"  aria-hidden="true" width='272' height='56.33' viewBox='0 0 272 56.33'><defs><style>.pa-site-tooltip-bg{fill:#e9e9eb;}</style></defs><path class='pa-site-tooltip-bg' d='M272,18v20c0,9.94-8.06,18-18,18H23c-4.1,0-7.88-1.37-10.91-3.69-1.68,1.83-6.8,4.68-11.98,3.88-1-.61,5-2,5-12.5,0-.45-.01-2.16-.01-3.76-.07-.63-.1-1.28-.1-1.93v-20C5,8.06,13.06,0,23,0h231c9.94,0,18,8.06,18,18Z'/></svg>
						</span>
					</span>

					<input type="radio" name="pa-wz-site-type" class="pa-wz-option" value="<?php printf( __( '%1$s', 'premium-addons-for-elementor' ), esc_attr( $site['value'] ) ); ?>">
				</label>
				<?php
		}

		?>
	</div>

