<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
	exit;
}

$prefix = defined('WPPB_PREFIX') ? WPPB_PREFIX : 'wppb_';

delete_option($prefix . 'settings');

if (is_multisite()) {
	global $wpdb;

	$wpdb->query(
		$wpdb->prepare(
			"DELETE FROM {$wpdb->options} WHERE option_name LIKE %s",
			$wpdb->esc_like($prefix) . '%'
		)
	);
}

