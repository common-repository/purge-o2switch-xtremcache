<?php
defined( 'ABSPATH' ) || die( 'You are not allowed to do that.' );

// Load the input fields
include( 'fields.php' );

add_filter( 'plugin_action_links_' . plugin_basename( BAWO2PC__FILE__ ), 'bawo2pc_settings_action_links' );
/**
 * Add a settings link in the plugin's page
 *
 * @param (array) $links
 * @return (array) $links
 * @author Julio Potier
 * @since 0.3
 **/
function bawo2pc_settings_action_links( $links ) {
	array_unshift( $links, '<a href="' . admin_url( 'admin.php?page=bawo2pc_settings' ) . '">' . __( 'Settings' ) . '</a>' );
	return $links;
}

add_action( 'admin_init', 'bawo2pc_l10n' );
/**
 * Load the i18n on admin side
 *
 * @return void
 * @author Julio Potier
 * @since 0.3
 **/
function bawo2pc_l10n() {
	load_plugin_textdomain( 'bawo2pc', '', dirname( plugin_basename( BAWO2PC__FILE__ ) ) . '/lang' );
}

add_action( 'admin_menu', 'bawo2pc_admin_menu' );
/**
 * Add a page on left menu in admin area + register out settings in the WP whitelist
 *
 * @return void
 * @author Julio potier
 * @since 0.3
 **/
function bawo2pc_admin_menu() {
	add_options_page( BAWO2PC_PLUGIN_NAME . ': ' . __( 'Settings' ), BAWO2PC_SHORT_PLUGIN_NAME, 'manage_options', 'bawo2pc_settings', 'bawo2pc_settings_page' );
	register_setting( 'bawo2pc_settings', 'bawo2pc_settings' );
}

/**
 * Declare the fields for our settings page + form print
 *
 * @return void
 * @author Julio Potier
 * @since 0.3
 **/
function bawo2pc_settings_page() {
	add_settings_section( 'bawo2pc_settings', '', '__return_false', 'bawo2pc_settings' );
		add_settings_field( 'bawo2pc_field_purge_key', __( 'Purge Key', 'bawo2pc' ), 'bawo2pc_field_purge_key', 'bawo2pc_settings', 'bawo2pc_settings' );
	add_settings_section( 'bawo2pc_advanced', '', '__return_false', 'bawo2pc_advanced' );
		add_settings_field( 'bawo2pc_field_header_name', __( 'Header Name', 'bawo2pc' ), 'bawo2pc_field_header_name', 'bawo2pc_advanced', 'bawo2pc_advanced' );
		add_settings_field( 'bawo2pc_field_header_regex_name', __( 'Header Regex Name', 'bawo2pc' ), 'bawo2pc_field_header_regex_name', 'bawo2pc_advanced', 'bawo2pc_advanced' );
		add_settings_field( 'bawo2pc_field_server_ip', __( 'Server IP', 'bawo2pc' ), 'bawo2pc_field_server_ip', 'bawo2pc_advanced', 'bawo2pc_advanced' );

	add_settings_section( 'bawo2pc_regex', '', '__return_false', 'bawo2pc_regex' );
		add_settings_field( 'bawo2pc_field_regex', __( 'Regex Purge', 'bawo2pc' ), 'bawo2pc_field_regex', 'bawo2pc_regex', 'bawo2pc_regex' );
?>
	<div class="wrap bawo2pc">

		<h1><?php echo BAWO2PC_PLUGIN_NAME; ?></h1>
		<p><?php _e( 'This tool allows the usage of XtremCacheCet from the web host <a href="https://o2switch.fr" lang="fr">o2switch.fr</a>', 'bawo2pc' ); ?></p>
		<p><?php _e( 'If you activated the function from your CPanel, you should also fill the inputs below.', 'bawo2pc' ); ?></p>
		<p><?php _e( 'You will find some help on:', 'bawo2pc' ); ?> <?php echo make_clickable( 'https://faq.o2switch.fr/hebergement-mutualise/tutoriels-cpanel/cache-varnish' ); ?></p>

		<h2 id="general-arrow" class="selected"><?php _e( 'General Settings', 'bawo2pc' ); ?></h2>
		<h2 id="advanced-arrow" ><?php _e( 'Advanced Settings', 'bawo2pc' ); ?></h2>
		<h2 id="regex-arrow" ><?php _e( 'XtremCache Manual Purge', 'bawo2pc' ); ?></h2>

		<form action="options.php" method="post">
			<?php settings_fields( 'bawo2pc_settings' ); ?>
			<div id="general-content">
				<?php do_settings_sections( 'bawo2pc_settings' ); ?>
				<?php submit_button(); ?>
			</div>

			<div id="advanced-content" class="hide-if-js">
				<?php do_settings_sections( 'bawo2pc_advanced' ); ?>
				<?php submit_button(); ?>
			</div>
		</form>
		<?php
		if ( isset( $_GET['x-purged'] ) ) {
			echo '<div class="updated notice is-dismissible"><p>Regex Purged!</p></div>';
		}
		?>
		<form action="admin-post.php" method="post">
			<input type="hidden" name="action" value="x-purge-regex">
			<?php wp_nonce_field( 'x-purge-regex' ); ?>
			<div id="regex-content" class="hide-if-js">
				<?php do_settings_sections( 'bawo2pc_regex' ); ?>
				<?php submit_button( __( 'Purge this Regex', 'bawo2pc' ) ); ?>
			</div>
		</form>
	</div>
<?php
}

