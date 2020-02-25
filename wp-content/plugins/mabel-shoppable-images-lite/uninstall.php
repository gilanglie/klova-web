<?php
// Fired when the plugin is uninstalled.
// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option('mb-si-lite-settings');
?>