<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'trangchu');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '[f(Q ]3b?$UP-z1/24R*z%lpEI:IeLHL_8@;$3F9Ar~}6txzd$?=Vw/a5yGRC!@D');
define('SECURE_AUTH_KEY',  'lC$2iw$sqx&Ww-)&/!+Gpa;?xDfu4{nHmqUVPc~;>`2Z]%)yT 2wY`.i(h;D~uPb');
define('LOGGED_IN_KEY',    '>s)G^ dCY&Ld/gEo[/ O]c|ijMf{@:7&f+Wvpffkxo#>6]aPF$^I{.CRv1Z_ZdN-');
define('NONCE_KEY',        ';!lAK,-~GD2XUeWGf)dB3q7E~q}[= |v~6rXn%{dNb;7*_?l@`?]f2MVbSMD?BVf');
define('AUTH_SALT',        'o0INcZB^_((_C1d8VbK:2[Ev;KF?ap7Frv_9^kPs=64-&c-&.7Qb|,NZP]d^hmPE');
define('SECURE_AUTH_SALT', 'Fe7HH5R-8+2w4?b;$-dG]<1`nxf7*W&},WH!tv>q,>0.<^TL{clOs?&hhz)0/mc`');
define('LOGGED_IN_SALT',   'o46S*C+[D&u~#aow$e<-g=vSZ,k7R@uXk2SiSLX;Xtp:<Q0M3kU8Sc!ZWe2# &va');
define('NONCE_SALT',       '/hIB0$(2G8^*Ue2+wvcK>azY5M*z9}Vy&u5X un;Kz@q:;O?65y1Rz;&#k_Z{ P7');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
