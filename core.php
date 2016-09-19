<?php

namespace Heartbeat_Control;
use Heartbeat_Control\Views\Settings;

// Bail if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Class Core
 *
 * Contains core Heartbeat Control functionality
 *
 * @package Heartbeat_Control
 * @since 2.0.0
 */
class Core {

	/**
	 * Holds an instance of this class
	 *
	 * @since 2.0.0
	 * @static
	 * @access public
	 *
	 * @var object $instance Holds an instance of this class
	 */
	public static $instance;

	/**
	 * Gets an instance of the Core object
	 *
	 * @since 2.0.0
	 * @static
	 * @access public
	 *
	 * @return Core
	 */
	public static function get_instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	/**
	 * Initializes Heartbeat Control
	 *
	 * @since 2.0.0
	 * @access public
	 * @see Core::enqueue_actions
	 */
	public function init() {
		$this->enqueue_actions();
	}

	/**
	 * Enqueues all actions associated with Heartbeat Control
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function enqueue_actions() {
		do_action( 'hbc_before_enqueue_actions' );

		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'maybe_kill_heartbeat' ), 100 );
		add_action( 'admin_enqueue_scripts', array( $this, 'maybe_kill_heartbeat' ), 100 );
		add_filter( 'heartbeat_settings', array( $this, 'modify_heartbeat' ) );

		do_action( 'hbc_after_enqueue_actions' );
	}

	/**
	 * Enqueues scripts and styles needed by Heartbeat Control
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param null|string $hook Reflects the page being accessed. Defaults to null.
	 */
	public function enqueue_scripts( $hook = null ) {
		do_action( 'hbc_before_enqueue_scripts', $hook );

		if ( 'settings_page_heartbeat-control' == $hook ) {
			wp_enqueue_style( 'heartbeat-control-settings', plugin_dir_url( __FILE__ ) . 'css/settings.css' );
			wp_enqueue_style( 'font-awesome', plugin_dir_url( __FILE__ ) . 'css/includes/font-awesome.min.css' );
			wp_enqueue_script( 'heartbeat-control-settings', plugin_dir_url( __FILE__ ) . 'js/settings.js' );
			wp_localize_script( 'heartbeat-control-settings', 'hbc_plugin_url', plugin_dir_url( __FILE__ ) );
			wp_localize_script( 'heartbeat-control-settings', 'hbc_settings_nonce', wp_create_nonce( 'hbc_settings_nonce' ) );
		}

		do_action( 'hbc_after_enqueue_scripts', $hook );
	}

	/**
	 * Initialize the Heartbeat Control menu item
	 *
	 * @since 2.0.0 Rewrite
	 * @since 1.0
	 * @access public
	 */
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

	/**
	 * Runs the view when the menu item is accessed
	 *
	 * @since 2.0.0
	 * @access public
	 * @todo Conditionally load views
	 */
	public function heartbeat_control_menu() {
		$load_view = new Settings();
		$load_view->init();
	}

	/**
	 * Migrates database options to the current version
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function maybe_migrate_db_options() {

		$pre_2_0_heartbeat_location  = get_option( 'heartbeat_location' );
		$pre_2_0_heartbeat_frequency = get_option( 'heartbeat_frequency' );

		if ( $pre_2_0_heartbeat_location ) {

			if ( $pre_2_0_heartbeat_location == 'use_default' ) {
				update_option( 'hbc_admin_allowed', 'allowed' );

			} elseif ( $pre_2_0_heartbeat_location == ( 'disable-heartbeat-everywhere' || 'disable-heartbeat-dashboard' ) ) {
				update_option( 'hbc_admin_allowed', 'denied' );
			} elseif ( $pre_2_0_heartbeat_location == 'allow-heartbeat-post-edit' ) {
				update_option( 'hbc_admin_allowed', 'denied' );
				update_option( 'hbc_override_post', 'allowed' );
				update_option( 'hbc_override_post-edit', 'allowed' );
			}

			delete_option( 'heartbeat_location' );
		}

		if ( $pre_2_0_heartbeat_frequency ) {
			update_option( 'hbc_interval', $pre_2_0_heartbeat_frequency );
			delete_option( 'heartbeat_frequency' );
		}

	}

	/**
	 * Determines if the heartbeat should be killed
	 *
	 * @since 2.0.0
	 * @access public
	 * @todo Clean this up.
	 *
	 * @return bool True if the heartbeat was killed.  False, otherwise.
	 */
	public function maybe_kill_heartbeat() {
		$kill_heartbeat = false;

		if ( is_admin() ) {
			$current_screen = get_current_screen()->id;
			$default_enabled = $this->default_enabled();

			if ( $default_enabled == 'denied' ) {
				$kill_heartbeat = true;
			}

		} else {
			$default_enabled = $this->default_enabled();
			$current_screen = false;

			if ( $default_enabled == 'denied' ) {
				$kill_heartbeat = true;
			}
		}

		if ( get_option( 'hbc_override_' . $current_screen ) ) {
			$kill_heartbeat = true;
		}

		$kill_heartbeat = apply_filters( 'hbc_kill_heartbeat', $kill_heartbeat );

		if ( $kill_heartbeat === true ) {
			$this->kill_heartbeat();
		}

		return $kill_heartbeat;
	}

