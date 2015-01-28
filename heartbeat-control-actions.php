<?php
$heartbeat_location  = get_option('heartbeat_location');
$heartbeat_frequency = get_option('heartbeat_frequency');

if ( $heartbeat_location == 'disable-heartbeat-everywhere') {

	add_action( 'init', 'stop_heartbeat', 1 );

	function stop_heartbeat() {

		wp_deregister_script('heartbeat');

	}

} elseif ($heartbeat_location == 'disable-heartbeat-dashboard') {

	add_action( 'init', 'stop_heartbeat', 1 );

	function stop_heartbeat() {
		global $pagenow;

		if ( $pagenow == 'index.php'  )
			wp_deregister_script('heartbeat');
	}

} elseif ($heartbeat_location == 'allow-heartbeat-post-edit') {

	add_action( 'init', 'stop_heartbeat', 1 );

	function stop_heartbeat() {
		global $pagenow;

		if ( $pagenow != 'post.php' && $pagenow != 'post-new.php' )
			wp_deregister_script('heartbeat');
	}

}

if ( is_numeric( $heartbeat_frequency ) ) {
	function heartbeat_frequency( $settings ) {
		global $heartbeat_frequency;
		$settings['interval'] = $heartbeat_frequency;
		return $settings;
	}

	add_filter( 'heartbeat_settings', 'heartbeat_frequency' );
}