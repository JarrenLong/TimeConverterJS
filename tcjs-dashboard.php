<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if(is_admin()) {
	/* Dashboard widget */
	function tcjs_dashboard_widget_function() {
		$ip = get_client_ip('', false);
		$host = ip_address_to_hostname( $ip );
		$num_users = get_num_valid_users();
		//$total_requests_count = get_request_stats_table_count();
		$req_install_count = get_request_stats_table_count('install');
		$req_scode_count = get_request_stats_table_count('shortcode');
		$req_widget_count = get_request_stats_table_count('widget');
		$req_svc_count = get_request_stats_table_count('service');
		$total_requests_count = ($req_install_count + $req_scode_count + $req_widget_count + $req_svc_count);
		
		$monthData = array(
			'install_count' => $req_install_count,
			'scode_count' => $req_scode_count,
			'widget_count' => $req_widget_count,
			'svc_count' => $req_svc_count,
			'daily_requests' => get_request_stats_table_by_day()
		);
		
		wp_enqueue_script( 'scripts-tcjs-chartjs', plugins_url( 'js/Chart.bundle.min.js', __FILE__ ), array(), true);
		wp_enqueue_script( 'scripts-tcjs-moment', plugins_url( 'js/moment.min.js', __FILE__ ), array(), true);
		wp_enqueue_script( 'scripts-tcjs-dashboard', plugins_url( 'js/scripts-tcjs-dashboard.js', __FILE__ ), array(), true);
		wp_localize_script( 'scripts-tcjs-dashboard', 'monthData', $monthData );

		// display information
		echo '<div style="display:table; width: 100%;">';
		echo '<div style="display:table-cell;"><big><strong>Your IP is ' . $ip . '</strong></big></div>';
		if($host != 'unknown')
			echo '<div style="display:table-cell; text-align: right;"><small>(' . __('hostname', 'tcjs-address') . ' : ' . $host . ')</small></div>';
		echo '<div style="display:table-row;"><a href="admin.php?page=tcjs">View Quick DynDNS Settings</a></div>';
		echo "</div>\n\n";
		echo '
		<div class="box-ip">
			<hr>
			This month, ' . $num_users . ' users have made ' . $total_requests_count . ' IP Lookup requests:
			<canvas id="tcjs-dashboard-canvas-bar"></canvas>
			<br/>
			<table class="wp-list-table widefat fixed striped posts">
				<thead>
				<tr>
					<td>Source</td>
					<td>Requests</td>
				</tr>
				</thead>
				<tr>
					<td>Install</td>
					<td>' . $req_install_count . '</td>
				</tr>
				<tr>
					<td>Shortcode</td>
					<td>' . $req_scode_count . '</td>
				</tr>
				<tr>
					<td>Widget</td>
					<td>' . $req_widget_count . '</td>
				</tr>
				<tr>
					<td>Service</td>
					<td>' . $req_svc_count . '</td>
				</tr>
			</table>
			<br/>
			<canvas id="tcjs-dashboard-canvas-line"></canvas>
			<hr>
			Like this plugin? <a href="http://jlong.co/donate">Donate</a> to support development!
		</div>';
	}

	function tcjs_add_dashboard_widgets() {
		wp_add_dashboard_widget('tcjs_dashboard_widget', __('Quick DynDNS Stats', 'tcjs-address'), 'tcjs_dashboard_widget_function');
	}

	add_action('wp_dashboard_setup', 'tcjs_add_dashboard_widgets' );
}
?>
