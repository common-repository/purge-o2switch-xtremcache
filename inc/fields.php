<?php
defined( 'ABSPATH' ) || die( 'You are not allowed to do that.' );

/** header name field */
function bawo2pc_field_header_name() {
	$bawo2pc_settings = get_option( 'bawo2pc_settings' );
?>
	<label><input type="text" class="" name="bawo2pc_settings[header_name]" value="<?php echo ! empty( $bawo2pc_settings['header_name'] ) ? esc_attr( $bawo2pc_settings['header_name'] ) : 'X-VC-Purge-Key'; ?>"/><br>
	<em><?php _e( 'Default value: <code>X-VC-Purge-Key</code>', 'bawo2pc' ); ?></em></label>
<?php
}

/** purge key field */
function bawo2pc_field_purge_key() {
	$bawo2pc_settings = get_option( 'bawo2pc_settings' );
?>
	<label><input type="text" size="60" name="bawo2pc_settings[purge_key]" value="<?php echo ! empty( $bawo2pc_settings['purge_key'] ) ? esc_attr( $bawo2pc_settings['purge_key'] ) : ''; ?>"/><br>
	<em><?php _e( 'You will find it in your XtremCache CPanel module.', 'bawo2pc' ); ?></em></label>
<?php
}

/** server ip@ field */
function bawo2pc_field_server_ip() {
	$bawo2pc_settings = get_option( 'bawo2pc_settings' );
?>
	<label><input type="text" placeholder="0.0.0.0" pattern="^([0-9]{1,3}\.){3}[0-9]{1,3}$" name="bawo2pc_settings[server_ip]" value="<?php echo ! empty( $bawo2pc_settings['server_ip'] ) && filter_var( $bawo2pc_settings['server_ip'], FILTER_VALIDATE_IP ) ? esc_attr( $bawo2pc_settings['server_ip'] ) : ''; ?>"/><br>
	<em><?php _e( 'Useful is you are behind a Cloudflare FW for example. Enter an IP address.', 'bawo2pc' ); ?></em></label>
<?php
}

/** header regex name field */
function bawo2pc_field_header_regex_name() {
	$bawo2pc_settings = get_option( 'bawo2pc_settings' );
?>
	<label><input type="text" class="" name="bawo2pc_settings[header_regex_name]" value="<?php echo ! empty( $bawo2pc_settings['header_regex_name'] ) ? esc_attr( $bawo2pc_settings['header_regex_name'] ) : 'X-Purge-Regex'; ?>"/><br>
	<em><?php _e( 'Default value: <code>X-Purge-Regex</code>', 'bawo2pc' ); ?></em></label>
<?php
}

/** header regex field */
function bawo2pc_field_regex() {
	$bawo2pc_settings = get_option( 'bawo2pc_settings' );
?>
	<label><input type="text" size="60" name="regex" value="<?php echo ! empty( $bawo2pc_settings['regex'] ) ? esc_attr( $bawo2pc_settings['regex'] ) : ''; ?>"/></label>
	<p><em><?php _e( 'Or choose a predefined regex:', 'bawo2pc' ); ?></p>
	<div class="hide-if-no-js">
		<select id="predefined-regex">
			<option value=""><?php _e( 'Make a choice', 'bawo2pc' ); ?></option>
			<option value=".*"><?php _e( 'Purge everything', 'bawo2pc' ); ?></option>
			<option value=".*\.(png|jpg|jpeg|gif|ico)"><?php _e( 'All images', 'bawo2pc' ); ?> (png,jpg,jpeg,gif,ico)</option>
			<option value=".*\.(css|js|html|htm|gz)"><?php _e( 'All dev files', 'bawo2pc' ); ?> (css,js,html,htm,gz)</option>
			<option value=".*\.(css|js|html|htm|gz|png|jpg|jpeg|gif|ico)"><?php _e( 'All static files', 'bawo2pc' ); ?> (images + dev files)</option>
			<option value="/wp-content/themes/.*"><?php _e( 'Everything related to the theme', 'bawo2pc' ); ?></option>
			<option value="/wp-content/uploads/.*"><?php _e( 'Everything related to the medias', 'bawo2pc' ); ?> (/uploads/)</option>
		</select>
	</div>
	<div class="hide-if-js">
		<ul>
			<li><code>.*</code>: <?php _e( 'Purge everything', 'bawo2pc' ); ?></li>
			<li><code>.*\.(png|jpg|jpeg|gif|ico)</code>: <?php _e( 'All images', 'bawo2pc' ); ?> (png,jpg,jpeg,gif,ico)</li>
			<li><code>.*\.(css|js|html|htm|gz)</code>: <?php _e( 'All dev files', 'bawo2pc' ); ?> (css,js,html,htm,gz)</li>
			<li><code>.*\.(css|js|html|htm|gz|png|jpg|jpeg|gif|ico)</code>: <?php _e( 'All static files', 'bawo2pc' ); ?> (images + dev files)</li>
			<li><code>/wp-content/themes/.*</code>: <?php _e( 'Everything related to the theme', 'bawo2pc' ); ?></li>
			<li><code>/wp-content/uploads/.*</code>: <?php _e( 'Everything related to the medias', 'bawo2pc' ); ?> (/uploads/)</li>
		</ul>
	</div>
<?php
}
