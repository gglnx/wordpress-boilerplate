/**
 * @package     gglnx/kesselblech
 * @link        https://github.com/gglnx/kesselblech
 * @author      Dennis Morhardt <info@dennismorhardt.de>
 * @copyright   Copyright 2015, Dennis Morhardt
 * @licence     MIT
 */

/**
 * RequireJS configuration
 */
requirejs.config({
	paths: {
		'jquery': '../components/jquery/dist/jquery.min'
	},
	shim: {

	}
});

/**
 * Load our application
 */
require([
	//'components/foo-bar'
]);
