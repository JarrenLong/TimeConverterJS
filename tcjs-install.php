<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/* Plugin installation and activation */
function tcjs_install() {
	global $wpdb;
	global $tcjs_db_version;

	// Fresh install of the database
	$db_prefix = $wpdb->prefix . 'tcjs_';
	$table_users = $db_prefix . 'users';
	$table_iplog = $db_prefix . 'iplog';
	
	$charset_collate = $wpdb->get_charset_collate();

	// Create the tcjs user table
	$sql = "CREATE TABLE $table_users (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		user_id bigint(20),
		service_token text,
		max_service_requests mediumint(8),
		UNIQUE KEY id (id)
	) $charset_collate;
	
	CREATE TABLE $table_iplog (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		user_id bigint(20),
		time datetime DEFAULT '0000-00-00 00:00:00',
		ip_address text,
		source tinytext,
		UNIQUE KEY id (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	add_option( 'tcjs_db_version', $tcjs_db_version );
	// Make sure our default settings are defined
	add_option( 'tcjs_enabled', '1' );
	add_option( 'tcjs_enable_user_auth', '1' );
	add_option( 'tcjs_show_powered_by_widget', '1' );
	
	// Check for a newer version and upgrade the database if necessary
	$installed_ver = get_option( "tcjs_db_version" );
	if ( $installed_ver != $tcjs_db_version ) {
		// Create the tcjs user table
		$sql = "CREATE TABLE $table_users (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			user_id bigint(20),
			service_token text,
			max_service_requests mediumint(8),
			UNIQUE KEY id (id)
		);
		
		CREATE TABLE $table_iplog (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			user_id bigint(20),
			time datetime DEFAULT '0000-00-00 00:00:00',
			ip_address text,
			source tinytext,
			UNIQUE KEY id (id)
		);";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

		update_option( "tcjs_db_version", $tcjs_db_version );
	}
}
register_activation_hook( __FILE__, 'tcjs_install' );

function tcjs_update_db_check() {
    global $tcjs_db_version;
	
    if ( get_site_option( 'tcjs_db_version' ) != $tcjs_db_version ) {
        tcjs_install();
    }
}
add_action( 'plugins_loaded', 'tcjs_update_db_check' );

function tcjs_install_data() {
	get_client_ip('install');
}
register_activation_hook( __FILE__, 'tcjs_install_data' );
?>
