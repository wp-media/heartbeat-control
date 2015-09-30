<?php

	add_action( 'admin_menu', 'heartbeat_control_menu_page' );
	/**
	 * heartbeat_control_menu function.
	 *
	 * @access public
	 * @return void
	 */
	function heartbeat_control_menu_page() {

		add_submenu_page(
			'options-general.php',
			__( 'Heartbeat Control', 'heartbeat-control' ),
			__( 'Heartbeat Control', 'heartbeat-control' ),
			'manage_options',
			'heartbeat-control',
			'heartbeat_control_menu',
			99
		);
	}

	function heartbeat_control_menu() {

		$directory = plugin_dir_path( __FILE__ );

		require_once $directory . '/heartbeat-control-options.php';
		?>

		<?php if ( isset( $_POST['heartbeat_location'] ) && in_array( $_POST['heartbeat_location'], $heartbeat_control_options ) ) {
			update_option( 'heartbeat_location', $_POST['heartbeat_location'] );
		}?>

		<?php if ( isset( $_POST['heartbeat_frequency'] ) && in_array( $_POST['heartbeat_frequency'], $heartbeat_frequency_options ) ) {
			update_option( 'heartbeat_frequency', $_POST['heartbeat_frequency'] );
		}?>

		<div class="wrap">

		<h1> Heartbeat Control configuration </h1>

		<form method="post" action="<?php admin_url( 'tools.php?page=heartbeat-control' ); ?>">

			<table class="form-table">
				<tr valign="top">
					<th scope="row">Control heartbeat locations:</th>

					<?php $heartbeat_setting = get_option( 'heartbeat_location' ) ?>

					<td>
						<label>
							<select name="heartbeat_location">

								<?php foreach ( $heartbeat_control_options as $options => $setting_value ) : ?>

									<option value="<?php echo $setting_value ?>"
										<?php selected( $setting_value, $heartbeat_setting ); ?>>
										<?php echo esc_html( $options ); ?>
									</option>

								<?php endforeach; ?>

							</select>
						</label>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Override heartbeat frequency:</th>

					<?php $heartbeat_frequency = get_option( 'heartbeat_frequency' ) ?>

					<td>
						<label>
							<select name="heartbeat_frequency">

								<?php foreach ( $heartbeat_frequency_options as $options => $setting_value ) : ?>

									<option value="<?php echo $setting_value ?>"
										<?php selected( $setting_value, $heartbeat_frequency ); ?>>
										<?php echo esc_html( $options ); ?>
									</option>

								<?php endforeach; ?>

							</select>
						</label>
					</td>
				</tr>
			</table>
			<?php submit_button(); ?>
        </form>
        <p><strong>Did this plugin help you?  Please consider donating to help keep Heartbeat Control updated:</strong></p>
        <a class="button button-primary" href="http://jeffmatson.net/donate">Donate</a>
	<?php
	}