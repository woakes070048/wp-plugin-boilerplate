<?php

namespace WPPluginBoilerplate\Support\Settings\Tabs;

use WPPluginBoilerplate\Support\Settings\Contracts\TabContract;
use WPPluginBoilerplate\Support\Settings\Contracts\SettingsTabContract;
use WPPluginBoilerplate\Support\Settings\Tabs;

class ToolsTab implements TabContract
{
    public function id(): string
    {
        return 'tools';
    }

    public function label(): string
    {
        return 'Tools';
    }

    public function hasForm(): bool
    {
        return false;
    }

    public function hasActions(): bool
    {
        return false;
    }

    public function viewCapability(): string
    {
        return 'manage_options';
    }

    public function manageCapability(): string
    {
        return 'manage_options';
    }

    public function render(): void
    {
        echo '<h2>Import / Export Settings</h2>';
        echo '<p>Export or import settings for individual tabs.</p>';

        foreach (Tabs::all() as $tab) {
            if (! $tab instanceof SettingsTabContract) {
                continue;
            }

            if (! current_user_can($tab->manageCapability())) {
                continue;
            }

            $this->render_tab_tools($tab);
        }
    }

    protected function render_tab_tools(SettingsTabContract $tab): void
    {
        $exportUrl = wp_nonce_url(
            admin_url(
                'admin-post.php?action=wp_plugin_boilerplate_export&tab=' . $tab->id()
            ),
            'wp_plugin_boilerplate_export_' . $tab->id()
        );

        echo '<hr />';
        echo '<h3>' . esc_html($tab->label()) . '</h3>';

        echo "<p><a href='{$exportUrl}' class='button button-secondary'>Export</a></p>";

        echo '<form method="post" action="' . admin_url('admin-post.php') . '" enctype="multipart/form-data">';
        wp_nonce_field('wp_plugin_boilerplate_import_' . $tab->id());
        echo '<input type="hidden" name="action" value="wp_plugin_boilerplate_import" />';
        echo '<input type="hidden" name="tab" value="' . esc_attr($tab->id()) . '" />';
        echo '<input type="file" name="import_file" accept="application/json" required />';
        submit_button('Import', 'secondary');
        echo '</form>';
    }
}
