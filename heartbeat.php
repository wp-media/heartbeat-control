<?php

namespace Heartbeat_Control;

class Heartbeat {

	public $current_screen;
	public $current_query_string;
	public $settings = array();

	public function __construct() {

		if ( ! empty( $_SERVER['QUERY_STRING'] ) ) {
			$current_url = $_SERVER['REQUEST_URI'] . '?' . $_SERVER['QUERY_STRING'];
		}  else {
			$current_url = $_SERVER['REQUEST_URI'];
		}

		$this->current_screen = parse_url( $current_url );

		if ( $this->current_screen == '/wp-admin/admin-ajax.php' ) {
			return;
		}

		$settings = get_option( 'heartbeat_control_settings' );

		if ( ( ! is_array( $settings['rules'] ) ) || ( empty( $settings['rules'] ) ) ) {
			return;
		}

		array_reverse( $settings['rules'] );
		$this->settings = $settings['rules'];

		add_action( 'admin_enqueue_scripts', array( $this, 'maybe_disable' ), 99 );
		add_action( 'wp_enqueue_scripts', array( $this, 'maybe_disable' ), 99 );
		add_filter( 'heartbeat_settings', array( $this, 'maybe_modify' ), 99, 1 );
	}

	public function check_location( $locations ) {
		if ( in_array( $this->current_screen['path'], $locations ) ) {
			return true;
		} elseif ( ( ! is_admin() ) && ( in_array( 'frontend', $locations ) ) ) {
			return true;
		} elseif ( ( is_admin() ) && ( in_array( 'admin', $locations ) ) ) {
			return true;
		}

		return false;
	}

	public function maybe_disable() {
		foreach ( $this->settings as $rule ) {
			if ( array_key_exists( 'heartbeat_control_behavior', $rule ) && $rule['heartbeat_control_behavior']  === 'disable' ) {
				if ( $this->check_location( $rule['heartbeat_control_location'] ) ) {
					wp_deregister_script( 'heartbeat' );
					return;
				}
			}
		}

	}

	public function maybe_modify( $settings ) {

		foreach ( $this->settings as $rule ) {
			if ( array_key_exists( 'heartbeat_control_behavior', $rule ) && $rule['heartbeat_control_behavior'] === 'modify' ) {
				if ( $this->check_location( $rule['heartbeat_control_location'] ) ) {
					$settings['interval'] = intval( $rule['heartbeat_control_frequency'] );
					return $settings;
				}
			}
		}

		return $settings;
	}

}
