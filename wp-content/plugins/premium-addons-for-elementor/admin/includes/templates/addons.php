<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use PremiumAddons\Includes\Helper_Functions;

$prefix = Helper_Functions::get_prefix();

$features = $elements['cat-13']['elements'];

?>

<div class="pa-section-content">
	<div class="row">
		<div class="col-full">
			<form action="" method="POST" id="pa-features" name="pa-features" class="pa-settings-form">
			<div id="pa-features-settings" class="pa-settings-tab">

				<div class="pa-section-outer-wrap">
					<div class="pa-section-info-wrap">
						<div class="pa-section-info">
							<h4><?php printf( '%1$s %2$s', esc_html( $prefix ), esc_html( __( 'Templates', 'premium-addons-for-elementor' ) ) ); ?></h4>
							<p><?php echo esc_html( __( 'Build Professional Website in Minutes Using Our Pre-Made Premium Elementor Templates.', 'premium-addons-for-elementor' ) ); ?></p>
						</div>

						<div class="pa-section-info-cta">
							<label class="switch">
								<input type="checkbox" id="premium-templates" pa-element="feature" name="premium-templates" <?php echo checked( 1, $enabled_elements['premium-templates'], false ); ?>>
									<span class="slider round pa-control"></span>
								</label>
							</p>
						</div>
					</div>
					<a href="<?php echo esc_url( $features[0]['demo'] ); ?>" target="_blank"></a>
				</div>

				<div class="pa-section-outer-wrap">
					<div class="pa-section-info-wrap">
						<div class="pa-section-info">
						<h4><?php echo esc_html( __( 'Display Conditions', 'premium-addons-for-elementor' ) ); ?></h4>
							<p><?php echo esc_html( __( 'Show/hide content dynamically based on location, browser, operating system, user role, device type, Woocommerce, ACF, etc.', 'premium-addons-for-elementor' ) ); ?></p>
						</div>

						<div class="pa-section-info-cta">
							<label class="switch">
								<input type="checkbox" id="pa-display-conditions" pa-element="feature" name="pa-display-conditions" <?php echo checked( 1, $enabled_elements['pa-display-conditions'], false ); ?>>
									<span class="slider round pa-control"></span>
								</label>
							</p>
						</div>
					</div>
					<a href="<?php echo esc_url( $features[2]['demo'] ); ?>" target="_blank"></a>
				</div>

				<div class="pa-section-outer-wrap">
					<div class="pa-section-info-wrap">
						<div class="pa-section-info">
						<h4><?php echo esc_html( __( 'Equal Height', 'premium-addons-for-elementor' ) ); ?></h4>
							<p><?php echo esc_html( __( 'Make your widgets the same height with just ONE click.', 'premium-addons-for-elementor' ) ); ?></p>
						</div>

						<div class="pa-section-info-cta">
							<label class="switch">
								<input type="checkbox" id="premium-equal-height" pa-element="feature" name="premium-equal-height" <?php echo checked( 1, $enabled_elements['premium-equal-height'], false ); ?>>
									<span class="slider round pa-control"></span>
								</label>
							</p>
						</div>
					</div>
					<a href="<?php echo esc_url( $features[1]['demo'] ); ?>" target="_blank"></a>
				</div>

				<div class="pa-section-outer-wrap">
					<div class="pa-section-info-wrap">
						<div class="pa-section-info">
						<h4 class = "pa-inline-flex"><?php echo esc_html( __( 'Custom Mouse Cursor', 'premium-addons-for-elementor' ) ); ?>
							<button type="button" class="pa-btn-clear-cursor pa-inline-flex" title="<?php esc_html_e( 'Clear Site Cursor Settings', 'premium-addons-for-elementor' ); ?>">
								<i class="dashicons dashicons-image-rotate"></i>
							</button>
						</h4>
							<p><?php echo esc_html( __( 'Change the default mouse cursor with icon, image, or Lottie animation for any Elementor container or widget.', 'premium-addons-for-elementor' ) ); ?></p>
						</div>
						<?php

						$status         = ( isset( $features[3]['is_pro'] ) && ! Helper_Functions::check_papro_version() ) ? 'disabled' : checked( 1, $enabled_elements['premium-global-cursor'], false );
						$class          = ( isset( $features[3]['is_pro'] ) && ! Helper_Functions::check_papro_version() ) ? 'pro-' : '';
						$switcher_class = $class . 'slider round pa-control';

						?>
						<div class="pa-section-info-cta">
							<label class="switch">
								<input type="checkbox" id="premium-global-cursor" pa-element="feature" name="premium-global-cursor" <?php echo esc_attr( $status ); ?>>
									<span class="<?php echo esc_attr( $switcher_class ); ?>"></span>
								</label>
							</p>
						</div>
					</div>
					<a href="<?php echo esc_url( $features[3]['demo'] ); ?>" target="_blank"></a>
				</div>

				<div class="pa-section-outer-wrap">
					<div class="pa-section-info-wrap">
						<div class="pa-section-info">
							<h4><?php echo esc_html( __( 'Global Badge', 'premium-addons-for-elementor' ) ); ?></h4>
							<p><?php echo esc_html( __( 'Add icon, image, Lottie, or SVG blob shape badge to any Elementor container or widget.', 'premium-addons-for-elementor' ) ); ?></p>
						</div>
						<?php

						$status         = ( isset( $features[4]['is_pro'] ) && ! Helper_Functions::check_papro_version() ) ? 'disabled' : checked( 1, $enabled_elements['premium-global-badge'], false );
						$class          = ( isset( $features[4]['is_pro'] ) && ! Helper_Functions::check_papro_version() ) ? 'pro-' : '';
						$switcher_class = $class . 'slider round pa-control';

						?>
						<div class="pa-section-info-cta">
							<label class="switch">
								<input type="checkbox" id="premium-global-badge" pa-element="feature" name="premium-global-badge" <?php echo esc_attr( $status ); ?>>
									<span class="<?php echo esc_attr( $switcher_class ); ?>"></span>
								</label>
							</p>
						</div>
					</div>
					<a href="<?php echo esc_url( $features[4]['demo'] ); ?>" target="_blank"></a>
				</div>

				<div class="pa-section-outer-wrap">
					<div class="pa-section-info-wrap">
						<div class="pa-section-info">
							<h4><?php echo esc_html( __( 'Animated Shape Divider', 'premium-addons-for-elementor' ) ); ?></h4>
							<p><?php echo esc_html( __( 'Add icon, image, Lottie, or SVG blob shape badge to any Elementor container or widget.', 'premium-addons-for-elementor' ) ); ?></p>
						</div>
												<div class="pa-section-info-cta">
							<label class="switch">
								<input type="checkbox" id="premium-shape-divider" pa-element="feature" name="premium-shape-divider" <?php echo checked( 1, $enabled_elements['premium-shape-divider'], false ); ?>>
									<span class="slider round pa-control"></span>
								</label>
							</p>
						</div>
					</div>
					<a href="<?php echo esc_url( $features[5]['demo'] ); ?>" target="_blank"></a>
				</div>

				<div class="pa-section-outer-wrap">
					<div class="pa-section-info-wrap">
						<div class="pa-section-info">
							<h4><?php echo esc_html( __( 'Global Tooltips', 'premium-addons-for-elementor' ) ); ?></h4>
							<p><?php echo esc_html( __( 'Add icon, text, Lottie or Elementor template tooltip to any Elementor container or widget.', 'premium-addons-for-elementor' ) ); ?></p>
						</div>
												<div class="pa-section-info-cta">
							<label class="switch">
								<input type="checkbox" id="premium-global-tooltips" pa-element="feature" name="premium-global-tooltips" <?php echo checked( 1, $enabled_elements['premium-global-tooltips'], false ); ?>>
									<span class="slider round pa-control"></span>
								</label>
							</p>
						</div>
					</div>
					<a href="<?php echo esc_url( $features[10]['demo'] ); ?>" target="_blank"></a>
				</div>

				<div class="pa-section-outer-wrap">
					<div class="pa-section-info-wrap">
						<div class="pa-section-info">
						<h4><?php echo esc_html( __( 'Floating Effects', 'premium-addons-for-elementor' ) ); ?></h4>
							<p><?php echo esc_html( __( 'Apply advanced floating effects on any Elementor element or a custom CSS selector.', 'premium-addons-for-elementor' ) ); ?></p>
						</div>

						<div class="pa-section-info-cta">
							<label class="switch">
								<input type="checkbox" id="premium-floating-effects" pa-element="feature" name="premium-floating-effects" <?php echo checked( 1, $enabled_elements['premium-floating-effects'], false ); ?>>
									<span class="slider round pa-control"></span>
								</label>
							</p>
						</div>
					</div>
					<a href="<?php echo esc_url( $features[6]['demo'] ); ?>" target="_blank"></a>
				</div>

				<div class="pa-section-outer-wrap">
					<div class="pa-section-info-wrap">
						<div class="pa-section-info">
						<h4><?php echo esc_html( __( 'Cross-Domain Copy N’ Paste', 'premium-addons-for-elementor' ) ); ?></h4>
							<p><?php echo esc_html( __( 'Copy any Elementor content from site to another in just ONE click.', 'premium-addons-for-elementor' ) ); ?></p>
						</div>

						<div class="pa-section-info-cta">
							<label class="switch">
								<input type="checkbox" id="premium-cross-domain" pa-element="feature" name="premium-cross-domain" <?php echo checked( 1, $enabled_elements['premium-cross-domain'], false ); ?>>
									<span class="slider round pa-control"></span>
								</label>
							</p>
						</div>
					</div>
					<a href="<?php echo esc_url( $features[7]['demo'] ); ?>" target="_blank"></a>
				</div>

				<div class="pa-section-outer-wrap">
					<div class="pa-section-info-wrap">
						<div class="pa-section-info">
							<h4><?php echo esc_html( __( 'Duplicator', 'premium-addons-for-elementor' ) ); ?></h4>
							<p><?php echo esc_html( __( 'Duplicate any post, page or template on your website.', 'premium-addons-for-elementor' ) ); ?></p>
						</div>

						<div class="pa-section-info-cta">
							<label class="switch">
								<input type="checkbox" id="premium-duplicator" pa-element="feature" name="premium-duplicator" <?php echo checked( 1, $enabled_elements['premium-duplicator'], false ); ?>>
									<span class="slider round pa-control"></span>
								</label>
							</p>
						</div>
					</div>
				</div>

				<div class="pa-section-outer-wrap">
					<div class="pa-section-info-wrap">
						<div class="pa-section-info">
						<h4><?php echo esc_html( __( 'Wrapper Link', 'premium-addons-for-elementor' ) ); ?></h4>
							<p><?php echo esc_html( __( 'Add links to Elementor flexbox containers or widgets.', 'premium-addons-for-elementor' ) ); ?></p>
						</div>

						<div class="pa-section-info-cta">
							<label class="switch">
								<input type="checkbox" id="premium-wrapper-link" pa-element="feature" name="premium-wrapper-link" <?php echo checked( 1, $enabled_elements['premium-wrapper-link'], false ); ?>>
									<span class="slider round pa-control"></span>
								</label>
							</p>
						</div>
					</div>
					<a href="<?php echo esc_url( $features[11]['demo'] ); ?>" target="_blank"></a>
				</div>

				<div class="pa-section-outer-wrap">
					<div class="pa-section-info-wrap">
						<div class="pa-section-info">
						<h4><?php echo esc_html( __( 'Liquid Glass', 'premium-addons-for-elementor' ) ); ?></h4>
							<p><?php echo esc_html( __( 'Apply glassmorphism and liquid glass effects to Elementor containers and widgets.', 'premium-addons-for-elementor' ) ); ?></p>
						</div>

						<div class="pa-section-info-cta">
							<label class="switch">
								<input type="checkbox" id="premium-glassmorphism" name="premium-glassmorphism" <?php echo checked( 1, $enabled_elements['premium-glassmorphism'], false ); ?>>
									<span class="slider round pa-control"></span>
								</label>
							</p>
						</div>
					</div>
					<a href="<?php echo esc_url( $features[12]['demo'] ); ?>" target="_blank"></a>
				</div>

			</div>
			</form> <!-- End Form -->
		</div>
	</div>
</div> <!-- End Section Content -->
