<?php
/**
 * Handles the automatic loading of classes.
 *
 * @package \Heartbeat_Control
 * @since 2.0.0
 */

// Bail if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Callback function for spl_autoload_register.
 *
 * @since 2.0.0
 *
 * @param string $classname The name of the class to load.
 */
function heartbeat_control_autoloader( $classname ) {

	$class = str_replace( '\\', DIRECTORY_SEPARATOR, str_replace( '_', '-', strtolower( $classname ) ) );
	$file_path = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . $class . '.php';

	if ( file_exists( $file_path ) ) {
		require_once $file_path;
	}

}

spl_autoload_register( 'heartbeat_control_autoloader' );
