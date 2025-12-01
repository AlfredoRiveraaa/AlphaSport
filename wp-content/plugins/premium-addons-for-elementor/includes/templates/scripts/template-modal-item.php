<?php
/**
 * Template Item
 */

?>

<div class="elementor-template-library-template-body">
	<div class="elementor-template-library-template-screenshot">
		<div class="elementor-template-library-template-preview">
			<i class="eicon-plus-circle-o" aria-hidden="true"></i>
		</div>
		<img src="{{ thumbnail }}">
	</div>
</div>
<div class="elementor-template-library-template-controls">
	<# if ( 'valid' === window.PremiumTempsData.license.status || ! pro ) { #>

		<button class="elementor-template-library-template-action premium-template-insert-no-media elementor-button elementor-button-success">
			<span class="elementor-button-title"><?php echo wp_kses_post( __( 'Insert w/o Images', 'premium-addons-for-elementor' ) ); ?></span>
		</button>

		<button class="elementor-template-library-template-action premium-template-insert elementor-button elementor-button-success">
			<span class="elementor-button-title"><?php echo wp_kses_post( __( 'Insert Template', 'premium-addons-for-elementor' ) ); ?></span>
		</button>

	<# } else if ( pro ) {
		var templateLink = window.PremiumTempsData.license.activateLink + '&utm_source=template-' + template_id;
	#>
	<a class="elementor-template-library-template-action template-library-activate-license" href="{{{ templateLink }}}" target="_blank">
		{{{ window.PremiumTempsData.license.proMessage }}}
	</a>
	<# } #>
</div>

<!--<div class="elementor-template-library-template-name">{{{ title }}}</div>-->
