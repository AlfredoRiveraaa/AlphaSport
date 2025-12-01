<?php

spl_autoload_register( function( $class ) {

	if ( 0 !== strpos( $class, 'PremiumAddons' ) ) {
		return;
	}

	$class_to_load = $class;

	$filename = strtolower(
		preg_replace(
			array( '/^PremiumAddons\\\/', '/([a-z])([A-Z])/', '/_/', '/\\\/' ),
			array( '', '$1-$2', '-', DIRECTORY_SEPARATOR ),
			$class_to_load
		)
	);

	$filename = PREMIUM_ADDONS_PATH . $filename . '.php';

	if ( is_readable( $filename ) ) {
		require_once $filename;
	}

});
