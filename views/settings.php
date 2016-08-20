<?php

namespace Heartbeat_Control\Views;

// Bail if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

class Settings {

	public function init() {

		// @todo Check nonce
		if ( $_POST['hbc_save_settings'] ) {
			$this->save_settings();
		} elseif ( isset( $_POST['hbc_disable_interval'] ) ) {
			delete_option( 'hbc_interval' );

		} elseif ( isset( $_POST['hbc_enable_interval'] ) ) {
			update_option( 'hbc_interval', 'enabled' );
		}

		$this->display();
	}

	public function display() { ?>

		<h1>Heartbeat Control Settings</h1>
		<div id="heartbeat-control-settings">
			<form action="options-general.php?page=heartbeat-control" method="post">

			<h2>Global Settings</h2>
			<?php do_action('hbc_start_global_settings'); ?>

			<div class="column-left">
				Frontend Heartbeat:
			</div>
			<div class="column-right">
				<select name="hbc_frontend_allowed">
					<option value="wp_default" <?php selected( get_option( 'hbc_frontend_allowed' ), 'wp_default' )?>>
						WordPress Defaults
					</option>
					<option value="allowed" <?php selected( get_option( 'hbc_frontend_allowed' ), 'allowed' )?>>
						Allowed
					</option>
					<option value="denied" <?php selected( get_option( 'hbc_frontend_allowed' ), 'denied' )?>>
						Denied
					</option>
				</select>
			</div>
			<div class="column-left">
				Admin Heartbeat:
			</div>
			<div class="column-right">
				<select name="hbc_admin_allowed">
					<option value="wp_default" <?php selected( get_option( 'hbc_admin_allowed' ), 'wp_default' )?>>
						WordPress Defaults
					</option>
					<option value="allowed" <?php selected( get_option( 'hbc_admin_allowed' ), 'allowed' )?>>
						Allowed
					</option>
					<option value="denied" <?php selected( get_option( 'hbc_admin_allowed' ), 'denied' )?>>
						Denied
					</option>
				</select>
			</div>
			<div class="column-left">
				Interval (in seconds):
			</div>
			<div class="column-right">
				<?php if ( get_option( 'hbc_interval' ) ) : ?>
					<input name="hbc_interval" type="number" min="15" max="300" <?php if ( is_numeric( get_option('hbc_interval') ) ) { echo 'value="' . get_option('hbc_interval') . '"'; } ?>>
					<input type="submit" name="hbc_disable_interval" id="submit" class="button button-secondary" value="Remove Custom Interval"  />
				<?php else : ?>
					<input type="submit" name="hbc_enable_interval" id="submit" class="button button-secondary" value="Enable Custom Interval"  />
				<?php endif; ?>
			</div>
			<?php do_action('hbc_end_global_settings'); ?>
			<h2>Overrides</h2>
			<?php do_action('hbc_start_overrides_settings'); ?>

			<h3>Admin Locations</h3>
			<div class="column-left">
				Post Listing:
			</div>
			<div class="column-right">
				<select name="hbc_post_listing">
					<option value="wp_default" <?php selected( get_option( 'hbc_post_listing' ), 'wp_default' )?>>
						Use Global Settings
					</option>
					<option value="allowed" <?php selected( get_option( 'hbc_post_listing' ), 'allowed' )?>>
						Allowed
					</option>
					<option value="denied" <?php selected( get_option( 'hbc_post_listing' ), 'denied' )?>>
						Denied
					</option>
				</select>
			</div>
			<div class="column-left">
				Post Edit:
			</div>
			<div class="column-right">
				<select name="hbc_post_edit">
					<option value="wp_default" <?php selected( get_option( 'hbc_post_edit' ), 'wp_default' )?>>
						Use Global Settings
					</option>
					<option value="allowed" <?php selected( get_option( 'hbc_post_edit' ), 'allowed' )?>>
						Allowed
					</option>
					<option value="denied" <?php selected( get_option( 'hbc_post_edit' ), 'denied' )?>>
						Denied
					</option>
				</select>
			</div>

			<?php do_action('hbc_end_overrides_settings'); ?>
				<?php submit_button( 'Save Settings', 'primary', 'hbc_save_settings' ); ?>

				</form>
		</div>

	<?php
	}

	public function save_settings() {

		if ( is_string( $_POST['hbc_frontend_allowed'] ) ) {
			update_option( 'hbc_frontend_allowed', $_POST['hbc_frontend_allowed'] );
		}

		if ( is_string( $_POST['hbc_admin_allowed'] ) ) {
			update_option( 'hbc_admin_allowed', $_POST['hbc_admin_allowed'] );
		}

		if ( is_numeric( $_POST['hbc_interval'] ) ) {
			update_option( 'hbc_interval', intval( $_POST['hbc_interval'] ) );
		}

		if ( isset( $_POST['hbc_disable_interval'] ) ) {
			delete_option( 'hbc_interval' );
		}

		if ( isset( $_POST['hbc_enable_interval'] ) ) {
			add_option( 'hbc_interval' );
		}

		if ( is_string( $_POST['hbc_post_listing'] ) ) {
			update_option( 'hbc_post_listing', $_POST['hbc_post_listing'] );
		}

		if ( is_string( $_POST['hbc_post_edit'] ) ) {
			update_option( 'hbc_post_edit', $_POST['hbc_post_edit'] );
		}

	}

}