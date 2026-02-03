<?php

namespace WPPluginBoilerplate\Admin\Actions;

use WPPluginBoilerplate\Support\Settings\Contracts\SettingsTabContract;
use WPPluginBoilerplate\Support\Settings\SettingsRepository;
use WPPluginBoilerplate\Support\Settings\Tabs;

class ExportSettings
{
    public function handle(): void
    {
        $tabId = $_GET['tab'] ?? null;
        $tab   = Tabs::active();

        if ($tab->id() !== $tabId) {
            wp_die('Invalid tab context');
        }

        check_admin_referer('wp_plugin_boilerplate_export_' . $tab->id());

        if (! $tab instanceof SettingsTabContract) {
            wp_die('Tab does not support export');
        }

        if (! current_user_can($tab->manageCapability())) {
            wp_die('Unauthorized');
        }

        $data = SettingsRepository::get($tab);

        header('Content-Type: application/json');
        header(
            'Content-Disposition: attachment; filename="' . $tab->id() . '-settings.json"'
        );

        echo wp_json_encode([
            'tab'     => $tab->id(),
            'exported_at' => gmdate('c'),
            'data'    => $data,
        ]);

        exit;
    }
}

