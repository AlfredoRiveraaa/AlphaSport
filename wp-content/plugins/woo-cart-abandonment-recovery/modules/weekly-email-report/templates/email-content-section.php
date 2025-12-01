<?php
/**
 * Template Name: Email Header
 *
 * @package Cart Abandonment Recovery
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<tbody data-block-id="7" class="mceWrapper">
	<tr>
		<td style="background-color:transparent" valign="top" align="center" class="mceSectionBody">
			<table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:660px" role="presentation">
				<tbody>
					<tr>
						<td style="background-color:#ffffff" valign="top" class="mceWrapperInner">
							<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation" data-block-id="6">
								<tbody>
									<tr class="mceRow">
										<td style="background-position:center;background-repeat:no-repeat;background-size:cover" valign="top">
											<table border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation">
												<tbody>
													<tr>
														<td style="padding-top:0;padding-bottom:0" valign="top" class="mceColumn" id="mceColumnId--11" data-block-id="-11" colspan="12" width="100%">
															<table border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation">
																<tbody>
																	<?php
																		require CARTFLOWS_CA_DIR . 'modules/weekly-email-report/templates/email-content-welcome-section.php'; 
																	?>
																	<!-- Row with full overview heading. -->
																	<tr id="full-overview-string">
																		<td style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;border:0;border-radius:0" valign="top" id="b16">
																			<table width="100%" style="border:0;border-radius:0;border-collapse:separate">
																				<tbody>
																					<tr>
																						<td style="padding-left:24px;padding-right:24px;padding-top:12px;padding-bottom:0" class="mceTextBlockContainer">
																							<div data-block-id="16" class="mceText" id="d16" style="width:100%">
																								<h4 style="text-align: left;" class="last-child">
																									<?php
																									echo esc_html( 
																										sprintf(
																											/* translators: %1$s: Report Start Date, %2$s: Report End Date. */ 
																											__( 'Full Overview (%1$s - %2$s):', 'woo-cart-abandonment-recovery' ), 
																											$from_date, 
																											$to_date 
																										) 
																									); 
																									?>
																								</h4>
																							</div>
																						</td>
																					</tr>
																				</tbody>
																			</table>
																		</td>
																	</tr>
																	<?php
																		require CARTFLOWS_CA_DIR . 'modules/weekly-email-report/templates/email-recovery-stat.php';
																	?>
																	<?php
																		// Display section to promote the CartFlows Pro if Pro is not there else show all products section.
																	if ( ! class_exists( 'Cartflows_Pro_Loader' ) ) {
																		include CARTFLOWS_CA_DIR . 'modules/weekly-email-report/templates/email-cf-block.php';
																	} else {
																		include CARTFLOWS_CA_DIR . 'modules/weekly-email-report/templates/email-bsf-product-block.php';
																	}
																	?>
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
<?php
