<?php

if( get_option( 'heartbeat_control_donate_dismissed' == '1' ) ) {
    add_action( 'admin_notices', 'heartbeat_control_donate_notice' );
}

function heartbeat_control_donate_notice() {
    ?>
    <div class="notice heartbeat-control-donate is-dismissible" >
        <p>Thanks for using Heartbeat Control!  As I don't make any money from this plugin, please consider <a href="http://jeffmatson.net/donate">donating</a> to help keep Heartbeat Control updated.</p>
    </div>
    <?php
}

add_action( 'admin_enqueue_scripts', 'heartbeat_control_assets' );

function heartbeat_control_assets() {
    wp_enqueue_script( 'heartbeat-control-donate-notice', plugins_url( '/js/donate-notice.js', __FILE__ ), array( 'jquery' ), '1.0', true  );
}

function heartbeat_control_dismiss_donate() {
    update_option( 'heartbeat_control_donate_dismissed', '1' );
}
add_action( 'wp_ajax_heartbeat_control_dismiss_donate', 'heartbeat_control_dismiss_donate' );