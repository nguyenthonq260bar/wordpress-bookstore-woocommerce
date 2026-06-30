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
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'araecrslhosting_ntaincloud' );

/** Database username */
define( 'DB_USER', 'araecrslhosting_ntaincloud' );

/** Database password */
define( 'DB_PASSWORD', 'vHKo3IPEbBNhiQixPfqBqQKq8RavfB6i' );

/** Database hostname */
define( 'DB_HOST', 'localhost:/var/lib/mysql/mysql.sock' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define( 'AUTH_KEY',          '75j2H;8V/9&clCT.&P{/>?dt`~eh)}8*``OsTzEiRLn[se?Py5PKMayY~SAmnaLg' );
define( 'SECURE_AUTH_KEY',   'Rqts,[xe{GSxCpbMICnUKoe4hUGGT,[)9L8fvi4dyGL%E V !?P;;m?A(`kTjpef' );
define( 'LOGGED_IN_KEY',     ',,U;%!90Ac#C3QQW/l?*,8-q5tS3zOPF!.I!,O$eN=?0Zn$OC&v_ww77~erQ@JK^' );
define( 'NONCE_KEY',         '=h0:%w}DJ#5?54[GoPu6F-9Iw+;FOg#dA)(]Ke/=)cvLzAWGGDOW`+dZ,c}?3/*q' );
define( 'AUTH_SALT',         'fx0x@^7,Al7,)Ymms@#^SCXJ=01a+tf-GpbuM/&S=zoqNVT]-Y<o(c%}#a4,d].3' );
define( 'SECURE_AUTH_SALT',  'Tb?kUCGj<B4[$GtCzV[0Fk8715u5d)XDPRaiGQ0<ghtiW_4ZRPovtp{7z|uT/%!q' );
define( 'LOGGED_IN_SALT',    '+j7r<%w@n2KD`~v1dUlR<FOSo,j0liw/Cd!`HI=pRd~vE*^T:y&6:;*N]<4Rj#Q#' );
define( 'NONCE_SALT',        'kP}LpDYT_:b3*X1+$N]LLWDnC9nf9KS$g*u_=-h{OF4 tI(NDPUd%3_?_f>:YeIk' );
define( 'WP_CACHE_KEY_SALT', 'TQ6N}Hq*!Cx mLXq}_;`V^,=G+xlFZ=Hufhi5bLD;,BQO+`gNq98e2uR_U4ioTTb' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'WyZ_default';


/* Add any custom values between this line and the "stop editing" line. */



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
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'WP_AUTO_UPDATE_CORE', true );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
