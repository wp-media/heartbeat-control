<?php
namespace Heartbeat_Control;

defined( 'ABSPATH' ) || die( 'Cheatin\' uh?' );

/**
 * Class Plugin_Card_Helper
 * This check plugin info from plugins_api and help to build a functional installation plugin card.
 *
 * @package Heartbeat_Control
 */
class Plugin_Card_Helper {

	/**
	 * Store nonce action.
	 *
	 * @var string
	 * @access protected
	 */
	protected $nonce = 'plugin_card_helper_wpnonce';

	/**
	 * Store plugin slug.
	 *
	 * @var string
	 * @access protected
	 */
	protected $plugin_slug;

	/**
	 * Store plugin's main file path.
	 *
	 * @var string
	 * @access protected
	 */
	protected $plugin_file_path;

	/**
	 * Store plugins_api result, all plugin information from WordPress plugin repository.
	 *
	 * @var array
	 * @access protected
	 */
	protected $plugin_information;

	/**
	 * Boolean is plugin is activated.
	 *
	 * @var boolean
	 * @access protected
	 */
	protected $activated;

	/**
	 * Boolean is plugin is installed.
	 *
	 * @var boolean
	 * @access protected
	 */
	protected $installed;

	/**
	 * Boolean is plugin is compatible with installed WordPress version.
	 *
	 * @var boolean
	 * @access protected
	 */
	protected $wp_compatibility;

	/**
	 * Boolean is plugin is compatible with installed php version.
	 *
	 * @var boolean
	 * @access protected
	 */
	protected $php_compatibility;

	/**
	 * Boolean is plugin can be install.
	 *
	 * @var boolean
	 * @access protected
	 */
	protected $can_install;

	/**
	 * Store setup arguments.
	 *
	 * @var array
	 * @access protected
	 */
	protected $args;

	/**
	 * Store overwrite variables.
	 *
	 * @var array
	 * @access protected
	 */
	protected $params = array(
		'title'       => null,
		'description' => null,
		'icon'        => null,
		'status_text' => null,
		'button_text' => null,
		'install_url' => null,
	);

	/**
	 * Is this card have been initialised.
	 *
	 * @var boolean
	 * @access protected
	 */
	protected $init = false;

	/**
	 * Constructor method, it's construct things.
	 * Set some basic parameters and register controller soon as possible.
	 * Else in some context install and activation route will not be register.
	 *
	 * @param  array $args          Required index plugin_slug. Use this array to pass param (force_activation active and install).
	 * @return void
	 */
	public function __construct( $args = null ) {
		$this->args = wp_parse_args(
			$args,
			array(
				'plugin_slug'      => null,
				'force_activation' => true,
			)
		);

		if ( is_null( $this->args['plugin_slug'] ) ) {
			return;
		}

		$this->plugin_slug = preg_replace( '@[^a-z0-9_-]@', '', strtolower( (string) $this->args['plugin_slug'] ) );

		if ( isset( $this->args['params'] ) ) {
			$this->params = wp_parse_args( $this->args['params'], $this->params );
		}

		if ( ! $this->is_installed() ) {
			add_action( 'admin_post_install_plugin_' . $this->plugin_slug, array( $this, 'install_callback' ) );
		}

		if ( ! $this->is_activated() ) {
			add_action( 'admin_post_activate_plugin_' . $this->plugin_slug, array( $this, 'activate_callback' ) );
		}
	}

	/**
	 * Init method, initialise things.
	 * Separate init form constructor, because route registering need to be early and this do not.
	 * This is execute only if install or activation route are reached or helper method is call.
	 *
	 * @return void
	 */
	protected function init() {
		if ( $this->init ) {
			return;
		}

		require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
		require_once ABSPATH . 'wp-admin/includes/plugin.php';

		$this->is_installed();
		$this->is_activated();

		$this->plugin_information = plugins_api(
			'plugin_information',
			array(
				'slug'   => $this->plugin_slug,
				'fields' => array(
					'short_description' => true,
					'icons'             => true,
					'sections'          => false,
					'rating'            => false,
					'ratings'           => false,
					'downloaded'        => false,
					'last_updated'      => false,
					'added'             => false,
					'tags'              => false,
					'homepage'          => false,
					'donate_link'       => false,
				),
			)
		);

		if ( is_wp_error( $this->plugin_information ) ) {
			$this->can_install = false;
		} elseif ( isset( $this->plugin_information->requires ) ) {
			$this->wp_compatibility = ( $this->plugin_information->requires <= get_bloginfo( 'version' ) );
		} elseif ( isset( $this->plugin_information->requires_php ) ) {
			$this->php_compatibility = ( $this->plugin_information->requires_php <= phpversion() );
		}

		$this->init = true;
	}

