<?php

namespace Heartbeat_Control;
use Heartbeat_Control\Views\Settings;

if ( ! current_user_can( 'manage_options' ) || ! check_ajax_referer( 'hbc_settings_nonce', 'hbc_settings_nonce' ) ) {
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

		$response = apply_filters( 'hbc_after_convert_boolean_response', $response );

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

	public static function update_allowed() {
		ob_clean();

		$location = $_POST['hbc_data']['location'];

		switch ( $location ) {
			case 'admin':
				$success = update_option( 'hbc_admin_allowed', $_POST['hbc_data']['allowed'] );
				break;
			case 'frontend':
				$success = update_option( 'hbc_frontend_allowed', $_POST['hbc_data']['allowed'] );
				break;
			default:
				$success = false;
		}

		self::convert_boolean_response( $success );

		wp_die();
	}

	public static function update_interval() {
		ob_clean();

		$interval = intval( $_POST['hbc_data']['interval'] );
		$location = esc_attr( $_POST['hbc_data']['location'] );

		switch ( $location ) {
			case 'admin':
				$success = update_option( 'hbc_admin_interval', $interval );
				break;
			case 'frontend':
				$success = update_option( 'hbc_frontend_interval', $interval );
				break;
			default:
				$success = false;
		}

		self::convert_boolean_response( $success );

		wp_die();
	}

	public static function disable_interval() {
		ob_clean();

		$location = esc_attr( $_POST['hbc_data']['location'] );

		switch ( $location ) {
			case 'admin':
				delete_option( 'hbc_admin_interval' );
				break;
			case 'frontend':
				delete_option( 'hbc_frontend_interval' );
				break;
		}

		echo 'success';

		wp_die();
	}

}