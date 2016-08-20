<?php
/**
 * Additional helper functions used in Heartbeat Control
 *
 * @package \Heartbeat_Control
 * @since 2.0.0
 *
 */

/**
 * Retrieves the value of a $_POST variable.
 *
 * @since 2.0.0
 *
 * @param string $key The key to look for.
 *
 * @return mixed|false The content of the $_POST variable. False if not found.
 */
function hbc_post( $key ) {
	if ( isset( $_POST[ $key ] ) ) {
		$value = $_POST[ $key ];
		return $value;
	}

	return false;
}
