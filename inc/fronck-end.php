<?php
defined( 'ABSPATH' ) || die( 'You are not allowed to do that.' );

/**
 * Actually purge a post by post object or ID
 *
 * @param (WP_Post) $post
 * @param (bool) $with_redirection
 * @return void
 * @author Julio Potier
 * @since 0.3
 **/
function bawo2pc_purge_post( $post, $with_redirection = true ) {
	$post = get_post( $post );

	if ( ! $post ) {
		return;
	}

	if ( current_user_can( 'edit-post', $post->ID ) ) {
		die( 'You are not allowed to do that.' );
	}

	$bawo2pc_settings = get_option( 'bawo2pc_settings' );
	$header_name      = isset( $bawo2pc_settings['header_name'] ) ? $bawo2pc_settings['header_name'] : 'X-VC-Purge-Key';
	$purge_key        = isset( $bawo2pc_settings['purge_key'] ) ? $bawo2pc_settings['purge_key'] : '';
	$args             = [ 	'method'     => 'PURGE',
							'ssl_verify' => false,
							'headers'    => [ 	$header_name => $purge_key,
											'Host' => bawo2pc_get_host()
											]
						];

	$response         = wp_remote_request( bawo2pc_get_url( get_permalink( $post ) ), $args );

	if ( $with_redirection && 200 === wp_remote_retrieve_response_code( $response ) ) {
		wp_redirect( add_query_arg( 'x-purged', 1, wp_get_referer() ) );
		die();
	}

	if ( 200 !== wp_remote_retrieve_response_code( $response ) ) {
		die( esc_html( wp_remote_retrieve_response_code( $response ) ) . ' Invalid request.' );
	}
}

/**
 * Actually purge content by regex
 *
 * @param (string) $regex
 * @param (bool) $with_redirection
 * @return void
 * @author Julio Potier
 * @since 0.3
 **/
function bawo2pc_purge_regex( $regex, $with_redirection = true ) {
	if ( ! $regex ) {
		return;
	}

	if ( current_user_can( 'manage-options' ) ) {
		die( 'You are not allowed to do that.' );
	}

	$bawo2pc_settings  = get_option( 'bawo2pc_settings' );
	$header_name       = isset( $bawo2pc_settings['header_name'] ) ? $bawo2pc_settings['header_name'] : 'X-VC-Purge-Key';
	$header_regex_name = isset( $bawo2pc_settings['header_regex_name'] ) ? $bawo2pc_settings['header_regex_name'] : 'X-Purge-Regex';
	$purge_key         = isset( $bawo2pc_settings['purge_key'] ) ? $bawo2pc_settings['purge_key'] : '';
	$args              = [ 	'method'     => 'PURGE',
							'ssl_verify' => false,
							'headers'    => [ 	$header_name => $purge_key,
											$header_regex_name => stripslashes( $regex ),
											'Host' => bawo2pc_get_host()
											]
						];

	$response          = wp_remote_request( bawo2pc_get_url( home_url() ), $args );

	if ( $with_redirection && 200 === wp_remote_retrieve_response_code( $response ) ) {
		wp_redirect( add_query_arg( 'x-purged', 1, wp_get_referer() ) );
		die();
	}

	if ( 200 !== wp_remote_retrieve_response_code( $response ) ) {
		die( esc_html( wp_remote_retrieve_response_code( $response ) ) . ' Invalid request.' );
	}
}

add_action( 'add_link'                                              , 'bawo2pc_clean_all' );
add_action( 'create_term'                                           , 'bawo2pc_clean_all' );
add_action( 'customize_save'                                        , 'bawo2pc_clean_all' );
add_action( 'delete_link'                                           , 'bawo2pc_clean_all' );
add_action( 'delete_term'                                           , 'bawo2pc_clean_all' );
add_action( 'deleted_user'                                          , 'bawo2pc_clean_all' );
add_action( 'edit_link'                                             , 'bawo2pc_clean_all' );
add_action( 'edited_terms'                                          , 'bawo2pc_clean_all' );
add_action( 'permalink_structure_changed'                           , 'bawo2pc_clean_all' );
add_action( 'switch_theme'                                          , 'bawo2pc_clean_all' );
add_action( 'update_option_category_base'                           , 'bawo2pc_clean_all' );
add_action( 'update_option_sidebars_widgets'                        , 'bawo2pc_clean_all' );
add_action( 'update_option_tag_base'                                , 'bawo2pc_clean_all' );
add_action( 'update_option_theme_mods_' . get_option( 'stylesheet' ), 'bawo2pc_clean_all' );
add_action( 'wp_update_nav_menu'                                    , 'bawo2pc_clean_all' );
/**
 * Intelligent purge, will purge all when these hooks happen
 *
 * @return void
 * @author Julio Potier
 * @since 0.3
 **/
function bawo2pc_clean_all() {
	bawo2pc_purge_regex( '.*', false );
}

add_filter( 'widget_update_callback', 'bawo2pc_widget_update_callback' );
/**
 * Intelligent purge², will purge all when this hook happens
 *
 * @return (object) $instance
 * @author Julio Potier
 * @since 0.3
 **/
function bawo2pc_widget_update_callback( $instance ) {
	bawo2pc_purge_regex( '.*', false );
	return $instance;
}

add_action( 'clean_post_cache'       , 'bawo2pc_clean_post' );
add_action( 'delete_post'            , 'bawo2pc_clean_post' );
add_action( 'save_post'              , 'bawo2pc_clean_post' );
add_action( 'wp_trash_post'          , 'bawo2pc_clean_post' );
add_action( 'wp_untrash_post'        , 'bawo2pc_clean_post' );
add_action( 'wp_update_comment_count', 'bawo2pc_clean_post' );
/**
 * Intelligent purge³, will purge all when this hook happens
 *
 * @return void
 * @author Julio Potier
 * @since 0.3
 **/
function bawo2pc_clean_post( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) ) {
		return;
	}
	bawo2pc_purge_post( $post_id, false );
}

/**
 * Check if the purge key is set, returns a bool
 *
 * @return (bool)
 * @author Julio Potier
 * @since 1.0
 **/
function bawo2pc_is_valid_key() {
	$bawo2pc_settings = get_option( 'bawo2pc_settings' );
	return isset( $bawo2pc_settings['purge_key'] ) && ! empty( $bawo2pc_settings['purge_key'] );
}

/**
 * Return the home_url without http|s
 *
 * @return (string)
 * @author Julio Potier
 * @since 1.0
 **/
function bawo2pc_get_host() {
	return str_replace( [ 'http://', 'https://' ], '', home_url() );
}

/**
 * Return the home_url without http|s
 *
 * @param (string) $url Any url, usually home_url or a post permalink
 * @return (string)
 * @author Julio Potier
 * @since 1.0
 **/
function bawo2pc_get_url( $url ) {
	$bawo2pc_settings = get_option( 'bawo2pc_settings' );
	$server_ip        = isset( $bawo2pc_settings['server_ip'] ) && filter_var( $bawo2pc_settings['server_ip'], FILTER_VALIDATE_IP ) ? $bawo2pc_settings['server_ip'] : '';
	if ( $server_ip ) {
		$protocol = strpos( home_url(), 'https://' ) === 0 ? 'https://' : 'http://';
		$url      = str_replace( home_url(), $protocol . $server_ip, $url );
	}
	return $url;
}