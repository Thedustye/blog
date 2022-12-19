<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'ctj0c1jwt' );

/** Database username */
define( 'DB_USER', 'ctj0c1jwt' );

/** Database password */
define( 'DB_PASSWORD', '31ApNMRS' );

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
define( 'AUTH_KEY',         'u`QP}?3% 0gyUE$Oa:jL7aLUggNIARK:8]_~#U[xXr&G[K)f>(UcvJ/obt$K1G5m' );
define( 'SECURE_AUTH_KEY',  'bbLa0Yen57x=vTMuz4X0Q<.kZd(zmq{NOArjg<Rh.eRB@/(zDQQ?`(cE`.iQ5wE<' );
define( 'LOGGED_IN_KEY',    'fcEjCXzT.+rvdLc#DpYQB>[4q&*6H/zRf)@j0{zpr[AA2.ZP#H+Y%x.),WjH}{O2' );
define( 'NONCE_KEY',        ':3! SoW|4XKEhNeP;liZE>2UX Hm]gN,;@|Yyg2@|Lt`f]@iVH?5j)WZAV+$5 (E' );
define( 'AUTH_SALT',        'N-XS&kj@hw]-UL9ho77~}i-z+RQ?fIgrD)vY}l4OP:2jg_j&3b-P*,u @PlQ9#P]' );
define( 'SECURE_AUTH_SALT', '@r9<C!fSX60rwgcYa!w$cB]B-*$$4mXkX&V-~W5h8VvoDMPZtv(ka09gQF%lbl$.' );
define( 'LOGGED_IN_SALT',   'yUyj?Bz|BP*$va Lc9UvXDis2ZEx{/ELe^~i<u2`rbX?|twC*{2&;_Jsar1^*HE[' );
define( 'NONCE_SALT',       'CO#>rFv~(9m~qJRB~CFtxy: /FBg;TUd566G!eHP8$S OvqU*s(Vd$Mg:M;C69}s' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
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
