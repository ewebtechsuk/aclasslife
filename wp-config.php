<?php
define( 'WP_CACHE', true );

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
define( 'DB_NAME', 'u753768407_Z5AlD' );

/** Database username */
define( 'DB_USER', 'u753768407_aTKmD' );

/** Database password */
define( 'DB_PASSWORD', 'SNeV3MIYCV' );

/** Database hostname */
define( 'DB_HOST', '127.0.0.1' );

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
define( 'AUTH_KEY',          '}@x50?.)|iPTgxhF?Py?`EHp`?A!^)0?0&![0yd*X`sG9[F_ .^,@,~;LumT@C3l' );
define( 'SECURE_AUTH_KEY',   '-_P_X^x+?#rTU&|KZVH!.SF|Nz,RMN)CLW}]NBF0`[>JwBM([oxX.IbY3H5YkDnk' );
define( 'LOGGED_IN_KEY',     '`x&*XD}#I9`j2)WLl^1q4_,s~{PXR4^r:k$BMw|:MSP{Su*_;S<6Byr@+Zx]c#f+' );
define( 'NONCE_KEY',         '_~g?c%*J7.>s3;^Q`(di*X[HfF?V&)HwcgRA<~!6Cj!vh2BxL#Y,t`cMPa#Pm*H.' );
define( 'AUTH_SALT',         'gB:`V/q{gx}wE)o0mtnsru#02DdTvllFy9FjBTDx,kl{H;UEIDs~GqC)qwTT5{SP' );
define( 'SECURE_AUTH_SALT',  ',eiUK;MH% ~{#7Rwgh3]7S:phdA[j7]:r[cV8E)B9i}m## N.X;3.U*JPD6158r9' );
define( 'LOGGED_IN_SALT',    'ej6^Rm6b*S,npl~9RV-RWV3fTFhRE!|{nP{j<@+1Cd-0T=h{@b)0}y9eez6FFYjG' );
define( 'NONCE_SALT',        'J,gCnaK61|hId1Rx/PR;)G^hEV|vvG598,=OZ;O@J,j(;:,V#J_f&n4`ZK~X7g3V' );
define( 'WP_CACHE_KEY_SALT', '/S_U>=(_LFZ!,@lv=hv6v>%$ke&J`Ig-{Zv+{?|G`9)`Z W`S&bc]j#CL-sU]AKK' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


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

define( 'FS_METHOD', 'direct' );
define( 'COOKIEHASH', 'e6bb88ecd8e834a527d79b32a4a3d08b' );
define( 'WP_AUTO_UPDATE_CORE', 'minor' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
