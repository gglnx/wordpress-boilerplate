<?php
/**
 * @package     gglnx/kesselblech
 * @link        https://github.com/gglnx/kesselblech
 * @author      Dennis Morhardt <info@dennismorhardt.de>
 * @copyright   Copyright 2015, Dennis Morhardt
 * @licence     MIT
 */

// Absolute path to the WordPress directory
if ( !defined( 'ABSPATH' ) )
	define( 'ABSPATH', dirname( __FILE__ ) . DIRECTORY_SEPARATOR );

// Absolute path to the project root directory
define( 'ROOT_ABSPATH', dirname( __DIR__ ) . DIRECTORY_SEPARATOR );

// Include autoloader from composer
require_once( ROOT_ABSPATH . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php' );

// Helper: Get environment settings, with fallback
function getenv_fallback( $varname, $fallback = null ) {
	return getenv( $varname ) ? getenv( $varname ) : $fallback;
}

// Load environment
$dotenv = new Dotenv\Dotenv( ROOT_ABSPATH );
$dotenv->load();

// Required environment variables
$dotenv->required( [ 'DB_NAME', 'DB_USER', 'DB_PASSWORD', 'THEMENAME' ] );

// Current environment
define( 'WP_ENV', getenv_fallback( 'WP_ENV', 'development' ) );

// Database connection
define( 'DB_NAME', getenv( 'DB_NAME' ) );
define( 'DB_USER', getenv( 'DB_USER' ) );
define( 'DB_PASSWORD', getenv( 'DB_PASSWORD' ) );
define( 'DB_HOST', getenv_fallback( 'DB_HOST', 'localhost' ) );
define( 'DB_CHARSET', getenv_fallback( 'DB_CHARSET', 'utf8' ) );
define( 'DB_COLLATE', getenv_fallback( 'DB_COLLATE', '' ) );

// WordPress Database Table prefix
$table_prefix = getenv_fallback( 'DB_TABLEPREFIX', 'wp_' );

// Language
if ( getenv( 'WPLANG' ) )
	$wp_local_package = getenv( 'WPLANG' );

// WP CLI fix
if ( defined( 'WP_CLI' ) )
	$_SERVER['HTTP_HOST'] = 'localhost:9000';

// Override home & siteurl settings
define( 'WP_HOME', getenv_fallback( 'WP_HOME', 'http://' . $_SERVER['HTTP_HOST'] ) );
define( 'WP_SITEURL', getenv_fallback( 'WP_SITEURL', 'http://' . $_SERVER['HTTP_HOST'] . '/wordpress' ) );

// Default theme
define( 'WP_DEFAULT_THEME', getenv( 'THEMENAME' ) );

// Authentication Unique Keys and Salts
define( 'AUTH_KEY', getenv_fallback( 'AUTH_KEY', 'put your unique phrase here' ) );
define( 'SECURE_AUTH_KEY', getenv_fallback( 'SECURE_AUTH_KEY', 'put your unique phrase here' ) );
define( 'LOGGED_IN_KEY', getenv_fallback( 'LOGGED_IN_KEY', 'put your unique phrase here' ) );
define( 'NONCE_KEY', getenv_fallback( 'NONCE_KEY', 'put your unique phrase here' ) );
define( 'AUTH_SALT', getenv_fallback( 'AUTH_SALT', 'put your unique phrase here' ) );
define( 'SECURE_AUTH_SALT', getenv_fallback( 'SECURE_AUTH_SALT', 'put your unique phrase here' ) );
define( 'LOGGED_IN_SALT', getenv_fallback( 'LOGGED_IN_SALT', 'put your unique phrase here' ) );
define( 'NONCE_SALT', getenv_fallback( 'NONCE_SALT', 'put your unique phrase here' ) );

// Disable automatic updates
define( 'AUTOMATIC_UPDATER_DISABLED', true );
define( 'DISALLOW_FILE_EDIT', true );

// Disable cron calls on page loads
define( 'DISABLE_WP_CRON', true );

// Content directory
define( 'CONTENT_DIR', 'content' );
define( 'WP_CONTENT_DIR', ROOT_ABSPATH . 'public' . DIRECTORY_SEPARATOR . CONTENT_DIR );
define( 'WP_CONTENT_URL', WP_HOME . '/' . CONTENT_DIR );

// Load configuration for environment
if ( file_exists( ROOT_ABSPATH . 'public' . DIRECTORY_SEPARATOR . 'wp-config.' . WP_ENV . '.php' ) )
	require_once ROOT_ABSPATH . 'public' . DIRECTORY_SEPARATOR . 'wp-config.' . WP_ENV . '.php';

// Init WordPress
require_once ABSPATH . 'wp-settings.php';
