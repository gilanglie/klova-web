<?php

$_SERVER["HTTPS"] = "on";
define('DISALLOW_FILE_EDIT', false);
define( 'WP_MEMORY_LIMIT', '1500M' );
/** define( 'WP_MEMORY_LIMIT', M' ); **/

//END Really Simple SSL
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
define('DB_NAME', 'klova');

/** MySQL database username */
define('DB_USER', 'klv_usr');

/** MySQL database password */
define('DB_PASSWORD', '42ALF645m9pFObYd');

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
define('AUTH_KEY',         'O]_cp#;AZ!qBH+8z=8t)tghZ],D?gy=|nuSrb<H:P71]I&@/>).o<V?K=$c}<Fq^');
define('SECURE_AUTH_KEY',  'r4<4HK3)zUQxAaRj1f8y/V$OEq9+n5i>Ex?.:{X2qjM@%_utVbR)+iqNZ-%ZeTw2');
define('LOGGED_IN_KEY',    'r636!vtG mI}yj>H0u6Fa#}GZ3JVcKIr=[J@)1j|%~(6Q<$DWMF(@,Zr/,-~D)#o');
define('NONCE_KEY',        ' C]vyF#,7w3:mxlAdRmjFO}/Dw;QBR~d&GhuE?.e~JjkTs.,v;X}K_#GOGm$$/I%');
define('AUTH_SALT',        'm|Y`Q kH^]LDB;InLRBbg503v1[#%8<Gv!!3(]KZXnkokiZ44b}V8)s.MM@Z1)|v');
define('SECURE_AUTH_SALT', '5ZSW)]zKb?q*_NK{{.bBrJ/Av-=6s5lk`u&jP9wsCL;Vv}k7L M# ketU..Iv&t%');
define('LOGGED_IN_SALT',   '+=i[CzHknSSAmHJe6i#g%}Fe)&%T]VA9p864_b~]hQ8~Y.(AN9?(D)9s]TqXC7;v');
define('NONCE_SALT',       'M2DqG/zZM]<6Khn:hr1,Q>N?7ONm,ZTs$`-bG`h>:(Jx]tnZHuuQKn]U,`Kxr H1');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'klvs_';

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

