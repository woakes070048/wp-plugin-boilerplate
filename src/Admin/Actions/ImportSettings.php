<?php
namespace WPPluginBoilerplate\Admin\Actions;

use WPPluginBoilerplate\Support\Settings\Contracts\SettingsTabContract;
use WPPluginBoilerplate\Support\Settings\SettingsRepository;
use WPPluginBoilerplate\Support\Settings\Tabs;

class ImportSettings
{
    public function handle(): void
    {
        $tabId = $_POST['tab'] ?? null;
        $tab   = Tabs::active();

        if ($tab->id() !== $tabId) {
            wp_die('Invalid tab context');
        }

        check_admin_referer('wp_plugin_boilerplate_import_' . $tab->id());

        if (! $tab instanceof SettingsTabContract) {
            wp_die('Tab does not support import');
        }

        if (! current_user_can($tab->manageCapability())) {
            wp_die('Unauthorized');
        }

        $file = $_FILES['import_file'] ?? null;

        if (! $file || $file['error'] !== UPLOAD_ERR_OK) {
            wp_die('Invalid upload');
        }

        $payload = json_decode(file_get_contents($file['tmp_name']), true);

        if (
            ! is_array($payload) ||
            ($payload['tab'] ?? null) !== $tab->id() ||
            ! isset($payload['data'])
        ) {
            wp_die('Invalid settings file');
        }

        SettingsRepository::import($tab, $payload['data']);

        wp_safe_redirect(
            admin_url('admin.php?page=wp-plugin-boilerplate&tab=tools')
        );
        exit;
    }
}
