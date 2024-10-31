<?php
defined( 'ABSPATH' ) || die( 'You are not allowed to do that.' );

add_action( 'init', 'bawo2pc_l10n' );
/**
 * Load the i18n on front side
 *
 * @return void
 * @author Julio Potier
 * @since 0.3
 **/
function bawo2pc_l10n() {
	load_plugin_textdomain( 'bawo2pc', 'false', dirname( plugin_basename( BAWO2PC__FILE__ ) ) . '/lang/' );
}

add_action( 'admin_bar_menu', 'bawo2pc_admin_bar', PHP_INT_MAX );
/**
 * On front side, will add an admin bar menu to purge the URL
 *
 * @param (object) $wp_admin_bar
 * @return void
 * @author Julio Potier
 * @since 0.3
 **/
function bawo2pc_admin_bar( $wp_admin_bar ) {
	if ( ! bawo2pc_is_valid_key() ) {
		return;
	}
	$referer = '&_wp_http_referer=' . rawurlencode( wp_unslash( $_SERVER['REQUEST_URI'] ) );
	// Purge this URL (frontend).
	$wp_admin_bar->add_menu(
		array(
			'id'     => 'x-purge-cache',
			'title'  => isset( $_GET['x-purged'] ) ? __( 'URL X-Purged!', 'bawo2pc' ) : __( 'X-Purge this URL', 'bawo2pc' ),
			'href'   => wp_nonce_url( admin_url( 'admin-post.php?action=x-purge-url' . $referer ), 'x-purge-url' ),
		)
	);
}

