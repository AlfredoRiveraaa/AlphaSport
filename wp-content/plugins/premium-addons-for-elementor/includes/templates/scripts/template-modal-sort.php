<?php
/**
 * Templates Sort
 */

?>
<div>
	<label><?php echo wp_kses_post( __( 'Sort By', 'premium-addons-for-elementor' ) ); ?></label>
	<select id="elementor-template-library-sort-subtype" class="elementor-template-library-sort-select premium-library-sort" data-elementor-filter="subtype">

		<option value="featured" <# if ('featured' === activeSort) { #>selected<# } #>><?php echo wp_kses_post( __( 'Featured', 'premium-addons-for-elementor' ) ); ?></option>
		<option value="recent" <# if ('recent' === activeSort) { #>selected<# } #>><?php echo wp_kses_post( __( 'Date', 'premium-addons-for-elementor' ) ); ?></option>
	</select>
</div>
