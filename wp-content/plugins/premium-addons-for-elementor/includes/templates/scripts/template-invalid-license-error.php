<?php
/**
 * Templates Loader Error
 */

use PremiumAddons\Includes\Templates;

?>
<div class="elementor-library-error">
	<div class="elementor-library-error-message">
	<?php
		echo wp_kses_post( __( 'You\'re using Premium Addons Pro without an official license. Get the genuine version from our website to enjoy updates, support, and access to exclusive Premium Templates.', 'premium-addons-for-elementor' ) );
	?>
	</div>
	<div class="elementor-library-error-link">
	<?php
		printf(
			'<a class="template-library-activate-license" href="%1$s" target="_blank">%2$s</a>',
			esc_url( 'https://premiumaddons.com/validate/papro' ),
			wp_kses_post( 'Get Premium Addons Pro' )
		);
		?>
	</div>
</div>