	/**
	 * Kill the heartbeat.  With fire.
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function kill_heartbeat() {
		do_action( 'hbc_before_kill_heartbeat' );
		wp_deregister_script( 'heartbeat' );
		do_action( 'hbc_after_kill_heartbeat' );
	}

	/**
	 * Determines if the heartbeat should be modified
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param array $settings Existing heartbeat settings
	 *
	 * @return array Heartbeat settings
	 */
	public function maybe_modify_heartbeat( $settings ) {

		$interval = get_option( 'hbc_interval' );

		if ( apply_filters( 'hbc_modify_heartbeat', $interval ) ) {
			return $this->modify_heartbeat( $settings, $interval );
		}

		return $settings;
	}

	/**
	 * Determines if the heartbeat should be fired.
	 *
	 * Uses global defaults.  Could be overridden later.
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return string Returns 'allowed' or 'denied' based on global settings set
	 */
	public function default_enabled() {

		if ( is_admin() ) {
			$global_default = get_option( 'hbc_admin_allowed' );
		} else {
			$global_default = get_option( 'hbc_frontend_allowed' );
		}

		$global_default = apply_filters( 'hbc_default_enabled', $global_default );
		return $global_default;
	}

	/**
	 * Gets the interval set globally
	 *
	 * Uses global defaults. Could be overwritten later.
	 *
	 * @since 2.0.0
	 * @access public
	 * @todo Cleanup
	 * @todo Validation
	 * @todo Hooks
	 *
	 * @return int|bool Returns number of seconds between requests. False if not set.
	 */
	public function default_interval() {

		$global_default = get_option( 'hbc_interval' );
		return $global_default;

	}

	/**
	 * Modifies the heartbeat interval.
	 *
	 * @since 2.0.0
	 * @access public
	 * @todo Is this needed?
	 * @todo Cleanup
	 * @todo Validation
	 * @todo Hook?
	 *
	 * @param array $settings Current heartbeat settings.
	 * @param bool  $interval If the interval should be modified when passing through. Defaults to false.
	 *
	 * @return array Heartbeat settings after modification.
	 */
	public function modify_heartbeat( $settings, $interval = false ) {

		if ( $interval ) {
			$settings['interval'] = $interval;
		}

		return $settings;

	}

	/**
	 * Determines if post edit pages are enabled.
	 *
	 * Likely to be removed later, but keeping to avoid removing functionality for non-premium users.
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return string|bool Returns 'allowed' or 'denied' based on heartbeat configuration.
	 */
	public function allowed_post_edit_pages() {

		$allowed = get_option( 'hbc_post_edit' );

		if ( $allowed ) {
			return $allowed;
		}

		return false;

	}

	/**
	 * Determines if post listing pages are enabled.
	 *
	 * Likely to be removed later, but keeping for backwards compatibility.
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return string|bool Returns 'allowed' or 'denied' based on heartbeat configuration.
	 */
	public function allowed_post_listing_pages() {
		$allowed = get_option( 'hbc_post_listing' );

		if ( $allowed ) {
			return $allowed;
		}

		return false;
	}

}
