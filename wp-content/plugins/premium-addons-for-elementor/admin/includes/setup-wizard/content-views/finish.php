<?php

	/**
	 * PA Setup Wizard Finish View.
	 *
	 * @package Setup Wizard.
	 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="pa-wz-completed-wrapper pa-wz-flex pa-wz-flex-d-col pa-wz-justify-content-center pa-wz-align-items-center">

	<img src="<?php echo esc_url( 'https://premiumtemplates.io/wp-content/uploads/wizard/team-hands.png' ); ?>" alt="<?php echo esc_attr_e( 'Completed', 'premium-addons-for-elementor' ); ?>">

	<h4 class="pa-wz-complete-msg"><?php echo esc_html__( 'Setup Complete!', 'premium-addons-for-elementor' ); ?></h4>
	<span class="pa-wz-complete-tagline"><?php echo esc_html__( 'Let’s Build Something Amazing!', 'premium-addons-for-elementor' ); ?></span>
	<div class="pa-wz-complete-cta pa-wz-flex pa-wz-align-items-center">
		<a class="pa-complete-btn pa-wz-flex pa-wz-align-items-center finish-btn pa-new-page" aria-label="<?php echo esc_attr_e( 'Edit a blank canvas with the Elementor Editor', 'premium-addons-for-elementor' ); ?>"><?php echo esc_html_e( 'Create a New Page', 'premium-addons-for-elementor' ); ?></a>
		<a class="pa-complete-btn pa-wz-flex pa-wz-align-items-center finish-btn" aria-label="<?php echo esc_attr_e( 'Go to Dashboard', 'premium-addons-for-elementor' ); ?>"><?php echo esc_html_e( 'Go to Dashboard', 'premium-addons-for-elementor' ); ?></a>
	</div>
</div>
