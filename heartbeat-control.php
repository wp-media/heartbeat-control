<?php
/**
 * Plugin Name: Heartbeat Control
 * Plugin URI: http://jeffmatson.net/heartbeat-killer
 * Description: Completely controls the WordPress heartbeat.
 * Version: 1.0
 * Author: Jeff Matson
 * Author URI: http://jeffmatson.net
 * License: GPL2
 */
if ( is_admin() && current_user_can( 'manage_options' ) ) {
	add_action('admin_menu', 'heartbeat_control_menu');
	/**
	 * heartbeat_control_menu function.
	 *
	 * @access public
	 * @return void
	 */
	function heartbeat_control_menu()
	{

		add_submenu_page(
			'tools.php',
			__('Heartbeat Control', 'heartbeat-control'),
			__('Heartbeat Control', 'heartbeat-control'),
			'manage_options',
			'heartbeat-control',
			'heartbeat_control_menu',
			99
		);
	}

}