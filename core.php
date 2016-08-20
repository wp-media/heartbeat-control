<?php

namespace Heartbeat_Control;
use Heartbeat_Control\Views\Settings;

// Bail if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

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
	 * @todo Add hook
	 */
	public function enqueue_actions() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'maybe_kill_heartbeat' ), 100 );
		add_action( 'admin_enqueue_scripts', array( $this, 'maybe_kill_heartbeat' ), 100 );
		add_filter( 'heartbeat_settings', array( $this, 'modify_heartbeat' ) );
	}

	/**
	 * Enqueues scripts and styles needed by Heartbeat Control
	 *
	 * @since 2.0.0
	 * @access public
	 * @todo Add hook
	 *
	 * @param null|string $hook Reflects the page being accessed. Defaults to null.
	 */
	public function enqueue_scripts( $hook = null ) {
		if ( 'settings_page_heartbeat-control' == $hook ) {
			wp_enqueue_style( 'heartbeat-control-settings', plugin_dir_url( __FILE__ ) . 'css/settings.css' );
		}
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
	 * @todo Write database migration
	 */
	public function maybe_migrate_db_options() {

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
			$current_screen = get_current_screen();
			$default_enabled = $this->admin_default_enabled();
		} else {
			$default_enabled = $this->frontend_default_enabled();
		}

		if ( $default_enabled == 'denied' ) {
			$kill_heartbeat = true;
		}

		$kill_heartbeat = apply_filters( 'hbc_kill_heartbeat', $kill_heartbeat );

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

	/**
	 * Kill the heartbeat.  With fire.
	 *
	 * @since 2.0.0
	 * @access public
	 * @todo Hook here?
	 */
	public function kill_heartbeat() {
		wp_deregister_script( 'heartbeat' );
	}

	/**
	 * Determines if the heartbeat should be modified
	 *
	 * @since 2.0.0
	 * @access public
	 * @todo Clean this up
	 * @todo Add hooks
	 *
	 * @param array $settings Existing heartbeat settings
	 *
	 * @return array Heartbeat settings
	 */
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

	/**
	 * Determines if the heartbeat should be fired on the front-end
	 *
	 * Uses global defaults.  Could be overridden later.
	 *
	 * @since 2.0.0
	 * @access public
	 * @todo Hooks
	 * @todo Cleanup
	 *
	 * @return string Returns 'allowed' or 'denied' based on global settings set
	 */
	public function frontend_default_enabled() {

		$global_default = get_option( 'hbc_frontend_allowed' );

		if ( $global_default && ( $global_default == ( 'allowed' || 'denied' ) ) ) {
			return $global_default;
		} else {
			return 'allowed';
		}

	}

	/**
	 * Determines if the heartbeat is fired on the front-end.
	 *
	 * Uses global defaults. Could be overwritten later.
	 *
	 * @since 2.0.0
	 * @access public
	 * @todo Cleanup
	 * @todo Validation
	 * @todo Hooks
	 *
	 * @return string Returns 'allowed' or 'denied' based on global settings set.
	 */
	public function admin_default_enabled() {

		$global_default = get_option( 'hbc_admin_allowed' );

		if ( $global_default && ( $global_default == ( 'allowed' || 'denied' ) ) ) {
			return $global_default;
		} else {
			return 'allowed';
		}

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

		if ( $global_default && is_numeric( $global_default ) ) {
			return $global_default;
		} else {
			return false;
		}

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