	// -- GETTER

	/**
	 * Get plugin information return by WordPress function plugins_api().
	 * Check https://developer.wordpress.org/reference/functions/plugins_api/ form more information
	 *
	 * @return array if the instance has reach information from WordPress plugin repository, null if not.
	 */
	public function get_plugin_information() {
		if ( is_wp_error( $this->plugin_information ) ) {
			return null;
		}

		return $this->plugin_information;
	}

	/**
	 * Get the plugin title.
	 *
	 * @return string The plugin title.
	 */
	public function get_title() {
		$pi = ( ! is_wp_error( $this->plugin_information ) && isset( $this->plugin_information->name ) ) ? $this->plugin_information->name : '';
		return ( ! is_null( $this->params['title'] ) ) ? $this->params['title'] : $pi;
	}

	/**
	 * Get the plugin description.
	 *
	 * @return string The plugin short description.
	 */
	public function get_description() {
		$pi = ( ! is_wp_error( $this->plugin_information ) && isset( $this->plugin_information->short_description ) ) ? $this->plugin_information->short_description : '';
		return ( ! is_null( $this->params['description'] ) ) ? $this->params['description'] : $pi;
	}

	/**
	 * Get the plugin icon.
	 *
	 * @return string The plugin icon as a img tag.
	 */
	public function get_icon() {
		$pi = ( ! is_wp_error( $this->plugin_information ) && isset( $this->plugin_information->icons ) ) ? '<img src="' . $this->plugin_information->icons['2x'] . '"/>' : '';
		return ( ! is_null( $this->params['icon'] ) ) ? $this->params['icon'] : $pi;
	}

	/**
	 * Get the plugin activation ans installation status.
	 *
	 * @return string The plugin status as a one of this string ['activated', 'installed', 'not_installed'].
	 */
	public function get_status() {
		return $this->is_installed() ? ( $this->is_activated() ? 'activated' : 'installed' ) : 'not_installed';
	}

	/**
	 * Get the plugin status text.
	 *
	 * @param  string $status Override the current status by this param.
	 * @return string         The plugin status text based on the current or given one.
	 */
	public function get_status_text( $status = null ) {
		$s  = ( is_string( $status ) && ! empty( $status ) ) ? $status : $this->get_status();
		$st = array(
			'activated'     => __( 'activated' ),
			'installed'     => __( 'installed' ),
			'not_installed' => __( 'not installed' ),
		);
		if ( isset( $this->params['status_text'][ $s ] ) ) {
			return $this->params['status_text'][ $s ];
		}
		return ( isset( $st[ $s ] ) ) ? $st[ $s ] : $st;
	}

	/**
	 * Get the plugin button text.
	 *
	 * @param  string $status Override the current status by this param.
	 * @return string         The plugin button text based on the current or given one.
	 */
	public function get_button_text( $status = null ) {
		$s  = ( is_string( $status ) && ! empty( $status ) ) ? $status : $this->get_status();
		$bt = array(
			'activated'     => __( 'Already activated' ),
			'installed'     => __( 'Activate plugin' ),
			'not_installed' => __( 'Install plugin' ),
		);

		if ( isset( $this->params['button_text'][ $s ] ) ) {
			return $this->params['button_text'][ $s ];
		}

		return ( isset( $bt[ $s ] ) ) ? $bt[ $s ] : $bt;
	}

	/**
	 * Get the plugin activation or installation url.
	 *
	 * @param  string $status Override the current status by this param.
	 * @return string         The appropriate activation/installation url based on the current or given one.
	 */
	public function get_install_url( $status = null ) {
		$s  = ( is_string( $status ) && ! empty( $status ) ) ? $status : $this->get_status();
		$bl = array(
			'activated'     => '#',
			'installed'     => add_query_arg(
				array(
					'action'           => 'activate_plugin_' . $this->plugin_slug,
					'_wpnonce'         => wp_create_nonce( $this->nonce ),
					'_wp_http_referer' => rawurlencode( $this->get_current_url() ),
				),
				admin_url( 'admin-post.php' )
			),
			'not_installed' => add_query_arg(
				array(
					'action'           => 'install_plugin_' . $this->plugin_slug,
					'_wpnonce'         => wp_create_nonce( $this->nonce ),
					'_wp_http_referer' => rawurlencode( $this->get_current_url() ),
				),
				admin_url( 'admin-post.php' )
			),
		);

		if ( isset( $this->params['install_url'][ $s ] ) ) {
			return $this->params['install_url'][ $s ];
		}

		return ( isset( $bl[ $s ] ) ) ? $bl[ $s ] : $bl;
	}

