<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'alpha_sport_db' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '{yzk~lPrc6}^uMUmF8 3XWK-XBOmbAhAvdNN7>1*J:BFs?h)ACqP./qGK*rVSCIT' );
define( 'SECURE_AUTH_KEY',  'Gc-qfApLxQf!]yh~2f=J[a7}_(6VE~S:w>>Lk){97ZFCh3{ps72:7=^WOBy# >~o' );
define( 'LOGGED_IN_KEY',    'sBW2;EJ}Pp8C%uQ(-DAbx15F2&jHt{J~juzlXKW&h4g)!H)W~SolBU}<o n@,={)' );
define( 'NONCE_KEY',        'L.IuIoh{zB>@]#uab~!-;_7LBF-h@31>CH@}}Dvl];J8| xFV}>Mwg=5 8jXBJ!v' );
define( 'AUTH_SALT',        'QQ53xAIzyW8)I[znf>yzh-S/@TEn!-j4j=k<((.%1eLoltr`@OvqoWOE7c|UD2kn' );
define( 'SECURE_AUTH_SALT', 'W,R7=K-Af7by--2mwiQ M`;6^P1FWug5`/1_b~P;*m2[flhE6RG<[<qg bspO;^7' );
define( 'LOGGED_IN_SALT',   '!c;AYY_xIX5spwz^bJ]Fx&~l;DyM6vLHnR,uuP5FFzfY@s?1k2* GP$E1ykiKyaT' );
define( 'NONCE_SALT',       'XE]xnzMfYo8>zH5wKPl1:6cX}DgbBMlGXn~+nj8}G=Ik>[EUHo{+#=^AOE.M4%a5' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'alphabd_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
