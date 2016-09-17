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

// Freemius helper function
function hbc_fs() {
	global $hbc_fs;

	if ( ! isset( $hbc_fs ) ) {
		// Include Freemius SDK.
		require_once dirname(__FILE__) . '/freemius/start.php';

		$hbc_fs = fs_dynamic_init( array(
			'id'                => '375',
			'slug'              => 'heartbeat-control',
			'type'              => 'plugin',
			'public_key'        => 'pk_704c21f74d1bea18f4aff700f9159',
			'is_premium'        => false,
			'has_addons'        => false,
			'has_paid_plans'    => false,
			'menu'              => array(
				'slug'       => 'heartbeat-control',
				'parent'     => array(
					'slug' => 'options-general.php',
				),
			),
		) );
	}

	return $hbc_fs;
}

// Init Freemius.
hbc_fs();

// Bail if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! defined( 'HEARTBEAT_CONTROL_DIR' ) ) {
	define( 'HEARTBEAT_CONTROL_DIR', plugin_dir_path( __FILE__ ) );
}

require_once HEARTBEAT_CONTROL_DIR . '/autoloader.php';

add_action( 'init', 'heartbeat_control_init' );

/**
 * Initializes Heartbeat Control
 *
 * @since 2.0.0
 */
function heartbeat_control_init() {
	\Heartbeat_Control\Core::get_instance()->init();
}

add_action( 'wp_ajax_hbc_update_admin_allowed', array( 'Heartbeat_Control\Ajax', 'update_admin_allowed' ) );
add_action( 'wp_ajax_hbc_update_admin_interval', array( 'Heartbeat_Control\Ajax', 'update_admin_interval' ) );
add_action( 'wp_ajax_hbc_update_frontend_allowed', array( 'Heartbeat_Control\Ajax', 'update_frontend_allowed' ) );
add_action( 'wp_ajax_hbc_update_frontend_interval', array( 'Heartbeat_Control\Ajax', 'update_frontend_interval' ) );
add_action( 'wp_ajax_hbc_update_override', array( 'Heartbeat_Control\Ajax', 'update_override' ) );
add_action( 'wp_ajax_hbc_disable_admin_interval', array( 'Heartbeat_Control\Ajax', 'disable_admin_interval' ) );
add_action( 'wp_ajax_hbc_disable_frontend_interval', array( 'Heartbeat_Control\Ajax', 'disable_frontend_interval' ) );
