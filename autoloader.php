<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

function heartbeat_control_autoload( $classname ) {
    $class     = str_replace( '\\', DIRECTORY_SEPARATOR, str_replace( '_', '-', strtolower($classname) ) );
    $file_path = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . $class . '.php';
    if ( file_exists( $file_path ) ) {
        require_once $file_path;
    }
}

spl_autoload_register('heartbeat_control_autoload');
