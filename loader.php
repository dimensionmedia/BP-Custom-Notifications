<?php

/*
Plugin Name: BP Custom Notifications
Version: 1.0
Author: David Bisset
*/

/**
 * Load only when BuddyPress is present.
 */
function wctpa_include() {
	require( dirname( __FILE__ ) . '/bp-custom-notifications.php' );
}
add_action( 'bp_include', 'wctpa_include' );