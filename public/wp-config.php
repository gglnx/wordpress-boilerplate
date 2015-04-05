<?php
/**
 * @package     gglnx/kesselblech
 * @link        https://github.com/gglnx/kesselblech
 * @author      Dennis Morhardt <info@dennismorhardt.de>
 * @copyright   Copyright 2015, Dennis Morhardt
 * @licence     MIT
 */

// Include autoloader from composer
require_once( dirname( __DIR__ ) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php' );

// Default values
$defaultConstantValues = array(
	'DB_HOST' => 'localhost',
	'DB_CHARSET' => 'utf8',
	'DB_COLLATE' => '',
	'DB_TABLEPREFIX' => 'wp_',
	'AUTH_KEY' => 'put your unique phrase here',
	'SECURE_AUTH_KEY' => 'put your unique phrase here',
	'LOGGED_IN_KEY' => 'put your unique phrase here',
	'NONCE_KEY' => 'put your unique phrase here',
	'AUTH_SALT' => 'put your unique phrase here',
	'SECURE_AUTH_SALT' => 'put your unique phrase here',
	'LOGGED_IN_SALT' => 'put your unique phrase here',
	'NONCE_SALT' => 'put your unique phrase here',
	'WP_DEBUG' => false
);

// Required environment variables
Dotenv::load( dirname( __DIR__ ) );
Dotenv::required( array( 'DB_NAME', 'DB_USER', 'DB_PASSWORD', 'WP_HOME', 'WP_SITEURL' ) );

// Constanize
$constants = array_merge( $defaultConstantValues, $_ENV );
foreach ( $constants as $constant => $value ) if ( !defined( $constant ) )
	define( $constant, $value );

// WordPress Database Table prefix
$table_prefix = DB_TABLEPREFIX;

// Language
if ( defined( 'WPLANG' ) )
	$wp_local_package = WP_LANG;

// HTTP_HOST fix
if ( php_sapi_name() == 'cli-server' )
	$_SERVER['HTTP_HOST'] = 'localhost:9000';

// Content directory
define( 'CONTENT_DIR', 'content' );
define( 'WP_CONTENT_DIR', dirname( __DIR__ ) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . CONTENT_DIR );
define( 'WP_CONTENT_URL', WP_HOME . '/' . CONTENT_DIR );

// Absolute path to the WordPress directory.
define( 'ABSPATH', dirname( __FILE__ ) . '/' );

// Init WordPress
require_once( ABSPATH . 'wp-settings.php' );
