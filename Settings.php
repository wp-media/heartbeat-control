<?php
/**
 * Contains the Heartbeat_Control\Settings class.
 *
 * @package Heartbeat_Control
 */

namespace Heartbeat_Control;

defined( 'ABSPATH' ) || die( 'Cheatin\' uh?' );

/**
 * Admin page handler class
 */
class Settings {
	/**
	 * A array of plugin card.
	 *
	 * @var array of Plugin_Card_Helper Object
	 */
	protected $plugins_block = array();

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'cmb2_render_slider', array( $this, 'render_slider_field' ), 10, 5 );

		// we need this objects to declare there controller right now.
		$imagify_partner = new Imagify_Partner( 'heartbeat-control' );
		$imagify_partner->init();
		$this->plugins_block = array(
			'rocket-lazy-load'  => new Plugin_Card_Helper(
				array(
					'plugin_slug' => 'rocket-lazy-load',
					'params'      => array(
						'title' => 'LazyLoad',
					),
				)
			),
			'wp-rocket'         => new Plugin_Card_Helper(
				array(
					'plugin_slug' => 'wp-rocket',
					'params'      => array(
						'icon'        => '<img src="' . HBC_PLUGIN_URL . 'assets/img/logo-rocket.jpg" alt="">',
						'title'       => 'WP Rocket',
						'description' => sprintf(
							// translators: %1$s %2$s: link markup.
							esc_html__( 'Integrate more than 80&#x25; of web performance good practices automatically to %1$sreduce your website\'s loading time.%2$s', 'heartbeat-control' ),
							'<strong>',
							'</strong>'
						),
						'install_url' => array(
							'not_installed' => 'https://wp-rocket.me/?utm_source=wp_plugin&utm_medium=heartbeat_control',
						),
						'button_text' => array(
							'not_installed' => __( 'Get WP Rocket', 'heartbeat-control' ),
						),
					),
				)
			),
			'imagify'           => new Plugin_Card_Helper(
				array(
					'plugin_slug' => 'imagify',
					'params'      => array(
						'title'       => 'Imagify',
						'description' => sprintf(
							// translators: %1$s: line break, %2$s %3$s: bold markup.
							esc_html__( '%2$sReduces image file sizes%3$s without losing quality.%1$sBy compressing your images you speed up your website and boost your SEO.', 'heartbeat-control' ),
							'<br>',
							'<strong>',
							'</strong>'
						),
					),
				)
			),
		);
	}

	/**
	 * HOOKED, Slider field render.
	 *
	 * Refer to the links for documentation on cmb2 cmb2_render_<field_type> hook
	 * https://github.com/CMB2/CMB2/wiki
	 * http://hookr.io/plugins/cmb2/2.2.3.beta/actions/cmb2_render_fieldtype/
	 *
	 * @param obj CMB2_Field $field               see CMB2 wiki.
	 * @param mixed          $field_escaped_value unused.
	 * @param integer        $field_object_id     unused.
	 * @param string         $field_object_type   unused.
	 * @param obj CMB2_Types $field_type_object   see CMB2 wiki.
	 * @return void
	 */
	public function render_slider_field( $field, $field_escaped_value, $field_object_id, $field_object_type, $field_type_object ) {
		echo '<div class="slider-field"></div>';
		echo $field_type_object->input( // phpcs:ignore WordPress.Security.EscapeOutput
			array(
				'type'       => 'hidden',
				'class'      => 'slider-field-value',
				'readonly'   => 'readonly',
				'data-start' => absint( $field_escaped_value ),
				'data-min'   => intval( $field->min() ),
				'data-max'   => intval( $field->max() ),
				'data-step'  => intval( $field->step() ),
				'desc'       => '',
			)
		);
		echo '<span class="slider-field-value-display">' . esc_html( $field->value_label() ) . ' <span class="slider-field-value-text"></span></span>';
		$field_type_object->_desc( true, true );
	}

	/**
	 * Option admin page controller.
	 *
	 * @param  obj $hookup CMB2_hookup.
	 * @return void
	 */
	public function admin_controller_options( $hookup ) {
		$cmb_form        = cmb2_metabox_form(
			$hookup->cmb,
			$hookup->cmb->cmb_id,
			array(
				'echo'        => false,
				'save_button' => __( 'Save changes', 'heartbeat-control' ),
			)
		);
		$plugins_block   = $this->plugins_block;
		$asset_image_url = HBC_PLUGIN_URL . 'assets/img/';
		$notices         = Notices::get_instance();
		include HBC_PLUGIN_PATH . 'views/admin-page.php';
	}

	/**
	 * Option admin page enqueue script and style.
	 *
	 * @param  string $hook Use for context validation.
	 * @return void
	 */
	public function enqueue_scripts( $hook ) {
		if ( 'settings_page_heartbeat_control_settings' !== $hook ) {
			return;
		}

		wp_register_script( 'hbc_admin_script', HBC_PLUGIN_URL . 'assets/js/script.js', array( 'jquery', 'jquery-ui-slider' ), HBC_VERSION, false );
		wp_enqueue_script( 'hbc_admin_script' );
		wp_register_style( 'slider_ui', '//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.min.css', array(), '1.12.1' );
		wp_enqueue_style( 'slider_ui' );
		wp_register_style( 'hbc_admin_style', HBC_PLUGIN_URL . 'assets/css/style.min.css', array(), HBC_VERSION );
		wp_enqueue_style( 'hbc_admin_style' );

	}

	/**
	 * Declare cmb2 metaboxes.
	 *
	 * @return void
	 */
	public function init_metaboxes() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action(
			'cmb2_save_options-page_fields',
			function( $object_id, $cmb_id, $updated, $t ) {
				if ( 'heartbeat_control_settings' === $object_id && $updated ) {
					$notices = Notices::get_instance();
					$notices->append( 'success', __( 'Your changes have been saved successfully!', 'heartbeat-control' ) );
				}
			},
			10,
			4
		);

		$behavior = array(
			'name'    => __( 'Heartbeat Behavior', 'heartbeat-control' ),
			'id'      => 'heartbeat_control_behavior',
			'type'    => 'radio_inline',
			'default' => 'allow',
			'classes' => 'heartbeat_behavior',
			'options' => array(
				'allow'   => __( 'Allow Heartbeat', 'heartbeat-control' ),
				'disable' => __( 'Disable Heartbeat', 'heartbeat-control' ),
				'modify'  => __( 'Modify Heartbeat', 'heartbeat-control' ),
			),
		);

		$frequency = array(
			'name'    => __( 'Override Heartbeat frequency', 'heartbeat-control' ),
			'id'      => 'heartbeat_control_frequency',
			'type'    => 'slider',
			'min'     => '15',
			'step'    => '1',
			'max'     => '300',
			'default' => '15',
			'classes' => 'heartbeat_frequency',
		);

		$cmb_options = new_cmb2_box(
			array(
				'id'           => 'heartbeat_control_settings',
				'title'        => __( 'Heartbeat Control', 'heartbeat-control' ),
				'object_types' => array( 'options-page' ),
				'option_key'   => 'heartbeat_control_settings',
				'capability'   => 'manage_options',
				'parent_slug'  => 'options-general.php',
				'display_cb'   => array( $this, 'admin_controller_options' ),
			)
		);

		$dash_group = $cmb_options->add_field(
			array(
				'id'         => 'rules_dash',
				'type'       => 'group',
				'repeatable' => false,
				'options'    => array(
					'group_title' => '<span class="dashicons dashicons-dashboard"></span> ' . __( 'WordPress Dashboard', 'heartbeat-control' ),
				),
			)
		);
		$cmb_options->add_group_field( $dash_group, $behavior );
		$cmb_options->add_group_field( $dash_group, $frequency );

		$front_group = $cmb_options->add_field(
			array(
				'id'         => 'rules_front',
				'type'       => 'group',
				'repeatable' => false,
				'options'    => array(
					'group_title' => '<span class="dashicons dashicons-admin-appearance"></span> ' . __( 'Frontend', 'heartbeat-control' ),
				),
			)
		);
		$cmb_options->add_group_field( $front_group, $behavior );
		$cmb_options->add_group_field( $front_group, $frequency );

		$editor_group = $cmb_options->add_field(
			array(
				'id'         => 'rules_editor',
				'type'       => 'group',
				'repeatable' => false,
				'options'    => array(
					'group_title' => '<span class="dashicons dashicons-admin-post"></span> ' . __( 'Post editor', 'heartbeat-control' ),
				),
			)
		);
		$cmb_options->add_group_field( $editor_group, $behavior );
		$cmb_options->add_group_field( $editor_group, $frequency );

	}

}
