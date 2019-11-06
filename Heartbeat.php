<?php
/**
 * Contains the Heartbeat_Control\Heartbeat class.
 *
 * @package Heartbeat_Control
 */

namespace Heartbeat_Control;

/**
 * Primary Hearbeat class.
 */
class Heartbeat {
	/**
	 * The current screen being accessed.
	 *
	 * @var string
	 */
	public $current_screen;

	/**
	 * The current query string being accessed.
	 *
	 * @var string
	 */
	public $current_query_string;

	/**
	 * Stores heartbeat settings across class methods.
	 *
	 * @var array
	 */
	public $settings = array();

	/**
	 * Constructor.
	 */
	public function __construct() {
		$_query_string = filter_input( INPUT_SERVER, 'QUERY_STRING', FILTER_SANITIZE_URL );
		$_request_uri  = filter_input( INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL );

		if ( $_query_string && $_request_uri ) {
			$current_url = wp_unslash( $_query_string . '?' . $_request_uri );
		} elseif ( $_query_string ) {
			$current_url = wp_unslash( $_request_uri );
		} else {
			$current_url = admin_url();
		}

		$this->current_screen = wp_parse_url( $current_url );
		if ( '/wp-admin/admin-ajax.php' === $this->current_screen ) {
			return;
		}

		$settings = get_option( 'heartbeat_control_settings' );
		if ( false === $settings ) {
			return;
		}

		$this->settings = $settings;

		add_action( 'admin_enqueue_scripts', array( $this, 'maybe_disable' ), 99 );
		add_action( 'wp_enqueue_scripts', array( $this, 'maybe_disable' ), 99 );
		add_filter( 'heartbeat_settings', array( $this, 'maybe_modify' ), 99, 1 );
	}

	/**
	 * Checks if the current location has a rule.
	 *
	 * @param  array $location Locations that have rules.
	 * @return bool
	 */
	public function check_location( $location ) {
		$location_test = array(
			'rules_dash'   => function() {
				return is_admin();
			},
			'rules_front'  => function() {
				return ! is_admin();
			},
			'rules_editor' => function() {
				return ( '/wp-admin/post.php' === $this->current_screen['path'] );
			},
		);

		if ( isset( $location_test[ $location ] ) ) {
			return $location_test[ $location ]();
		}

		return false;
	}

	/**
	 * Disable the heartbeat, if needed.
	 *
	 * @return void
	 */
	public function maybe_disable() {
		foreach ( $this->settings as $location => $r ) {
			$rule = reset( $r );
			if ( array_key_exists( 'heartbeat_control_behavior', $rule ) && 'disable' === $rule['heartbeat_control_behavior'] ) {
				if ( $this->check_location( $location ) ) {
					wp_deregister_script( 'heartbeat' );
					return;
				}
			}
		}
	}

	/**
	 * Modify the heartbeat, if needed.
	 *
	 * @param  array $settings The settings.
	 * @return array $settings Maybe an updated settings.
	 */
	public function maybe_modify( $settings ) {
		foreach ( $this->settings as $location => $r ) {
			$rule = reset( $r );

			if ( array_key_exists( 'heartbeat_control_behavior', $rule ) && 'modify' === $rule['heartbeat_control_behavior'] ) {
				if ( $this->check_location( $location ) ) {
					$settings['interval'] = intval( $rule['heartbeat_control_frequency'] );

					return $settings;
				}
			}
		}

		return $settings;
	}
}
