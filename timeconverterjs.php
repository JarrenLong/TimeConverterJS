<?php
/*
Plugin Name: Quick DynDNS
Plugin URI: http://jlong.co/timeconverterjs
Description: TimeConverterJS provides free and premium Dynamic DNS services for your members, allowing them to know their current IP address wherever they are.
Version: 1.0.65
Author: Jarren Long
Author URI: http://jlong.co
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.en.html

Copyright 2016 Jarren Long (jarrenlong@gmail.com)

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

global $tcjs_db_version;
$tcjs_db_version = '1.0.65';

/* Plugin JS and CSS */
wp_enqueue_style('styles-tcjs', plugins_url( 'css/style-tcjs.css', __FILE__ ), array(), 'all');

function plugin_name() {
	return plugin_basename( __FILE__ ); 
}

$enabled = get_option('tcjs_enabled');

if ($enabled) {
  include('tcjs-rewrite.php');
}
include('tcjs-core.php');
include('tcjs-install.php');
include('tcjs-settings.php');
if ($enabled) {
  include('tcjs-dashboard.php');
  include('tcjs-pluginlinks.php');
  include('tcjs-shortcode.php');
  include('tcjs-widget.php');
  include('tcjs-userprofile.php');
}
?>
