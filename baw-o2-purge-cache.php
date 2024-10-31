<?php
/*
Plugin Name: o2switch XtremCache
Plugin URI: https://o2switch.fr
Description: Purge cache for o2switch XtremCache
Author: Julio Potier for o2switch
Author URI: https://secupress.me
Version: 1.0
License: GPLv2
Domain: bawo2pc
*/

defined( 'ABSPATH' ) || die( 'You are not allowed to do that.' );

define( 'BAWO2PC__FILE__', __FILE__ );
define( 'BAWO2PC_PLUGIN_NAME', 'o2switch XtremCache' );
define( 'BAWO2PC_SHORT_PLUGIN_NAME', 'XtremCache' );

// Include functions used on front-end and back-end
include( 'inc/fronck-end.php' );
if ( ! is_admin() ) {
	// Include functions used on front-end only
	include( 'inc/front-end.php' );
} else {
	// Include functions used on back-end only
	include( 'inc/back-end.php' );
}