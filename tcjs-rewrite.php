<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function tcjs_rewrite_activate() {
	tcjs_rewrite_init();
	flush_rewrite_rules();
}
register_activation_hook( plugin_name(), 'tcjs_rewrite_activate' );

function tcjs_rewrite_deactivate() {
	flush_rewrite_rules();
}
register_deactivation_hook( plugin_name(), 'tcjs_rewrite_deactivate' ); 

function tcjs_rewrite_init() {
	add_rewrite_rule( 'dns\??([^/]*)', 'index.php?pagename=tcjs&$matches[1]', 'top' );
}
add_action( 'init', 'tcjs_rewrite_init' );

function tcjs_rewrite_query_vars( $query_vars ) {
	array_push( $query_vars, 'qdf', 'qda' );
	
    return $query_vars;
}
add_filter( 'query_vars', 'tcjs_rewrite_query_vars' );

function tcjs_rewrite_display_custom_page() {
	$page = get_query_var( 'pagename' );
	
	if ( 'tcjs' == $page ) {
		$auth = get_query_var('qda');
		$fmt = get_query_var('qdf');
		
		// Get user auth token built
		$isAuth = current_user_has_auth( $auth );
		
		$cur_ip = 'false';
		$status = '401';
		
		if( $isAuth ) {
			$cur_ip = get_client_ip( 'service' );
			$status = '200';
		}
		
		if( $fmt == 'json' ) {
			// Send response as JSON
			header( 'Content-Type: application/json;charset=utf-8' );
			
			wp_send_json( array(
				'tcjs' => array(
					'IP' => $cur_ip,
					'AuthToken' => $auth,
					'Status' => $status
				)
			) );
		} else if( $fmt == 'xml') {
			// Send response as XML
			header('Content-Type: application/xml;charset=utf-8');
			
			print '<?xml version="1.0"?>
<tcjs xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
<IP>' . $cur_ip . '</IP>
<AuthToken>' . $auth . '</AuthToken>
<Status>' . $status . '</Status>
</tcjs>';
		} else {
			// Send response as Text
			header( 'Content-Type: text/plain;charset=utf-8' );

			print $cur_ip;
		}
	
		exit();
	}
}
add_filter( 'template_redirect', 'tcjs_rewrite_display_custom_page' );
?>
