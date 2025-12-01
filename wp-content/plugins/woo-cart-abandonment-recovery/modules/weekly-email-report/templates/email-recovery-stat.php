<?php
/**
 * Template Name: Email Reovery Stats Block.
 *
 * @package Cart Abandonment Recovery
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?> 
<!-- First Row of Review Stats -->
<tr>
	<td style="padding-top:0;padding-bottom:0;padding-right:24px;padding-left:24px" valign="top" class="mceGutterContainer" id="gutterContainerId-17">
		<table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:separate" role="presentation">
			<tbody>
				<tr>
					<td style="padding-top:12px;padding-bottom:0;padding-right:0;padding-left:0;border:0;border-radius:0" valign="top" class="mceLayoutContainer" id="b17">
						<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation" data-block-id="17" id="section_a083e0db65d7352a2c22ef29a406ab78" class="mceLayout">
							<tbody>
								<tr class="mceRow">
									<td style="background-position:center;background-repeat:no-repeat;background-size:cover" valign="top">
										<table border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation">
											<tbody>
												<tr>
													<td valign="top" class="mceColumn" id="mceColumnId--13" data-block-id="-13" colspan="12" width="100%">
														<table border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation">
															<tbody>
																<tr>
																	<td style="border:0;border-radius:0" valign="top" align="center" id="b-5">
																		<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation" data-block-id="-5">
																			<tbody>
																				<tr class="mceRow">
																					<td style="background-position:center;background-repeat:no-repeat;background-size:cover" valign="top">
																						<!-- First Row of revenue. -->
																						<table border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation">
																							<tbody>
																								<tr>
																									<td valign="top" class="mceColumn" id="mceColumnId--17" data-block-id="-17" colspan="12" width="100%">
																										<table border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation">
																											<tbody>
																												<tr>
																													<td style="border:0;border-radius:0" valign="top" id="b22">
																														<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation" data-block-id="22">
																															<tbody>
																																<tr class="mceRow">
																																	<td style="background-position:center;background-repeat:no-repeat;background-size:cover;padding-top:0px;padding-bottom:0px" valign="top">
																																		<table border="0" cellpadding="0" cellspacing="24" width="100%" style="table-layout:fixed" role="presentation">
																																			<tbody>
																																				<tr>
																																					<!-- Last 7 Days Revenue -->
																																					<td style="padding-top:0;padding-bottom:0" valign="center" class="mceColumn" id="mceColumnId-19" data-block-id="19" colspan="6" width="50%">
																																						<table border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation">
																																							<tbody>
																																								<tr>
																																									<td style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;border:0;border-radius:0" valign="top" id="b23">
																																										<table width="100%" style="border-width:1px;border-style:solid;border-color:#edbeaf;background-color:#fff9f7;border-radius:0;border-collapse:separate">
																																											<tbody>
																																												<tr>
																																													<td style="padding-left:24px;padding-right:24px;padding-top:24px;padding-bottom:24px" class="mceTextBlockContainer">
																																														<div data-block-id="23" class="mceText" id="d23" style="width:100%">
																																															<p>
																																																<span style="color:#1f2937;">
																																																	<span
																																																	style="font-size: 24px"><?php echo wp_kses_post( wc_price( $report_details['recovered_revenue'] ) ); ?></span>
																																																</span>
																																															</p>
																																															<p class="last-child">
																																																<span style="color:#707070;">
																																																	<span
																																																	style="font-size: 14px"><?php echo esc_html__( '(Last 7 days)', 'woo-cart-abandonment-recovery' ); ?></span>
																																																</span>
																																															</p>
																																														</div>
																																													</td>
																																												</tr>
																																											</tbody>
																																										</table>
																																									</td>
																																								</tr>
																																							</tbody>
																																						</table>
																																					</td>
																																					<!-- Last 30 Days Revenue -->
																																					<td style="padding-top:0;padding-bottom:0" valign="center" class="mceColumn" id="mceColumnId-21" data-block-id="21" colspan="6" width="50%">
																																						<table border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation">
																																							<tbody>
																																								<tr>
																																									<td style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;border:0;border-radius:0" valign="top" id="b24">
																																										<table width="100%" style="border-width:1px;border-style:solid;border-color:#edbeaf;background-color:#fff9f7;border-top-right-radius:0;border-collapse:separate">
																																											<tbody>
																																												<tr>
																																													<td style="padding-left:24px;padding-right:24px;padding-top:24px;padding-bottom:24px" class="mceTextBlockContainer">
																																														<div data-block-id="24" class="mceText" id="d24" style="width:100%">
																																															<p>
																																																<span style="color:#1f2937;">
																																																	<span style="font-size: 24px">
																																																		<?php echo wp_kses_post( wc_price( $report_details['last_month_recovered_Revenue'] ) ); ?>
																																																	</span>
																																																</span>
																																															</p>
																																															<p class="last-child">
																																																<span style="color:#707070;">
																																																	<span style="font-size: 14px">
																																																		<?php echo esc_html__( '(Last 30 days)', 'woo-cart-abandonment-recovery' ); ?>
																																																	</span>
																																																</span>
																																															</p>
																																														</div>
																																													</td>
																																												</tr>
																																											</tbody>
																																										</table>
																																									</td>
																																								</tr>
																																							</tbody>
																																						</table>
																																					</td>
																																				</tr>
																																			</tbody>
																																		</table>
																																	</td>
																																</tr>
																															</tbody>
																														</table>
																													</td>
																												</tr>
																											</tbody>
																										</table>
																									</td>
																								</tr>
																							</tbody>
																						</table>
																					</td>
																				</tr>
																			</tbody>
																		</table>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
	</td>
</tr>
<!-- First Row of Review Stats End -->

<!-- Second Row of Review Stats -->
<tr>
	<td style="padding-top:0;padding-bottom:0;padding-right:24px;padding-left:24px" valign="top" class="mceGutterContainer" id="gutterContainerId-25">
		<table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:separate" role="presentation">
			<tbody>
				<tr>
					<td style="padding-top:0;padding-bottom:12px;padding-right:0;padding-left:0;border:0;border-radius:0" valign="top" class="mceLayoutContainer" id="b25">
						<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation" data-block-id="25" id="section_ee7ff1c8fb52ecf9a18c97677a634035" class="mceLayout">
							<tbody>
								<tr class="mceRow">
									<td style="background-position:center;background-repeat:no-repeat;background-size:cover" valign="top">
										<table border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation">
											<tbody>
												<tr>
													<td valign="top" class="mceColumn" id="mceColumnId--14" data-block-id="-14" colspan="12" width="100%">
														<table border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation">
															<tbody>
																<tr>
																	<td style="border:0;border-radius:0" valign="top" align="center" id="b-7">
																		<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation" data-block-id="-7">
																			<tbody>
																				<tr class="mceRow">
																					<td style="background-position:center;background-repeat:no-repeat;background-size:cover" valign="top">
																						<table border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation">
																							<tbody>
																								<tr>
																									<td valign="top" class="mceColumn" id="mceColumnId--18" data-block-id="-18" colspan="12" width="100%">
																										<table border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation">
																											<tbody>
																												<tr>
																													<td style="border:0;border-radius:0" valign="top" id="b30">
																														<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation" data-block-id="30">
																															<tbody>
																																<tr class="mceRow">
																																	<td style="background-position:center;background-repeat:no-repeat;background-size:cover;padding-top:0px;padding-bottom:0px" valign="top">
																																		<table border="0" cellpadding="0" cellspacing="24" width="100%" style="table-layout:fixed" role="presentation">
																																			<tbody>
																																				<tr>
																																					<!-- Stat One -->
																																					<td style="padding-top:0;padding-bottom:0" valign="top" class="mceColumn" id="mceColumnId-27" data-block-id="27" colspan="4" width="33.33333333333333%">
																																						<table border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation">
																																							<tbody>
																																								<tr>
																																									<td style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;border:0;border-radius:0" valign="top" id="b31">
																																										<table width="100%" style="border-width:1px;border-style:solid;border-color:#edbeaf;background-color:#fff9f7;border-radius:0;border-collapse:separate">
																																											<tbody>
																																												<tr>
																																													<td style="padding-left:24px;padding-right:24px;padding-top:24px;padding-bottom:24px" class="mceTextBlockContainer">
																																														<div data-block-id="31" class="mceText" id="d31" style="width:100%">
																																															<p>
																																																<span style="color:#1f2937;">
																																																	<span style="font-size: 24px"><?php echo esc_html( $report_details['abandonded_orders'] ); ?></span>
																																																</span>
																																															</p>
																																															<p class="last-child">
																																																<span style="color:#707070;">
																																																	<span style="font-size: 14px">
																																																		<?php
																																																			echo esc_html__(
																																																				'Carts Abandoned',
																																																				'woo-cart-abandonment-recovery'
																																																			)
																																																			?>
																																																	</span>
																																																</span>
																																															</p>
																																														</div>
																																													</td>
																																												</tr>
																																											</tbody>
																																										</table>
																																									</td>
																																								</tr>
																																							</tbody>
																																						</table>
																																					</td>
																																					<!-- Stat One Close-->
																																					<!-- Stat Two -->
																																					<td style="padding-top:0;padding-bottom:0" valign="top" class="mceColumn" id="mceColumnId-29" data-block-id="29" colspan="4" width="33.33333333333333%">
																																						<table border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation">
																																							<tbody>
																																								<tr>
																																									<td style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;border:0;border-radius:0" valign="top" id="b32">
																																										<table width="100%" style="border-width:1px;border-style:solid;border-color:#edbeaf;background-color:#fff9f7;border-radius:0;border-collapse:separate">
																																											<tbody>
																																												<tr>
																																													<td style="padding-left:24px;padding-right:24px;padding-top:24px;padding-bottom:24px" class="mceTextBlockContainer">
																																														<div data-block-id="32" class="mceText" id="d32" style="width:100%">
																																															<p>
																																																<span style="color:#1f2937;">
																																																	<span style="font-size: 24px"><?php echo esc_html( $report_details['recovered_orders'] ); ?></span>
																																																</span>
																																															</p>
																																															<p class="last-child">
																																																<span style="color:#707070;">
																																																	<span style="font-size: 14px">
																																																		<?php
																																																			echo esc_html__(
																																																				'Carts Recovered',
																																																				'woo-cart-abandonment-recovery'
																																																			)
																																																			?>
																																																	</span>
																																																</span>
																																															</p>
																																														</div>
																																													</td>
																																												</tr>
																																											</tbody>
																																										</table>
																																									</td>
																																								</tr>
																																							</tbody>
																																						</table>
																																					</td>
																																					<!-- Stat Two Close -->
																																					<!-- Stat Three -->
																																					<td style="padding-top:0;padding-bottom:0" valign="top" class="mceColumn" id="mceColumnId-34" data-block-id="34" colspan="4" width="33.33333333333333%">
																																						<table border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation">
																																							<tbody>
																																								<tr>
																																									<td style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;border:0;border-radius:0" valign="top" id="b35">
																																										<table width="100%" style="border-width:1px;border-style:solid;border-color:#edbeaf;background-color:#fff9f7;border-radius:0;border-collapse:separate">
																																											<tbody>
																																												<tr>
																																													<td style="padding-left:24px;padding-right:24px;padding-top:24px;padding-bottom:24px" class="mceTextBlockContainer">
																																														<div data-block-id="35" class="mceText" id="d35" style="width:100%">
																																															<p>
																																																<span style="color:#1f2937;">
																																																	<span style="font-size: 24px">1<?php echo esc_html( $report_details['conversion_rate'] ) . '%'; ?></span>
																																																</span>
																																															</p>
																																															<p class="last-child">
																																																<span style="color:#707070;">
																																																	<span style="font-size: 14px">
																																																		<?php
																																																			echo esc_html__(
																																																				'Recovery Rate',
																																																				'woo-cart-abandonment-recovery'
																																																			)
																																																			?>
																																																	</span>
																																																</span>
																																															</p>
																																														</div>
																																													</td>
																																												</tr>
																																											</tbody>
																																										</table>
																																									</td>
																																								</tr>
																																							</tbody>
																																						</table>
																																					</td>
																																					<!-- Stat Three Close -->
																																				</tr>
																																			</tbody>
																																		</table>
																																	</td>
																																</tr>
																															</tbody>
																														</table>
																													</td>
																												</tr>
																											</tbody>
																										</table>
																									</td>
																								</tr>
																							</tbody>
																						</table>
																					</td>
																				</tr>
																			</tbody>
																		</table>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
	</td>
</tr>
<!-- Second row of the revenue stats. -->
