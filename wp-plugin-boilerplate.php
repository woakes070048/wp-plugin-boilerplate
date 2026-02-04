<?php
/**
 * Plugin Name: WP Plugin Boilerplate
 * Description: An opinionated, OOP-first WordPress plugin boilerplate.
 * Plugin URI: http://www.ulhas.net/labs/wp-plugin-boilerplate/
 * Version: 0.9.2
 * Author: Ulhas Vardhan Golchha
 * Author URI: https://www.ulhas.net/
 * Text Domain: wp-plugin-boilerplate
 */

defined('ABSPATH') || exit;

require_once __DIR__ . '/vendor/autoload.php';

use WPPluginBoilerplate\Activator;
use WPPluginBoilerplate\Deactivator;
use WPPluginBoilerplate\Plugin;

if ( ! defined( 'WP_PLUGIN_BOILERPLATE_VERSION' ) ) {
    define('WP_PLUGIN_BOILERPLATE_VERSION', '0.9.2');
}

if ( ! defined( 'WP_PLUGIN_BOILERPLATE_URL' ) ) {
    define( 'WP_PLUGIN_BOILERPLATE_URL', plugin_dir_url( __FILE__ ) );
}

register_activation_hook(__FILE__, [Activator::class, 'activate']);
register_deactivation_hook(__FILE__, [Deactivator::class, 'deactivate']);

function run_wp_plugin_boilerplate(): void
{
    new Plugin()->run();
}

run_wp_plugin_boilerplate();
