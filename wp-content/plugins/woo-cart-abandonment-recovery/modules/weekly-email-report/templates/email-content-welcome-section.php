<?php
/**
 * Template Name: Email Content Welcome user section
 *
 * @package Cart Abandonment Recovery
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<!-- Welcome user. -->
<tr>
	<td style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;border:0;border-radius:0" valign="top" id="b15">
		<table width="100%" style="border:0;border-radius:0;border-collapse:separate">
			<tbody>
				<tr>
					<td style="padding-left:24px;padding-right:24px;padding-top:12px;padding-bottom:12px" class="mceTextBlockContainer">
						<div data-block-id="15" class="mceText" id="d15" style="width:100%">
							<p style="text-align: left;" class="last-child">
								<?php
								echo esc_html( 
									sprintf(
										/* translators: %s: The user name. */
										__( 'Hey %s!', 'woo-cart-abandonment-recovery' ), 
										$user_name 
									) 
								); 
								?>
							</p>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
	</td>
</tr>

<!-- Welcome message Start -->
<tr>
	<td style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;border:0;border-radius:0" valign="top" id="b5">
		<table width="100%" style="border:0;border-radius:0;border-collapse:separate">
			<tbody>
				<tr>
					<td style="padding-left:24px;padding-right:24px;padding-top:12px;padding-bottom:12px" class="mceTextBlockContainer">
						<div data-block-id="5" class="mceText" id="d5" style="width:100%">
							<p style="text-align: left;" class="last-child">
							<?php
							echo sprintf(
								/* translators: %1$s: store name, %2$s: total revenue.  %3$s: total revenue*/
								esc_html__(
									'%1$s has recovered a total %2$s revenue in last week by using Cart Abandonment Recover by CartFlows! And in last month, we recovered %3$s',
									'woo-cart-abandonment-recovery'
								),
								esc_attr( $store_name ),
								wp_kses_post( wc_price( $report_details['recovered_revenue'] ) ),
								wp_kses_post( wc_price( $report_details['last_month_recovered_Revenue'] ) )
							);
							?>
							</p>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
	</td>
</tr>
<!-- Welcome message End -->												
<?php
