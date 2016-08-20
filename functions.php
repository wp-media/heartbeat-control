<?php

function hbc_post( $key ) {
	if ( isset( $_POST[ $key ] ) ) {
		$value = $_POST[ $key ];
		return $value;
	} else {
		return false;
	}
}