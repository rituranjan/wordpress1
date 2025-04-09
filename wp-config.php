<?php
/** Enable W3 Total Cache */
define('WP_CACHE', true); // Added by W3 Total Cache

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
define( 'DB_NAME', 'Reetechtest' );

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
define( 'AUTH_KEY',         'c8.Hr+e*Xe5 %(?lypbBEpHwe$C8{<=a[D?giT-OK]NAIf|[wSOLxQj^^uZlBYT2' );
define( 'SECURE_AUTH_KEY',  'A+PHUaI_ejwu56?s1T!or%QI2^0L(Bs 1G)Z|Z_bH#zb6;f(4Xc!_<<zpph?Znqz' );
define( 'LOGGED_IN_KEY',    'zR^s?@~^3^2M>p@fG fT%>d[S;liHN}Zz8l1KMEX/3Ra[&=z|EqRk9t$l^=OL,{e' );
define( 'NONCE_KEY',        'sQ-jXXTx+}9g,!W$@>|8=d.OuLp6M!?$o+H!u;I+|J&%<s&; ZY|]_PNxECtA,X!' );
define( 'AUTH_SALT',        'mMY;p{OHiQ@]bZ6elHi.:;d#*h@TFEw.I cw`0Ay7(8!.aPj2Kq`vU(=`*CQyasj' );
define( 'SECURE_AUTH_SALT', '#PXx%ThK-M*@Md^XFR|Mc=HP3aIDs0tRdzT^|b!3i0hQ3]@kP$t]@t]/a1(?i<sI' );
define( 'LOGGED_IN_SALT',   'k>=3Gnxx)z^_4J)WH98MZHDH0E3O:hwsmWc?]y9Plm&^JEqow=5/X;G0Y}N<[njq' );
define( 'NONCE_SALT',       '<8D0 bJF?D/0VkAM09!YoR:n2&;kN++zz5ndk|-S]eB+ZOxhpM!-A.W5rw@X;f`H' );

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
$table_prefix = 'tbl_';

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
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true ); // Creates /wp-content/debug.log
define( 'WP_DEBUG_DISPLAY', false ); // Don't show errors on screen
define( 'SCRIPT_DEBUG', true ); // Loads unminified scripts/styles
define( 'SAVEQUERIES', true ); // Logs database queries

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}
@ini_set( 'upload_max_filesize' , '528M' );
@ini_set( 'post_max_size', '128M');
@ini_set( 'memory_limit', '256M' );
@ini_set( 'max_execution_time', '300' );
@ini_set( 'max_input_time', '300' );

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
