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
// define( 'DB_NAME', 'u500931863_newlmsDb' );

// /** Database username */
// define( 'DB_USER', 'u500931863_newlms' );

// /** Database password */
// define( 'DB_PASSWORD', 'U1j]1Ut^Xrx;' );


define( 'DB_NAME', 'acc_db' );

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
define( 'AUTH_KEY',         '>a2u|Ew7; FzXWZo5DAN@e;^tiXP|U)g=*YOelBy3Mt4JP}aM!SU*`Vij]&NEmn9' );
define( 'SECURE_AUTH_KEY',  '):i_Pe)`gU3_wZ0=#m`*2DpE!6_+Ec/>h3tmJ/sx6uiPa2UQKt;qIRht79;#JpCg' );
define( 'LOGGED_IN_KEY',    'L^#L0LJg][F/4Xj!B`PAVH)9 }RUZD#G:(UJLWu{Q5XY`Q{VDLFxEG[zix0?9Ry:' );
define( 'NONCE_KEY',        '%ex(#/Pu+_b[)Lf*8T==s+H/T0l<&&QQZ_hp0N/=3_;QGnG4UMj!N9t5`$N:NFqb' );
define( 'AUTH_SALT',        '>77<r-/ITx$$T@U)z6H}5Xjdd_uXtk|MAhNH>ER~=6<XmkO0UB]Zbb6M!1#?t-]`' );
define( 'SECURE_AUTH_SALT', 'w0P%PDu`:$7PJ*{Q5lvj]wo;2A5p}M/}{;CZnBka)#l?#{oH]f7=4c;4[t_hRWXE' );
define( 'LOGGED_IN_SALT',   'o>a/=NfOS~G:8Mlx-^3,E]1CKmsW{9a 26!^>`t.?*&@Q]sk`1/9/-SJd]j_cQj,' );
define( 'NONCE_SALT',       'e*Y`3e;h5c&;hn`{C`zX~3re}(H;)nbd97r-mRy8KOf0Zan:a//+lOD!9,ABS6MC' );

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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
