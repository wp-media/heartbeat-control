<?php

namespace Heartbeat_Control;

use Heartbeat_Control\Views\Settings;

class Core {

	public static $instance;

	public static function get_instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	public function init() {
		$this->enqueue_actions();
	}

	public function enqueue_actions() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'maybe_kill_heartbeat' ), 100 );
		add_action( 'admin_enqueue_scripts', array( $this, 'maybe_kill_heartbeat' ), 100 );
		add_filter( 'heartbeat_settings', array( $this, 'modify_heartbeat' ) );
	}

	public function enqueue_scripts( $hook = null ) {
		if ( 'settings_page_heartbeat-control' == $hook ) {
			wp_enqueue_style( 'heartbeat-control-settings', plugin_dir_url( __FILE__ ) . 'css/settings.css' );
		}
	}

	public function admin_menu() {

		add_submenu_page(
			'options-general.php',
			__( 'Heartbeat Control', 'heartbeat-control' ),
			__( 'Heartbeat Control', 'heartbeat-control' ),
			'manage_options',
			'heartbeat-control',
			array( $this, 'heartbeat_control_menu')
		);

	}

	public function heartbeat_control_menu() {
		$load_view = new Settings();
		$load_view->init();
	}

	public function maybe_migrate_db_options() {

	}

	public function migrate_db() {

	}

	public function maybe_kill_heartbeat() {

		$kill_heartbeat = false;

		if ( is_admin() ) {
			$current_screen = get_current_screen();
			$default_enabled = $this->admin_default_enabled();
		} else {
			$default_enabled = $this->frontend_default_enabled();
		}

		if ( $default_enabled == 'denied' ) {
			$kill_heartbeat = true;
		}

		$kill_heartbeat = apply_filters( 'hbc_kill_heartbeat', $kill_heartbeat );

//		if ( $this->heartbeat_allowed_locations() ) {
//			$kill_heartbeat = false;
//		}
//
//		if ( $this->heartbeat_denied_locations() ) {
//			$kill_heartbeat = true;
//		}

		if ( is_admin() && $current_screen->id == 'post' ) {
			$allowed_post_edit_pages = $this->allowed_post_edit_pages();

			if ( $allowed_post_edit_pages == 'allowed' ) {
				$kill_heartbeat = false;
			} elseif ( $allowed_post_edit_pages == 'denied' ) {
				$kill_heartbeat = true;
			}
		} elseif ( is_admin() && $current_screen->id == 'edit-post' ) {
			$allowed_post_listing_pages = $this->allowed_post_listing_pages();

			if ( $allowed_post_listing_pages == 'allowed' ) {
				$kill_heartbeat = false;
			} elseif ( $allowed_post_listing_pages == 'denied' ) {
				$kill_heartbeat = true;
			}
		}

		if ( $kill_heartbeat === true ) {
			$this->kill_heartbeat();
		}

		return $kill_heartbeat;

	}

	public function kill_heartbeat() {
		wp_deregister_script( 'heartbeat' );
	}

	public function maybe_modify_heartbeat( $settings ) {

		$modify_heartbeat = false;

		if ( is_numeric( $this->default_interval() ) ) {
			$modify_heartbeat = $this->default_interval();
		}

		$modify_heartbeat = apply_filters( 'hbc_modify_heartbeat', $modify_heartbeat );

		if ( $modify_heartbeat ) {
			return $this->modify_heartbeat( $settings, $modify_heartbeat );
		}

		return $settings;
	}

	public function frontend_default_enabled() {

		$global_default = get_option( 'hbc_frontend_allowed' );

		if ( $global_default && ( $global_default == ( 'allowed' || 'denied' ) ) ) {
			return $global_default;
		} else {
			return 'allowed';
		}

	}

	public function admin_default_enabled() {

		$global_default = get_option( 'hbc_admin_allowed' );

		if ( $global_default && ( $global_default == ( 'allowed' || 'denied' ) ) ) {
			return $global_default;
		} else {
			return 'allowed';
		}

	}

	public function default_interval() {

		$global_default = get_option( 'hbc_default_interval' );

		if ( $global_default && is_numeric( $global_default ) ) {
			return $global_default;
		} else {
			return false;
		}

	}

	public function modify_heartbeat( $settings, $interval = false ) {

		if ( $interval ) {
			$settings['interval'] = $interval;
		}

		return $settings;

	}

//	public function heartbeat_allowed_locations() {
//
//		$allowed_locations = get_option( 'hbc_allowed_locations' );
//		$allowed_locations = json_decode( $allowed_locations );
//
//		$allowed = false;
//
//		if ( $allowed_locations !== null ) {
//
//			if ( is_admin() ) {
//
//				$screen = get_current_screen();
//				if ( in_array( $screen->id, $allowed_locations ) ) {
//
//					$allowed = true;
//
//				} else {
//
//					$allowed = false;
//
//				}
//
//			}
//
//		}
//
//		$allowed = apply_filters( 'hbc_allowed_locations', $allowed );
//
//		return $allowed;
//
//	}

//	public function heartbeat_denied_locations() {
//
//		$denied_locations = get_option( 'hbc_denied_locations' );
//		$denied_locations = json_decode( $denied_locations );
//
//		$denied = false;
//
//		if ( $denied_locations !== null ) {
//
//			if ( is_admin() ) {
//
//				$screen = get_current_screen();
//				if ( in_array( $screen->id, $denied_locations ) ) {
//
//					$denied = true;
//
//				} else {
//
//					$denied = false;
//
//				}
//
//			} else {
//
//				$current_object = get_queried_object();
//				$class = get_class( $current_object );
//
//				if ( $class == 'WP_Post' && property_exists( $denied_locations, 'posts' ) ) {
//					if ( in_array( $current_object->ID, $denied_locations->posts ) ) {
//						$denied = true;
//					}
//				}
//
//			}
//
//		}
//
//		$denied = apply_filters( 'hbc_denied_locations', $denied );
//
//		return $denied;
//
//	}

	public function allowed_post_edit_pages() {

		$allowed = get_option( 'hbc_post_edit' );

		if ( $allowed ) {
			return $allowed;
		}

		return false;

	}

	public function allowed_post_listing_pages() {
		$allowed = get_option( 'hbc_post_listing' );

		if ( $allowed ) {
			return $allowed;
		}

		return false;
	}

}