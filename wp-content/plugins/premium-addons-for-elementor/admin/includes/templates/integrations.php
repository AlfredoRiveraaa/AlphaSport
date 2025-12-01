<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use PremiumAddons\Includes\Helper_Functions;

// Get settings
$settings = self::get_integrations_settings();

$locales = Helper_Functions::get_google_maps_prefixes();

?>

<div class="pa-section-content">
	<div class="row">
		<div class="col-full">
			<form action="" method="POST" id="pa-integrations" name="pa-integrations" class="pa-settings-form">
			<div id="pa-integrations-settings" class="pa-settings-tab">

				<div class="pa-section-info-wrap">
					<div class="pa-section-info">
						<h4><?php echo esc_html_e( 'Google API Keys', 'premium-addons-for-elementor' ); ?></h4>
						<p><?php printf( 'Google APIs are used in Google Maps and Video Box widgets. If you don\'t have one, click %1$shere%2$s to get your key. Click %3$shere%2$s to enable Youtube Data for your API key', '<a href="https://premiumaddons.com/docs/google-api-key-for-elementor-widgets/" target="_blank">', '</a>', '<a href="https://premiumaddons.com/docs/how-to-enable-youtube-data-api-for-premium-video-box-widget" target="_blank">' ); ?></p>
					</div>
				</div>

				<table class="pa-maps-table">

					<tr>
						<td>
							<span class="pa-maps-circle-icon"></span>
							<h4 class="pa-api-title"><?php echo esc_html_e( 'Youtube Data API Key:', 'premium-addons-for-elementor' ); ?></h4>
						</td>
						<td>
							<input name="premium-youtube-api" id="premium-youtube-api" type="text" placeholder="Youtube API Key" value="<?php echo esc_attr( $settings['premium-youtube-api'] ); ?>">
						</td>
					</tr>

					<tr>
						<td>
							<span class="pa-maps-circle-icon"></span>
							<h4 class="pa-api-title"><?php echo esc_html_e( 'Google Maps API Key:', 'premium-addons-for-elementor' ); ?></h4>
						</td>
						<td>
							<input name="premium-map-api" id="premium-map-api" type="text" placeholder="Maps API Key" value="<?php echo esc_attr( $settings['premium-map-api'] ); ?>">
						</td>
					</tr>
					<tr>
						<td>
							<span class="pa-maps-circle-icon"></span>
							<h4 class="pa-api-disable-title"><?php echo esc_html_e( 'Google Maps Localization Language:', 'premium-addons-for-elementor' ); ?></h4>
						</td>
						<td>
							<select name="premium-map-locale" id="premium-map-locale" class="placeholder placeholder-active">
									<option value=""><?php esc_html_e( 'Default', 'premium-addons-for-elementor' ); ?></option>
								<?php
								foreach ( $locales as $key => $value ) {
									$selected = '';
									if ( $key === $settings['premium-map-locale'] ) {
										$selected = 'selected="selected" ';
									}
									?>
								<option value="<?php echo esc_attr( $key ); ?>" <?php echo esc_attr( $selected ); ?>><?php echo esc_attr( $value ); ?></option>
								<?php } ?>
							</select>
						</td>
					</tr>
					<tr>
						<td>
							<span class="pa-maps-circle-icon"></span>
							<h4 class="pa-api-disable-title"><?php echo esc_html_e( 'Load Maps API JS File:', 'premium-addons-for-elementor' ); ?></h4>
						</td>
						<td>
							<input name="premium-map-disable-api" id="premium-map-disable-api" type="checkbox" <?php checked( 1, $settings['premium-map-disable-api'], true ); ?>>
							<label for="premium-map-disable-api"></label>
							<span>
								<?php echo esc_html_e( 'This will load API JS file if it\'s not loaded by another theme or plugin.', 'premium-addons-for-elementor' ); ?>
							</span>
						</td>
					</tr>
					<tr>
						<td>
							<span class="pa-maps-circle-icon"></span>
							<h4 class="pa-api-disable-title">
								<?php echo esc_html_e( 'Load Markers Clustering JS File:', 'premium-addons-for-elementor' ); ?>
							</h4>
						</td>
						<td>
							<input name="premium-map-cluster" id="premium-map-cluster" type="checkbox" <?php checked( 1, $settings['premium-map-cluster'], true ); ?>>
							<label for="premium-map-cluster"></label>
							<span><?php echo esc_html_e( 'This will load the JS file for markers clusters.', 'premium-addons-for-elementor' ); ?></span>
						</td>
					</tr>

					<?php if ( class_exists( 'WP_Optimize' ) ) : ?>
						<tr>
							<td>
								<span class="pa-maps-circle-icon"></span>
								<h4 class="pa-api-disable-title">
									<?php echo esc_html_e( 'Exclude Dynamic Assets from WP-Optimize Minification:', 'premium-addons-for-elementor' ); ?>
								</h4>
							</td>
							<td>
								<input name="premium-wp-optimize-exclude" id="premium-wp-optimize-exclude" type="checkbox" <?php checked( 1, $settings['premium-wp-optimize-exclude'], true ); ?>>
								<label for="premium-wp-optimize-exclude"></label>
								<span><?php echo esc_html_e( 'This will exclude the dynamic generated CSS/JS files from being minified.', 'premium-addons-for-elementor' ); ?></span>
							</td>
						</tr>
					<?php endif; ?>

				</table>
			</div>
			</form> <!-- End Form -->
		</div>
	</div>
</div> <!-- End Section Content -->
