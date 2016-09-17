<?php
/**
 * Handles the display and functionality of the Settings page
 *
 * @since 2.0.0
 * @package \Heartbeat_Control\Views
 */

// Declare our namespace for the autoloader.
namespace Heartbeat_Control\Views;

// Bail if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Class Settings
 *
 * Primary class for the Settings page.
 *
 * @package Heartbeat_Control\Views
 * @since 2.0.0
 */
class Settings {

	/**
	 * Initializes the Settings page.
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function init() {

		// Save settings if needed.
		$this->maybe_save_settings();

		// Display the Settings page
		$this->display();
	}

	/**
	 * Determines if any settings need to be saved.
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function maybe_save_settings() {
		if ( filter_input( INPUT_POST, 'hbc_settings_sent', FILTER_SANITIZE_STRING ) !== null ) {
			check_admin_referer( 'hbc_settings_sent', 'hbc_settings_sent' );
			$this->save_settings();
		}
	}

	public static function get_overrides( $interface = null ) {

		$locations = array(
			'admin' => array(
				'posts' => array(
					'label' => 'Posts',
					'items' => array(
						'edit-post' => array(
							'label'   => 'Post Listing',
							'type'    => 'select',
							'options' => array(
								'wp_default' => 'WordPress Defaults',
								'allowed'    => 'Allowed',
								'denied'     => 'Denied',
							),
						),
						'post' => array(
							'label'   => 'Post Editor',
							'type'    => 'select',
							'options' => array(
								'wp_default' => 'WordPress Defaults',
								'allowed'    => 'Allowed',
								'denied'     => 'Denied',
							),
						),
						'edit-category' => array(
							'label'   => 'Category Manager',
							'type'    => 'select',
							'options' => array(
								'wp_default' => 'WordPress Defaults',
								'allowed'    => 'Allowed',
								'denied'     => 'Denied',
							),
						),
						'edit-post_tag' => array(
							'label'   => 'Tag Manager',
							'type'    => 'select',
							'options' => array(
								'wp_default' => 'WordPress Defaults',
								'allowed'    => 'Allowed',
								'denied'     => 'Denied',
							),
						),
					),
				),
			),
		);

		return $locations;

	}

	/**
	 * Displays the Settings page content.
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function display() { ?>

		<h1>Heartbeat Control Settings</h1>

		<div id="heartbeat-control-settings">

<!--			<form action="options-general.php?page=heartbeat-control" method="post">-->
				<?php wp_nonce_field( 'hbc_settings_sent', 'hbc_settings_sent' ); ?>

				<h2>Global Settings</h2>
				<?php do_action('hbc_start_global_settings'); ?>

				<h3>Frontend Locations</h3>

				<div class="column-left">Frontend Heartbeat:</div>

				<div class="column-right">
					<select title="" name="hbc_frontend_allowed" class="hbc_frontend_allowed">
						<option value="wp_default" <?php selected( get_option( 'hbc_frontend_allowed' ), 'wp_default' )?>>WordPress Defaults</option>
						<option value="allowed" <?php selected( get_option( 'hbc_frontend_allowed' ), 'allowed' )?>>Allowed</option>
						<option value="denied" <?php selected( get_option( 'hbc_frontend_allowed' ), 'denied' )?>>Denied</option>
					</select>
				</div>

				<div id="hbc_frontend_interval_container" style="display:none">
					<div class="column-left">Frontend Interval (in seconds):</div>

					<div class="column-right">
						<div id="hbc_frontend_interval_settings" style="display:none;">
							<input  id="hbc_frontend_interval_entry" title="" name="hbc_frontend_interval" type="number" min="15" max="300" <?php if ( is_numeric( get_option('hbc_frontend_interval') ) ) { echo 'value="' . get_option('hbc_frontend_interval') . '"'; } ?>>
							<button id="hbc_save_frontend_interval" class="button button-primary" style="display:none;">Save</button>
							<button id="hbc_disable_frontend_interval" class="button button-secondary" value="Remove Custom Interval">Disable Custom Interval</button>
						</div>
						<div id="hbc_enable_frontend_interval" style="display:none;">
							<button id="hbc_enable_frontend_interval_button" class="button button-secondary">Enable Custom Interval</button>
						</div>
					</div>
				</div>

				<h3>Admin Locations</h3>

				<div class="column-left">Admin Heartbeat:</div>

				<div class="column-right">
					<select title="" name="hbc_admin_allowed" class="hbc_admin_allowed">
						<option value="wp_default" <?php selected( get_option( 'hbc_admin_allowed' ), 'wp_default' )?>>WordPress Defaults</option>
						<option value="allowed" <?php selected( get_option( 'hbc_admin_allowed' ), 'allowed' )?>>Allowed</option>
						<option value="denied" <?php selected( get_option( 'hbc_admin_allowed' ), 'denied' )?>>Denied</option>
					</select>
				</div>

				<div id="hbc_admin_interval_container" style="display:none">
					<div class="column-left">Admin Interval (in seconds):</div>

					<div class="column-right">
						<div id="hbc_admin_interval_settings" style="display:none;">
							<input  id="hbc_admin_interval_entry" title="" name="hbc_admin_interval" type="number" min="15" max="300" <?php if ( is_numeric( get_option('hbc_admin_interval') ) ) { echo 'value="' . get_option('hbc_admin_interval') . '"'; } ?>>
							<button id="hbc_save_admin_interval" class="button button-primary" style="display:none;">Save</button>
							<button id="hbc_disable_admin_interval" class="button button-secondary" value="Remove Custom Interval">Disable Custom Interval</button>
						</div>
						<div id="hbc_enable_admin_interval" style="display:none;">
							<button id="hbc_enable_admin_interval_button" class="button button-secondary">Enable Custom Interval</button>
						</div>
					</div>

				</div>
					<?php if ( get_option( 'hbc_admin_interval' ) && get_option( 'hbc_admin_allowed' ) !== 'denied' ) : ?>
						<script>
							jQuery('#hbc_admin_interval_container').show();
							jQuery('#hbc_admin_interval_settings').show();
						</script>
					<?php elseif ( get_option( 'hbc_admin_allowed' ) !== 'denied' ) : ?>
						<script>
							jQuery('#hbc_admin_interval_container').show();
							jQuery('#hbc_enable_admin_interval').show();
						</script>
					<?php endif; ?>

			<?php if ( get_option( 'hbc_frontend_interval' ) && get_option( 'hbc_frontend_allowed' ) !== 'denied' ) : ?>
				<script>
					jQuery('#hbc_frontend_interval_container').show();
					jQuery('#hbc_frontend_interval_settings').show();
				</script>
			<?php elseif ( get_option( 'hbc_frontend_allowed' ) !== 'denied' ) : ?>
				<script>
					jQuery('#hbc_frontend_interval_container').show();
					jQuery('#hbc_enable_frontend_interval').show();
				</script>
			<?php endif; ?>






				<?php do_action('hbc_end_global_settings'); ?>

				<h2>Overrides</h2>

				<?php do_action('hbc_start_overrides_settings'); ?>

				<h3>Admin Locations</h3>

				<?php foreach ( self::get_overrides()['admin'] as $section_key => $section_data ) : ?>

					<?php foreach ( $section_data['items'] as $item_id => $item ) : ?>

						<div class="column-left"><?php esc_html_e( $item['label'] ) ?></div>

						<div class="column-right">

							<select title="" name="<?php esc_attr_e( $item_id ); ?>" data-subheader="<?php esc_attr_e( $section_key ); ?>" class="hbc_overrides">
								<?php foreach ( $item['options'] as $option => $label ) : ?>
									<?php $db_option_id = get_option( 'hbc_override_' . $item_id ); ?>
									<option value="<?php esc_attr_e( $option ); ?>" <?php selected( $db_option_id, $option ) ?>><?php esc_html_e( $label ); ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					<?php endforeach; ?>

				<?php endforeach; ?>

				<?php do_action('hbc_end_overrides_settings'); ?>

<!--			</form>-->

		</div>

	<?php
	}

	/**
	 * Saves any settings submitted.
	 *
	 * @since 2.0.0
	 * @access public
	 * @todo Move accepted values (allow/deny) into an array.
	 * @todo Make a filter for the above todo.
	 */
	public function save_settings() {

		$frontend_allowed     = filter_input( INPUT_POST, 'hbc_frontend_allowed', FILTER_SANITIZE_STRING );
		$admin_allowed        = filter_input( INPUT_POST, 'hbc_admin_allowed', FILTER_SANITIZE_STRING );
		$hbc_interval         = filter_input( INPUT_POST, 'hbc_interval', FILTER_SANITIZE_NUMBER_INT );
		$hbc_disable_interval = filter_input( INPUT_POST, 'hbc_disable_interval' );
		$hbc_enable_interval  = filter_input( INPUT_POST, 'hbc_enable_interval' );
		$hbc_post_listing     = filter_input( INPUT_POST, 'hbc_post_listing', FILTER_SANITIZE_STRING );
		$hbc_post_edit        = filter_input( INPUT_POST, 'hbc_post_edit', FILTER_SANITIZE_STRING );

		if ( $frontend_allowed == ( 'allowed' || 'denied' ) ) {
			update_option( 'hbc_frontend_allowed', $frontend_allowed );
		}

		if ( $admin_allowed == ( 'allowed' || 'denied' ) ) {
			update_option( 'hbc_admin_allowed', $admin_allowed );
		}

		$hbc_interval = intval( $hbc_interval );
		if ( ( $hbc_interval >= 15 ) && ( $hbc_interval <= 300 ) ) {
			update_option( 'hbc_interval', $hbc_interval );
		}

		if ( $hbc_disable_interval !== null ) {
			delete_option( 'hbc_interval' );
		}

		if ( $hbc_enable_interval !== null ) {
			update_option( 'hbc_interval', 'enabled' );
		}

		if ( $hbc_post_listing == ( 'allowed' || 'denied' ) ) {
			update_option( 'hbc_post_listing', $hbc_post_listing );
		}

		if ( $hbc_post_edit == ( 'allowed' || 'denied' ) ) {
			update_option( 'hbc_post_edit', $hbc_post_edit );
		}

	}

}
