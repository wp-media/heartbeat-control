<?php

// @todo Consolidation

namespace Heartbeat_Control;
use Heartbeat_Control\Views\Settings;

if ( ! current_user_can( 'manage_options' ) && ! check_ajax_referer( 'hbc_settings_nonce', 'hbc_settings_nonce' ) ) {
	echo 'error';
	wp_die();
}

class Ajax {

	/**
	 * Converts a boolean to a string response for AJAX calls.
	 *
	 * @since 2.0.0
	 * @access public
	 * @static
	 *
	 * @param mixed $check The value to check.
	 * @param bool $echo If the response should be echoed.  Defaults to true.
	 *
	 * @return string The response string.
	 */
	public static function convert_boolean_response( $check, $echo = true ) {

		if ( $check ) {
			$response = 'success';
		} else {
			$response = 'error';
		}

		if ( $echo == true ) {
			echo $response;
		} else {
			return $response;
		}

	}

	public static function update_override() {
		ob_clean();

		$overrides = Settings::get_overrides();
		$subheader = $_POST['hbc_data']['override_subheader'];
		$override_name = $_POST['hbc_data']['override_name'];

		if ( ( array_key_exists( $subheader, $overrides['admin'] ) ) && ( array_key_exists( $override_name, $overrides['admin'][$subheader]['items'] ) ) ) {
			$success = update_option( 'hbc_override_' . $override_name, $_POST['hbc_data']['override_value'] );

			self::convert_boolean_response( $success );

		} else {
			echo 'error';
		}

		wp_die();
	}

	public static function update_frontend_allowed() {
		ob_clean();

		$allowed = $_POST['hbc_data']['frontend_allowed'];

		if ( $allowed == ( 'allowed' || 'denied' || 'wp_default' ) ) {
			$success = update_option( 'hbc_frontend_allowed', $_POST['hbc_data']['frontend_allowed'] );

			self::convert_boolean_response( $success );

		} else {
			echo 'error';
		}

		wp_die();

	}

	public static function update_admin_allowed() {
		ob_clean();

		$allowed = $_POST['hbc_data']['admin_allowed'];

		if ( $allowed == ( 'allowed' || 'denied' || 'wp_default' ) ) {
			$success = update_option( 'hbc_admin_allowed', $_POST['hbc_data']['admin_allowed'] );

			self::convert_boolean_response( $success );

		} else {
			echo 'error';
		}

		wp_die();

	}

	public static function update_frontend_interval() {
		ob_clean();

		$interval = $_POST['hbc_data']['frontend_interval'];

		if ( is_numeric( $interval ) ) {
			$success = update_option( 'hbc_frontend_interval', $_POST['hbc_data']['frontend_interval'] );

			self::convert_boolean_response( $success );

		} else {
			echo 'error';
		}

		wp_die();

	}

	public static function update_admin_interval() {
		ob_clean();

		$interval = $_POST['hbc_data']['admin_interval'];

		if ( is_numeric( $interval ) ) {
			$success = update_option( 'hbc_admin_interval', intval( $_POST['hbc_data']['admin_interval'] ) );

			self::convert_boolean_response( $success );

		} else {
			echo 'error';
		}

		wp_die();

	}

	public static function disable_admin_interval() {
		ob_clean();

		delete_option( 'hbc_admin_interval' );

		echo 'success';

		wp_die();

	}

	public static function disable_frontend_interval() {
		ob_clean();

		delete_option( 'hbc_frontend_interval' );

		echo 'success';

		wp_die();

	}

}