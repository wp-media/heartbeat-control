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
	function heartbeat_control_menu() { ?>

		<div class="wrap" >

			<h1> Heartbeat Control configuration </h1>

				<form method = "post" action = "<?php admin_url( 'tools.php?page=heartbeat-control' ); ?>" >

					<table class="form-table">
						<tr valign="top">
							<th scope="row"></th>
							<td>
								<label>

								</label>
							</td>
						</tr>
					</table>
					<?php submit_button(); ?>
				</form>


	<?php }