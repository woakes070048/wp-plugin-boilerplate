<?php

namespace WPPluginBoilerplate\Support;

use WPPluginBoilerplate\Loader;

class Assets
{
    public function register(Loader $loader): void
    {
        $loader->action('admin_enqueue_scripts', $this, 'admin_assets');
        $loader->action('wp_enqueue_scripts', $this, 'public_assets');
    }

    public function admin_assets(): void
    {
        // Media
        wp_enqueue_media();
        wp_enqueue_script('wppb-media', WP_PLUGIN_BOILERPLATE_URL . 'assets/admin/js/media.js', ['jquery'], WP_PLUGIN_BOILERPLATE_VERSION, true);

        // Color picker
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');

        // Init
        wp_add_inline_script('wp-color-picker', "jQuery('.wppb-color-field').wpColorPicker();");

        wp_enqueue_style('wppb-admin', WP_PLUGIN_BOILERPLATE_URL . 'assets/admin/css/admin.css', [], WP_PLUGIN_BOILERPLATE_VERSION, true);
    }

    public function public_assets(): void
    {
        wp_enqueue_script('wppb-public', plugin_dir_url(dirname(__DIR__, 2)) . 'assets/public/public.js', [], WP_PLUGIN_BOILERPLATE_VERSION, true);
    }
}
