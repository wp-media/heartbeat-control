<?php
/**
 * Plugin Name: Heartbeat Control
 * Plugin URI: http://jeffmatson.net/heartbeat-control
 * Description: Completely controls the WordPress heartbeat.
 * Version: 2.0.0
 * Author: Jeff Matson
 * Author URI: http://jeffmatson.net
 * License: GPL2
 */

// Bail if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! defined( 'HEARTBEAT_CONTROL_DIR' ) ) {
	define( 'HEARTBEAT_CONTROL_DIR', plugin_dir_path( __FILE__ ) );
}

require_once HEARTBEAT_CONTROL_DIR . '/functions.php';

require_once HEARTBEAT_CONTROL_DIR . '/autoloader.php';

add_action( 'init', 'heartbeat_control_init' );

function heartbeat_control_init() {
	\Heartbeat_Control\Core::get_instance()->init();
}