add_action( 'admin_footer-settings_page_bawo2pc_settings', 'bawo2pc_scripts_styles' );
/**
 * Add inline JS and CSS in admin area on our setting page only (no need a file for this much)
 *
 * @return void
 * @author Julio Potier
 * @since 0.3
 **/
function bawo2pc_scripts_styles() {
?>
<style>
#general-arrow,#advanced-arrow,#regex-arrow{
	cursor: pointer;
}
#general-content,#advanced-content,#regex-content{
	border: 1px solid #ccc;
    z-index: -10;
    padding: 10px;
    background: #eee;
    border-bottom-color: #888;
    border-right-color: #888;
    border-radius: 0 5px 5px 5px;
}
.bawo2pc h2{
	border: 1px solid #aaa;
	border-bottom-color: #777;
	border-right-color: #777;
    display: inline-block;
    padding: 7px 8px 10px 8px;
    border-radius: 5px 5px 0 0;
    background-color: #ccc;
    font-weight: 400;
    margin-bottom: 0;
}
.bawo2pc h2.selected{
    padding: 11px 10px 10px 10px;
	font-weight: 600;
	background-color: #ddd;
	border-color: #bbb;
	border-bottom-color: #888;
	border-right-color: #888;
}
</style>
<script>
	jQuery(".bawo2pc h2").on("click", function() {
		var NewID = jQuery(this).attr("id").replace("arrow", "content");
		jQuery("#general-arrow,#advanced-arrow,#regex-arrow").removeClass("selected");
		jQuery("#general-content,#advanced-content,#regex-content").hide();
		jQuery(this).addClass("selected");
		jQuery("#" + NewID).show();
	});
	jQuery("#predefined-regex").on("change", function() {
		jQuery("#regex-content input[type=text]").val(jQuery(this).val());
		jQuery(this).val('');
	});
</script>
<?php
}

register_activation_hook( BAWO2PC__FILE__, 'bawo2pc_activation' );
/**
 * When activated, the plugin will set the default values, if not already there to prevent overwriting
 *
 * @return void
 * @author Julio Potier
 * @since 0.3
 **/
function bawo2pc_activation() {
	if ( ! get_option( 'bawo2pc_settings' ) ) {
		update_option( 'bawo2pc_settings', [ 'header_name' => 'X-VC-Purge-Key', 'purge_key'=> '', 'server_ip' => '', 'header_regex_name' => 'X-Purge-Regex', 'regex' => '' ] );
	}
}

