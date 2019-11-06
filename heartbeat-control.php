<?php
/**
 * Plugin Name: Heartbeat Control by WP Rocket
 * Plugin URI: https://wordpress.org/plugins/heartbeat-control/
 * Description: Completely controls the WordPress heartbeat.
 * Version: 2.0
 * Author: WP Rocket
 * Author URI: https://wp-rocket.me
 * License: GPL2
 * Text Domain: heartbeat-control
 *
 * @package Heartbeat_Control
 */

namespace Heartbeat_Control;

define( 'HBC_VERSION', '2.0' );
define( 'HBC_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'HBC_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once dirname( __FILE__ ) . '/vendor/autoload.php';

/**
 * The primary Heartbeat Control class.
 */
class Heartbeat_Control {
	/**
	 * The current version.
	 *
	 * @var string
	 */
	public $version = HBC_VERSION;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->register_dependencies();
		$this->maybe_upgrade();
		new Heartbeat();
	}

	/**
	 * Register additional plugin dependencies.
	 *
	 * @return void
	 */
	public function register_dependencies() {
		// Initialize CMB2 for the new settings page.
		require_once dirname( __FILE__ ) . '/vendor/cmb2/cmb2/init.php';
		add_action( 'cmb2_admin_init', array( new Settings(), 'init_metaboxes' ) );
	}

	/**
	 * Check the version and update as needed.
	 *
	 * @return void
	 */
	public function maybe_upgrade() {
		$db_version = get_option( 'heartbeat_control_version', '1.0' );
		if ( version_compare( $db_version, $this->version, '<' ) ) {
			$this->upgrade_db( $db_version );
		}
	}

	/**
	 * Upgrades the database from older versions.
	 *
	 * @param  string $version The current DB version.
	 * @return void
	 */
	public function upgrade_db( $version ) {
		if ( version_compare( $version, '1.1', '<' ) ) {

			$updated_options = array();

			if ( get_option( 'heartbeat_location' ) === $old_location ) {
				if ( 'disable-heartbeat-everywhere' === $old_location ) {
					$updated_options['heartbeat_control_behavior'] = 'disable';
					$updated_options['heartbeat_control_location'] = array( 'frontend', 'admin', '/wp-admin/post.php' );
				} elseif ( 'disable-heartbeat-dashboard' === $old_location ) {
					$updated_options['heartbeat_control_behavior'] = 'disable';
					$updated_options['heartbeat_control_location'] = array( 'admin' );
				} elseif ( 'allow-heartbeat-post-edit' === $old_location ) {
					$updated_options['heartbeat_control_behavior'] = 'allow';
					$updated_options['heartbeat_control_location'] = array( '/wp-admin/post.php' );
				} else {
					if ( get_option( 'heartbeat_frequency' ) === $old_frequency ) {
						$updated_options['heartbeat_control_behavior']  = 'modify';
						$updated_options['heartbeat_control_location']  = array( 'frontend', 'admin', '/wp-admin/post.php' );
						$updated_options['heartbeat_control_frequency'] = $old_frequency;
					}
				}
			}

			update_option( 'heartbeat_control_settings', $updated_options );
		}

		if ( version_compare( $version, '1.2', '<' ) && ! array_key_exists( 'rules', get_option( 'heartbeat_control_settings' ) ) ) {
			$original_settings = get_option( 'heartbeat_control_settings' );
			update_option( 'heartbeat_control_settings', array( 'rules' => array( $original_settings ) ) );
		}

		/*
		 * In version 1.3.0 we remove the ordering and overwriting of rules,
		 * you can have only one behavior for each location now, it simpler and less misleading.
		 * So this code check for rules by location and take one for each based on there order.
		 */
		if ( version_compare( $version, '2.0', '<' ) ) {
			$os          = get_option( 'heartbeat_control_settings', array() );
			$new_mapping = array(
				array(
					'heartbeat_control_behavior'  => 'allow',
					'heartbeat_control_frequency' => 0,
				),
			);
			$ns          = array(
				'rules_dash'   => $new_mapping,
				'rules_front'  => $new_mapping,
				'rules_editor' => $new_mapping,
			);

			if ( ! isset( $os['rules'] ) || empty( $os['rules'] ) ) {
				update_option( 'heartbeat_control_settings', $ns );
			} else {
				$v = array( false, false, false );

				foreach ( $os['rules'] as $rules ) {
					foreach ( $rules['heartbeat_control_location'] as $location ) {
						if ( 'frontend' === $location && false === $v[0] ) {
							$ns['rules_front'] = array(
								array(
									'heartbeat_control_behavior' => $rules['heartbeat_control_behavior'],
									'heartbeat_control_frequency' => $rules['heartbeat_control_frequency'],
								),
							);
							$v[0]              = true;
						}

						if ( 'admin' === $location && false === $v[1] ) {
							$ns['rules_dash'] = array(
								array(
									'heartbeat_control_behavior' => $rules['heartbeat_control_behavior'],
									'heartbeat_control_frequency' => $rules['heartbeat_control_frequency'],
								),
							);
							$v[1]             = true;
						}

						if ( '/wp-admin/post.php' === $location && false === $v[2] ) {
							$ns['rules_editor'] = array(
								array(
									'heartbeat_control_behavior' => $rules['heartbeat_control_behavior'],
									'heartbeat_control_frequency' => $rules['heartbeat_control_frequency'],
								),
							);
							$v[2]               = true;
						}

						if ( ! in_array( false, $v ) ) { // phpcs:ignore WordPress.PHP.StrictInArray
							break 2;
						}
					}
				}
			}

			update_option( 'heartbeat_control_settings', $ns );
		}

		update_option( 'heartbeat_control_version', $this->version );

		$notices = Notices::get_instance();
		$notices->append( 'success', __( 'Heartbeat Control data have been migrated successfully!', 'heartbeat-control' ) );
	}

}

new Heartbeat_Control();
