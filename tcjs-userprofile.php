<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

wp_enqueue_script( 'scripts-tcjs-userprofile', plugins_url( 'js/scripts-tcjs-userprofile.js', __FILE__ ), array(), true);

/* Add custom fields to the user's profile config page */
function tcjs_show_extra_profile_fields( $user ) {
?>
	<h3>TimeConverterJS Settings</h3>
	<table class="form-table">
		<tr>
			<th><label for="tcjs_client_auth_enabled">Enable Web Service</label></th>
			<td>
				<input type="checkbox" name="tcjs_client_auth_enabled" id="tcjs_client_auth_enabled" value="tcjs_client_auth_enabled" <?php if( get_the_author_meta( 'tcjs_client_auth_enabled', $user->ID ) == 'tcjs_client_auth_enabled' ) { ?>checked="checked"<?php } ?> />
				Enable/disable tcjs client services for account
			</td>
		</tr>
		<tr>
			<th><label for="tcjs_client_auth_token">Client Auth Token</label></th>
			<td>
				<input type="text" name="tcjs_client_auth_token" id="tcjs_client_auth_token" value="<?php echo esc_attr( get_the_author_meta( 'tcjs_client_auth_token', $user->ID ) ); ?>" class="regular-text" />
				<input type="hidden" name="tcjs_new_auth_token" id="tcjs_new_auth_token" value="<?php echo create_user_auth_token(); ?>" />
				<input type='button' class="tcjs_button_generate additional-user-image button-primary" value="Generate" id="tcjs_button_generate"/><br />
				<span class="description">Used for authenticating you when you use the tcjs web service</span>
			</td>
		</tr>
	</table>
<?php
}

if( get_option( 'tcjs_enable_user_auth' ) ) {
	add_action( 'show_user_profile', 'tcjs_show_extra_profile_fields' );
	add_action( 'edit_user_profile', 'tcjs_show_extra_profile_fields' );
}

function tcjs_save_extra_profile_fields( $user_id ) {
	if ( !current_user_can( 'edit_user', $user_id ) )
		return false;

	update_usermeta( $user_id, 'tcjs_client_auth_enabled', $_POST['tcjs_client_auth_enabled'] );
	update_usermeta( $user_id, 'tcjs_client_auth_token', $_POST['tcjs_client_auth_token'] );
}

if( get_option( 'tcjs_enable_user_auth' ) ) {
	add_action( 'personal_options_update', 'tcjs_save_extra_profile_fields' );
	add_action( 'edit_user_profile_update', 'tcjs_save_extra_profile_fields' );
}
?>
