<?php
/**
 * Plugin Name: WP Plugin Boilerplate
 * Description: An opinionated, OOP-first WordPress plugin boilerplate.
 */

defined('ABSPATH') || exit;

require_once __DIR__ . '/vendor/autoload.php';

use WPPluginBoilerplate\\Plugin;

function run_wp_plugin_boilerplate() {
    \ = new Plugin();
    \->run();
}

run_wp_plugin_boilerplate();
