<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

//if(is_admin()) {
	/* Add links to the plugin page next to the Activate/Deactivate link */
	function tcjs_plugin_action_links( $links ) {
		$new_links = array(
			'settings' => '<a href="options-general.php?page=tcjs">Settings</a>',
			'faq' => '<a href="http://jlong.co/timeconverterjs#faq" target="_blank">FAQ</a>',
			'donate' => '<a href="http://jlong.co/donate" target="_blank">Donate</a>'
		);
		
		return array_merge( $links, $new_links );
	}
	
	$plugin = plugin_name();
	add_filter("plugin_action_links_$plugin", 'tcjs_plugin_action_links' );
	
	function tcjs_plugin_row_meta_links($links, $file) {
		$plugin_file = plugin_name();
		
		if ( $file == $plugin_file ) {
			return array_merge(
				$links, array(
					'support' => '<a href="http://jlong.co/timeconverterjs#support" target="_blank">Support</a>',
					'donate' => '<a href="http://jlong.co/donate" target="_blank">Donate</a>'
				)
			);
		}
		
		return $links;
	}
	add_filter('plugin_row_meta', 'tcjs_plugin_row_meta_links', 10, 2);
//}
?>
