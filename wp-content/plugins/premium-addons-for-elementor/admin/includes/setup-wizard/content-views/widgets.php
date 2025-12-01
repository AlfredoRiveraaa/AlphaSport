<?php
	/**
	 * PA Setup Wizard Widgets View.
	 *
	 * @package Setup Wizard.
	 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

	use PremiumAddons\Includes\Helper_Functions;

	$pa_elements   = require_once PREMIUM_ADDONS_PATH . 'admin/includes/setup-wizard/elements.php';
	$rec_widgets   = array_merge( $pa_elements['basic'], $pa_elements['ecommerce'], $pa_elements['blog'] );
	$rows          = intval( ceil( count( $rec_widgets ) / 4 ) ); // each row should have 4 elements.
	$is_second_run = get_option( 'pa_complete_wizard' ) ? false : true;

if ( $is_second_run ) {
	$enabled_elements = self::get_enabled_elements();
}

?>
	<span class="pa-welcome-msg-wrapper pa-wz-flex pa-wz-flex-d-col pa-wz-justify-content-center">
		<span class="pa-welcome-msg-primary"><?php echo esc_html__( 'Recommended Widgets.', 'premium-addons-for-elementor' ); ?></span>
		<span class="pa-welcome-msg-secondary"><?php echo esc_html__( 'We\'ve pre-activated the best widgets for your website type.', 'premium-addons-for-elementor' ); ?></span>
	</span>

	<span class="pa-welcome-msg-wrapper pa-wz-flex pa-wz-flex-d-col pa-wz-justify-content-center pa-custom-site-msg">
		<span class="pa-welcome-msg-primary"><?php echo esc_html__( 'Start Activating Widgets.', 'premium-addons-for-elementor' ); ?></span>
		<span class="pa-welcome-msg-secondary"><?php echo esc_html__( 'Customize your Elementor website by enabling the widgets you need.', 'premium-addons-for-elementor' ); ?></span>
	</span>

	<div class="pa-wz-widgets-outer-wrapper">
		<form action="" method="POST" id="pa-wz-settings" name="pa-wz-settings" class="pa-wz-settings-form">
			<div class="pa-wz-recs toggled">
				<?php
					$row_start = 0;
					$row_end   = 3;
				for ( $i = 0; $i < $rows; $i++ ) {
					?>
						<div class="pa-wz-flex pa-wz-recs-row">
						<?php
						for ( $j = $row_start; $j <= $row_end; $j++ ) {
							$elem = isset( $rec_widgets[ $j ] ) ? $rec_widgets[ $j ] : false;

							if ( ! $elem ) {
								continue;
							}

							$is_checked = ! empty( $enabled_elements[ $elem['key'] ] ?? null ) ? 'checked' : '';
							$sep_cls    = $j < $row_end && isset( $rec_widgets[ $j + 1 ] ) ? 'pa-wz-sep' : 'pa-wz-sep pa-hidden-sep';
							?>
									<div class="pa-switcher <?php echo isset( $elem['name'] ) ? ' ' . esc_html( $elem['name'] ) : ''; ?>">
										<div class="pa-element-info">
											<div class="pa-element-icon-wrap">
												<i class="pa-dash-<?php echo esc_attr( $elem['key'] ); ?> pa-element-icon"></i>
											</div>
											<div class="pa-element-meta-wrap">
												<p class="pa-element-name <?php echo isset( $elem['is_freemium'] ) ? esc_attr( 'freemium' ) : ''; ?>">
													<span class="pa-sw-title"><?php echo esc_attr( $elem['title'] ); ?></span>
												<?php if ( isset( $elem['is_freemium'] ) ) : ?>
														<span><?php echo esc_html_e( 'freemium', 'premium-addons-for-elementor' ); ?></span>
													<?php endif; ?>
												</p>
											</div>
										</div>
										<label class="switch">
											<input type="checkbox" id="<?php echo esc_attr( $elem['key'] ); ?>" name="<?php echo esc_attr( $elem['key'] ); ?>"   <?php echo esc_attr( $is_checked ); ?>>
											<span class="slider round pa-control"></span>
										</label>
										<span class="<?php echo esc_attr( $sep_cls ); ?>"></span>
									</div>
								<?php
						}
						?>
						</div>
						<?php

						$row_start += 4;
						$row_end   += 4;
				}
				?>
				<div id="pa-wz-recs-gradient" class="pa-wz-gradient toggled"></div>
			</div>

			<a class="pa-wz-nav pa-wz-toggler pa-wz-flex pa-wz-align-items-center pa-wz-justify-content-center" type="button" role="button" aria-label="<?php echo esc_attr_e( 'See More', 'premium-addons-for-elementor' ); ?>">
				<?php echo esc_html_e( 'See More', 'premium-addons-for-elementor' ); ?>
			</a>

			<div class="pa-switchers">
				<span class="pa-welcome-msg-primary"><?php echo esc_html__( 'Enable More Widgets.', 'premium-addons-for-elementor' ); ?></span>
				<ul class="pa-wz-listing-outer-wrapper">
					<?php
					foreach ( $pa_elements['wizard'] as $list ) {
						$list_class  = isset( $list['key'] ) && 'ecommerce' === $list['key'] ? 'pa-wz-listing-wrapper pa-type-ecommerce' : 'pa-wz-listing-wrapper';
						$list_class .= isset( $list['key'] ) && 'blog' === $list['key'] ? ' has-blog-widget' : '';
						?>
							<li class="<?php echo esc_attr( $list_class ); ?>">
								<div class="pa-wz-list-title-wrapper pa-wz-flex pa-wz-align-items-center">
									<svg class="pa-wz-bulltet-icon" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" width="24" height="24" viewBox="0 0 24 24"><defs><style>.pa-wz-bulltet-1{fill:#231f20;stroke:#231f20;stroke-linecap:round;stroke-linejoin:round;stroke-width:1.5px;}</style></defs><circle id="Bullet" class="pa-wz-bulltet-1" cx="12" cy="12" r="8.8"/></svg>
									<!-- translators: %s is the title of the wizard list step. -->
									<span class="pa-wz-list-title"><?php printf( __( '%1$s', 'premium-addons-for-elementor' ), esc_html( $list['title'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
									<svg class="pa-wz-toggle-icon" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" width="24" height="24" viewBox="0 0 24 24"><defs><style>.pa-wz-toggle-1{fill:none;stroke:#231f20;stroke-linecap:round;stroke-linejoin:round;stroke-width:1.5px;}</style></defs><g id="Arrow_Down"><path class="pa-wz-toggle-1" d="M12,22c5.52,0,10-4.48,10-10S17.52,2,12,2,2,6.48,2,12s4.48,10,10,10Z"/><path class="pa-wz-toggle-1" d="M8.47,10.74l3.53,3.52,3.53-3.52"/></g></svg>
								</div>
								<div class="pa-wz-list-content">
								<?php
									$elements       = $list['elements'];
									$row_count      = intval( ceil( count( $elements ) / 4 ) );
									$list_row_start = 0;
									$list_row_end   = 3;

								for ( $outer_index = 0; $outer_index < $row_count; $outer_index++ ) {
									?>
										<div class="pa-wz-flex pa-wz-recs-row">
										<?php

										for ( $inner_index = $list_row_start; $inner_index <= $list_row_end; $inner_index++ ) {

											$elem = isset( $elements[ $inner_index ] ) ? $elements[ $inner_index ] : false;

											if ( ! $elem ) {
												continue;
											}

											if ( ! empty( $elem['is_pro'] ) && ! Helper_Functions::check_papro_version() ) {
												$status = 'disabled';
											} else {
												$status = ! empty( $enabled_elements[ $elem['key'] ] ?? null )
													? checked( 1, $enabled_elements[ $elem['key'] ], false )
													: '';
											}
											$class          = ( isset( $elem['is_pro'] ) && ! Helper_Functions::check_papro_version() ) ? 'pro-' : '';
											$switcher_class = $class . 'slider round pa-control';

											$sw_cont_cls  = isset( $elem['type'] ) ? 'pa-switcher pa-type-' . $elem['type'] : 'pa-switcher';
											$sw_cont_cls .= isset( $elem['name'] ) ? ' ' . esc_html( $elem['name'] ) : '';
											$sep_cls      = 'pa-wz-sep';
											?>
														<div class="<?php echo esc_attr( $sw_cont_cls ); ?>">
															<span class="<?php echo esc_attr( $sep_cls ); ?>"></span>

															<div class="pa-element-info">
																<div class="pa-element-icon-wrap">
																	<i class="pa-dash-<?php echo esc_attr( $elem['key'] ); ?> pa-element-icon"></i>
																</div>
																<div class="pa-element-meta-wrap">
																	<p class="pa-element-name <?php echo isset( $elem['is_freemium'] ) ? esc_attr( 'freemium' ) : ''; ?>">
																		<span class="pa-sw-title"><?php echo esc_attr( $elem['title'] ); ?> </span>
																<?php if ( isset( $elem['is_pro'] ) || isset( $elem['is_freemium'] ) ) : ?>
																			<span><?php echo isset( $elem['is_pro'] ) ? esc_html_e( 'pro', 'premium-addons-for-elementor' ) : esc_html_e( 'freemium', 'premium-addons-for-elementor' ); ?></span>
																		<?php endif; ?>
																	</p>
															<?php if ( isset( $elem['demo'] ) ) : ?>
																		<a class="pa-demo-link" href="<?php echo esc_url( $elem['demo'] ); ?>" target="_blank" style="display:none;">
																			<?php echo esc_html_e( 'Live Demo', 'premium-addons-for-elementor' ); ?>
																		</a>
																	<?php endif; ?>
																</div>
															</div>
															<label class="switch">
																<input type="checkbox" id="<?php echo esc_attr( $elem['key'] ); ?>" name="<?php echo esc_attr( $elem['key'] ); ?>" title="<?php echo esc_attr( $elem['title'] ); ?>" <?php echo esc_attr( $status ); ?>>
																<span class="<?php echo esc_attr( $switcher_class ); ?>"></span>

															</label>
														</div>
												<?php
										}

											$list_row_start += 4;
											$list_row_end   += 4;
										?>
										</div>
										<?php
								}
								?>
								</div>
							</li>
							<?php
					}
					?>

					<div id="pa-wz-list-gradient" class="pa-wz-gradient toggled"></div>
				</ul>
			</div>

		</form>
	</div>
<?php
