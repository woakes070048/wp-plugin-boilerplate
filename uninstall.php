<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
	exit;
}

/**
 * IMPORTANT:
 * uninstall.php runs without plugin bootstrap or autoloading.
 * Do not use Plugin class or namespaced code here.
 */

// Hard fallback prefix
$prefix = defined('WPPB_PREFIX') ? WPPB_PREFIX : 'wppb_';

global $wpdb;

/**
 * Single-site uninstall
 */
$wpdb->query(
	$wpdb->prepare(
		"DELETE FROM {$wpdb->options} WHERE option_name LIKE %s",
		$wpdb->esc_like($prefix) . '%'
	)
);

/**
 * Multisite uninstall
 */
if (is_multisite()) {
	$wpdb->query(
		$wpdb->prepare(
			"DELETE FROM {$wpdb->sitemeta} WHERE meta_key LIKE %s",
			$wpdb->esc_like($prefix) . '%'
		)
	);
}