	/**
	 * Get the plugin activation status as a boolean.
	 *
	 * @return boolean True if plugin is activated false if not.
	 */
	public function is_activated() {
		if ( is_null( $this->activated ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';

			if ( is_null( $this->installed ) ) {
				$this->is_installed();
			}

			$this->activated = is_plugin_active( $this->plugin_file_path );
		}

		return $this->activated;
	}

	/**
	 * Get the plugin installation status as a boolean.
	 *
	 * @return boolean True if plugin is installed false if not.
	 */
	public function is_installed() {
		if ( is_null( $this->installed ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';

			$installed_plugins = get_plugins();
			$m                 = array();

			foreach ( $installed_plugins as $k => $p ) {
				preg_match( '/([a-zA-Z0-9-_\s]+)\/([a-zA-Z0-9-_]+)\.php/', $k, $m );

				if ( isset( $m[2] ) && $this->plugin_slug === $m[2] ) {
					$this->plugin_file_path = $k;
					$this->installed        = true;
					break;
				}
			}
		}

		return $this->installed;
	}


	// -- SETTER

	/**
	 * Set a title override.
	 *
	 * @param  string $title Whatever you want, a appropriate title preferably.
	 * @return void
	 */
	public function set_title( $title ) {
		if ( is_string( $title ) ) {
			$this->params['title'] = $title;
		}
	}

	/**
	 * Set a description override.
	 *
	 * @param  string $desc The description.
	 * @return void
	 */
	public function set_description( $desc ) {
		if ( is_string( $desc ) ) {
			$this->params['description'] = $desc;
		}
	}

	/**
	 * Set a icon override.
	 *
	 * @param  string $string The icon, has a tag... no ? whatever.
	 * @return void
	 */
	public function set_icon( $string ) {
		if ( is_string( $string ) ) {
			$this->params['icon'] = $string;
		}
	}

	/**
	 * Set status text override.
	 *
	 * @param array $array An array of strings key must be valid status ['activated', 'installed', 'not_installed'].
	 * @return void
	 */
	public function set_status_text( $array ) {
		if ( is_array( $array ) && ! empty( $array ) ) {
			$this->params['status_text'] = $array;
		}
	}

	/**
	 * Set button text override.
	 *
	 * @param array $array An array of strings key must be valid status ['activated', 'installed', 'not_installed'].
	 * @return void
	 */
	public function set_button_text( $array ) {
		if ( is_array( $array ) && ! empty( $array ) ) {
			$this->params['button_text'] = $array;
		}
	}

	// -- Install and activation route and logic

	/**
	 * Install plugin controller.
	 *
	 * @return mixed
	 */
	public function install_callback() {
		if ( ! check_admin_referer( $this->nonce ) ) {
			return false;
		}

		if ( ! current_user_can( is_multisite() ? 'manage_network_plugins' : 'install_plugins' ) ) {
			return false;
		}

		$notices = Notices::get_instance();
		$result  = $this->install();

		if ( is_wp_error( $result ) ) {
			$notices->append( 'error', $result->get_error_code() . ' : ' . $result->get_error_message() );
			wp_safe_redirect( wp_get_referer() );
		}

		if ( $this->args['force_activation'] ) {
			$result = $this->activate();

			if ( is_wp_error( $result ) ) {
				$notices->append( 'error', $result->get_error_code() . ' : ' . $result->get_error_message() );
				wp_safe_redirect( wp_get_referer() );
			}

			$notices->append(
				'success',
				sprintf(
					// translators: %1$s: plugin title.
					esc_html__( '%1$s has been successfully installed and activated.' ),
					$this->get_title()
				)
			);
		} else {
			$notices->append(
				'success',
				sprintf(
					// translators: %1$s: plugin title.
					esc_html__( '%1$s has been successfully installed.' ),
					$this->get_title()
				)
			);
		}

		wp_safe_redirect( wp_get_referer() );
	}

	/**
	 * Activate plugin controller.
	 *
	 * @return mixed
	 */
	public function activate_callback() {
		if ( ! check_admin_referer( $this->nonce ) ) {
			return false;
		}

		if ( ! current_user_can( is_multisite() ? 'manage_network_plugins' : 'install_plugins' ) ) {
			return false;
		}

		$notices = Notices::get_instance();
		$result  = $this->activate();

		if ( is_wp_error( $result ) ) {
			$notices->append( 'error', $result->get_error_code() . ' : ' . $result->get_error_message() );

			wp_safe_redirect( wp_get_referer() );
		}

		$notices->append(
			'success',
			sprintf(
				// translators: %1$s: plugin title.
				esc_html__( '%1$s has been successfully activated.' ),
				$this->get_title()
			)
		);

		wp_safe_redirect( wp_get_referer() );
	}

	/**
	 * Install plugin.
	 *
	 * @return mixed
	 */
	protected function install() {
		$this->init();

		if ( $this->installed ) {
			return null;
		}

		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

		ob_start();
		@set_time_limit( 0 ); // phpcs:ignore WordPress.PHP.NoSilencedErrors
		$upgrader = new \Plugin_Upgrader( new \Automatic_Upgrader_Skin() );
		$result   = $upgrader->install( $this->plugin_information->download_link );
		ob_end_clean();

		if ( is_wp_error( $result ) ) {
			return $result;
		}

		clearstatcache();

		$this->plugin_file_path = $upgrader->plugin_info();
		$this->installed        = true;

		return null;
	}

	/**
	 * Activate plugin.
	 *
	 * @return mixed
	 */
	protected function activate() {
		$this->init();

		if ( $this->is_activated() ) {
			return null;
		}

		require_once ABSPATH . 'wp-admin/includes/plugin-install.php';

		$result = activate_plugin( $this->plugin_file_path, false, is_multisite() );

		if ( is_wp_error( $result ) ) {
			return $result;
		}

		$this->activated = true;

		return null;
	}

	// -- Helper

	/**
	 * Card helper, construct a functional card.
	 *
	 * @param  boolean $echo Print the result if true.
	 * @return mixed         If echo is false, else it's return the card as a sting.
	 */
	public function helper( $echo = true ) {
		$this->init();

		if ( false === $echo ) {
			ob_start();
		}

		$this->render_helper();

		if ( false === $echo ) {
			$r = ob_get_contents();
			ob_end_clean();
			return $r;
		}
	}

	/**
	 * Card helper, the real one.
	 *
	 * @return void
	 */
	protected function render_helper() { ?>
		<div class="card single-link">
			<div class="link-infos">
				<div class="link-infos-logo"><?php echo $this->get_icon(); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
				<span class="link-infos-txt">
					<h3><?php echo esc_html( $this->get_title() ); ?></h3>
					<p>
					<?php
					printf(
						// translators: %1$s: status (not installed, installed or activated).
						esc_html__( 'Status : %1$s' ),
						esc_html( $this->get_status_text() )
					);
					?>
					</p>
				</span>
			</div>
			<div class="link-content"><?php echo $this->get_description(); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
			<?php if ( 'activated' === $this->get_status() ) : ?>
				<span class="wrapper-infos-active"><span class="dashicons dashicons-yes"></span><span class="info-active"><?php echo esc_html( $this->get_button_text() ); ?></span></span>
			<?php else : ?>
				<a class="link-btn button-primary referer-link <?php echo esc_attr( $this->get_status() ); ?>" href="<?php echo esc_url( $this->get_install_url() ); ?>"><?php echo esc_html( $this->get_button_text() ); ?></a>
			<?php endif; ?>
		</div>
		<?php
	}

	// -- tools

	/**
	 * Rebuilt current url.
	 *
	 * @return string The current url.
	 */
	public function get_current_url() {
		$_server_port = filter_input( INPUT_SERVER, 'SERVER_PORT', FILTER_SANITIZE_NUMBER_INT );
		$_request_uri = ( ! empty( $GLOBALS['HTTP_SERVER_VARS']['REQUEST_URI'] ) )
			? $GLOBALS['HTTP_SERVER_VARS']['REQUEST_URI']
			: filter_input( INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL );
		$_http_host   = filter_input( INPUT_SERVER, 'HTTP_HOST', FILTER_SANITIZE_URL );

		$port = (int) $_server_port;
		$port = 80 !== $port && 443 !== $port ? ( ':' . $port ) : '';
		$url  = $_request_uri ? $_request_uri : '';

		return 'http' . ( is_ssl() ? 's' : '' ) . '://' . $_http_host . $port . $url;
	}
}