register_uninstall_hook( BAWO2PC__FILE__, 'bawo2pc_uninstaller' );
/**
 * On uninstall, will delete our setting DB entry
 *
 * @return void
 * @author Julio Potier
 * @since 0.3
 **/
function bawo2pc_uninstaller() {
	delete_option( 'bawo2pc_settings' );
}

add_filter( 'page_row_actions', 'bawo2pc_post_row_actions', 11, 2 );
add_filter( 'post_row_actions', 'bawo2pc_post_row_actions', 11, 2 );
/**
 * In post listing page, will add an action link to purge the post
 *
 * @param (array) $actions
 * @param (WP_Post) $post
 * @return (array) $actions
 * @author Julio Potier
 * @since 0.3
 **/
function bawo2pc_post_row_actions( $actions, $post ) {
	if ( current_user_can( 'manage_options' ) && bawo2pc_is_valid_key() ) {
		$referer = '&_wp_http_referer=' . rawurlencode( wp_unslash( $_SERVER['REQUEST_URI'] ) );
		$url = wp_nonce_url( admin_url( 'admin-post.php?action=x-purge-post&post=' . $post->ID . $referer ), 'x-purge-post' );
		$actions['bawo2pc_purge'] = sprintf( '<a href="%s">%s</a>', $url, __( 'X-Purge this post', 'bawo2pc' ) );
	}
	return $actions;
}

add_action( 'admin_bar_menu', 'bawo2pc_admin_bar', PHP_INT_MAX );
/**
 * On admin side, will add an admin bar menu to purge the URL if we're on a post edit page
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
	// Purge a post.
	global $pagenow, $post;
	if ( $post && 'post.php' === $pagenow && isset( $_GET['action'], $_GET['post'] ) ) {
		$wp_admin_bar->add_menu(
			array(
				'id'     => 'x-purge-cache',
				'title'  => isset( $_GET['x-purged'] ) ? __( 'Post X-Purged!', 'bawo2pc' ) : __( 'X-Purge this post', 'bawo2pc' ),
				'href'   => wp_nonce_url( admin_url( 'admin-post.php?action=x-purge-post&post=' . $post->ID . $referer ), 'x-purge-post' ),
			)
		);
	}
}

add_action( 'admin_post_x-purge-post', 'bawo2pc_post_cb_x_purge_post' );
/**
 * Admin post callback to call a purge for a post by its ID
 *
 * @return void
 * @author Julio Potier
 * @since 0.3
 **/
function bawo2pc_post_cb_x_purge_post() {
	if ( ! isset( $_GET['post'], $_GET['_wpnonce'] ) || ! wp_verify_nonce( $_GET['_wpnonce'], $_GET['action'] ) ) {
		die( 'Invalid request' );
	}
	bawo2pc_purge_post( $_GET['post'] );
}

add_action( 'admin_post_x-purge-url', 'bawo2pc_post_cb_x_purge_url' );
/**
 * Admin post callback to call the purge for a post by its URL
 *
 * @return void
 * @author Julio Potier
 * @since 0.3
 **/
function bawo2pc_post_cb_x_purge_url() {
	if ( ! isset( $_GET['_wp_http_referer'], $_GET['_wpnonce'] ) || ! wp_verify_nonce( $_GET['_wpnonce'], $_GET['action'] ) ) {
		die( 'Invalid request' );
	}
	$url     = esc_url( home_url( urldecode( $_GET['_wp_http_referer'] ) ) );
	$post_id = url_to_postid( $url );
	bawo2pc_purge_post( $post_id );
}

add_action( 'admin_post_x-purge-regex', 'bawo2pc_post_cb_x_purge_regex' );
/**
 * Admin post callback to call the purge for posts by a regex
 *
 * @return void
 * @author Julio Potier
 * @since 0.3
 **/
function bawo2pc_post_cb_x_purge_regex() {
	if ( ! isset( $_POST['_wp_http_referer'], $_POST['regex'], $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], $_POST['action'] ) ) {
		die( 'Invalid request' );
	}
	bawo2pc_purge_regex( $_POST['regex'] );
}
