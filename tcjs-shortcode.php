<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function get_client_ip_shortcode() {
	print get_client_ip( 'shortcode' );
}
add_shortcode( 'tcjs', 'get_client_ip_shortcode' );
?>
