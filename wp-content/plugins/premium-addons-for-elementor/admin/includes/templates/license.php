<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Premium Addons Classes
use PremiumAddons\Includes\Helper_Functions;

$get_license  = Helper_Functions::get_campaign_link( 'https://premiumaddons.com/pro/#get-pa-pro', 'free-license-page', 'wp-dash', 'get-pro' );
$account_link = Helper_Functions::get_campaign_link( 'https://my.leap13.com/', 'free-license-page', 'wp-dash', 'get-pro' );


?>

<div class="pa-section-content">
	<div class="row">
		<div class="col-full">
			<form class="pa-license-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<?php settings_fields( 'papro_license' ); ?>
				<div class="pa-section-info-wrap">
					<div class="pa-section-info">

						<b><?php echo __( 'Premium Addons PRO is an extension to the free version. It boosts what you can do with the free version. Get access to 90+ Elementor widgets, 10+ global features and container add-ons, and 580+ ready-built templates to take your designs further.', 'premium-addons-for-elementor' ); ?></b>

						<ol>

							<li>
								<span>
									<?php echo __( 'Get ', 'premium-addons-for-elementor' ); ?>
									<a href="<?php echo esc_url( $get_license ); ?>" target="_blank"><?php echo __( 'Premium Addons Pro', 'premium-addons-for-elementor' ); ?></a>
									<?php echo __( 'now. ', 'premium-addons-for-elementor' ); ?></span><b style="text-decoration: underline; color: #FF6000"><?php echo __( 'SAVE UP TO $105', 'premium-addons-for-elementor' ); ?></b>.</span>
							</li>

							<li>
								<span>
									<?php echo __( 'Download the PRO version from your account at: ', 'premium-addons-for-elementor' ); ?><a href="<?php echo esc_url( $account_link ); ?>" target="_blank"><?php echo __( 'https://my.leap13.com', 'premium-addons-for-elementor' ); ?></a>
									<?php echo __( ' -> Downloads tab.', 'premium-addons-for-elementor' ); ?>
								</span>
							</li>

							<li>
								<span><?php echo __( 'Upload the PRO version on your site from WP Dashboard -> Plugins -> Add Plugin -> Upload Plugin.', 'premium-addons-for-elementor' ); ?></span>
							</li>

							<li>
								<span><?php echo __( 'Get your license key from your account page on my.leap13.com -> License Keys tab.', 'premium-addons-for-elementor' ); ?></span>
							</li>

							<li>
								<span><?php echo __( 'Activate your license key from your WP Dashboard -> Premium Addons -> License tab.', 'premium-addons-for-elementor' ); ?></span>
							</li>
						</ol>

					</div>
				</div>
			</form>
		</div>
	</div>
</div> <!-- End Section Content -->
