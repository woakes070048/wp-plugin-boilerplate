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
        echo '<h2>Tools</h2>';
        echo '<p class="description">';
        echo esc_html__('Import or export settings for individual tabs.', 'wp-plugin-boilerplate');
        echo '</p>';

        $this->render_notices();

        echo '<div class="notice notice-info inline">';
        echo '<p>';
        echo esc_html__(
            'Importing settings will overwrite existing values for the selected tab.',
            'wp-plugin-boilerplate'
        );
        echo '</p>';
        echo '</div>';

        $this->render_export_table();
        $this->render_import_table();

        echo '<script>
function wppbHandleFileSelect(input, submitId, labelId) {
    var submitBtn = document.getElementById(submitId);
    var label     = document.getElementById(labelId);

    if (!input.files || !input.files.length) {
        label.textContent = "' . esc_js(__('No file selected', 'wp-plugin-boilerplate')) . '";
        submitBtn.disabled = true;
        return;
    }

    label.textContent = input.files[0].name;
    submitBtn.disabled = false;
}
</script>';

    }

    /* -------------------------------------------------------------------------
     * Notices
     * ---------------------------------------------------------------------- */

    protected function render_notices(): void
    {
        $notice = $_GET['wppb_notice'] ?? null;

        if (! $notice) {
            return;
        }

        switch ($notice) {
            case 'exported':
                $class = 'notice-success';
                $message = __('Settings exported successfully.', 'wp-plugin-boilerplate');
                break;

            case 'imported':
                $class = 'notice-success';
                $message = __('Settings imported successfully.', 'wp-plugin-boilerplate');
                break;

            case 'error':
                $class = 'notice-error';
                $message = __('An error occurred while processing the request.', 'wp-plugin-boilerplate');
                break;

            default:
                return;
        }

        echo '<div class="notice ' . esc_attr($class) . ' inline">';
        echo '<p>' . esc_html($message) . '</p>';
        echo '</div>';
    }

    /* -------------------------------------------------------------------------
     * Export
     * ---------------------------------------------------------------------- */

    protected function render_export_table(): void
    {
        echo '<h3>' . esc_html__('Export Settings', 'wp-plugin-boilerplate') . '</h3>';
        echo '<p class="description">';
        echo esc_html__('Download settings for a specific tab.', 'wp-plugin-boilerplate');
        echo '</p>';

        echo '<table class="widefat striped">';
        echo '<thead>';
        echo '<tr>';
        echo '<th style="width:30%;">' . esc_html__('Tab', 'wp-plugin-boilerplate') . '</th>';
        echo '<th style="width:20%;">' . esc_html__('Scope', 'wp-plugin-boilerplate') . '</th>';
        echo '<th style="width:50%;">' . esc_html__('Action', 'wp-plugin-boilerplate') . '</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        foreach (Tabs::all() as $tab) {
            if (! $tab instanceof SettingsTabContract) {
                continue;
            }

            if (! current_user_can($tab->manageCapability())) {
                continue;
            }

            $exportUrl = wp_nonce_url(
                admin_url(
                    'admin-post.php?action=wp_plugin_boilerplate_export&tab=' . $tab->id()
                ),
                'wp_plugin_boilerplate_export_' . $tab->id()
            );

            echo '<tr>';
            echo '<td><strong>' . esc_html($tab->label()) . '</strong></td>';
            echo '<td>' . esc_html(ucfirst($tab::scope())) . '</td>';
            echo '<td>';
            echo '<a href="' . esc_url($exportUrl) . '" class="button button-secondary">';
            echo esc_html__('Export settings', 'wp-plugin-boilerplate');
            echo '</a>';
            echo '</td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
    }

    /* -------------------------------------------------------------------------
     * Import
     * ---------------------------------------------------------------------- */

    protected function render_import_table(): void
    {
        echo '<h3>' . esc_html__('Import Settings', 'wp-plugin-boilerplate') . '</h3>';
        echo '<p class="description">';
        echo esc_html__('Upload a previously exported settings file for a specific tab.', 'wp-plugin-boilerplate');
        echo '</p>';
        echo '<p class="description">';
        echo esc_html__('Choose a JSON file exported from this plugin.', 'wp-plugin-boilerplate');
        echo '</p>';

        echo '<table class="widefat striped">';
        echo '<thead>';
        echo '<tr>';
        echo '<th style="width:30%;">' . esc_html__('Tab', 'wp-plugin-boilerplate') . '</th>';
        echo '<th style="width:20%;">' . esc_html__('Scope', 'wp-plugin-boilerplate') . '</th>';
        echo '<th style="width:50%;">' . esc_html__('Action', 'wp-plugin-boilerplate') . '</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        foreach (Tabs::all() as $tab) {
            if (! $tab instanceof SettingsTabContract) {
                continue;
            }

            if (! current_user_can($tab->manageCapability())) {
                continue;
            }

            echo '<tr>';
            echo '<td><strong>' . esc_html($tab->label()) . '</strong></td>';
            echo '<td>' . esc_html(ucfirst($tab::scope())) . '</td>';
            echo '<td>';

            $this->render_import_form($tab);

            echo '</td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
    }

    protected function render_import_form(SettingsTabContract $tab): void
    {
        $inputId  = 'import_file_' . esc_attr($tab->id());
        $buttonId = 'import_submit_' . esc_attr($tab->id());
        $labelId  = 'import_label_' . esc_attr($tab->id());

        echo '<form method="post" action="' . esc_url(admin_url('admin-post.php')) . '" enctype="multipart/form-data" style="display:inline-flex; gap:8px; align-items:center;">';

        wp_nonce_field('wp_plugin_boilerplate_import_' . $tab->id());

        echo '<input type="hidden" name="action" value="wp_plugin_boilerplate_import" />';
        echo '<input type="hidden" name="tab" value="' . esc_attr($tab->id()) . '" />';

        // Hidden file input
        echo '<input
        type="file"
        name="import_file"
        id="' . $inputId . '"
        accept="application/json"
        required
        style="display:none;"
        onchange="wppbHandleFileSelect(this, \'' . esc_js($buttonId) . '\', \'' . esc_js($labelId) . '\')"
    />';

        // Trigger button
        echo '<label for="' . $inputId . '" class="button button-secondary">';
        echo esc_html__('Choose file', 'wp-plugin-boilerplate');
        echo '</label>';

        // Filename preview
        echo '<span id="' . $labelId . '" style="font-style:italic; color:#666;">';
        echo esc_html__('No file selected', 'wp-plugin-boilerplate');
        echo '</span>';

        // Submit button (disabled by default)
        submit_button(
            esc_html__('Import settings', 'wp-plugin-boilerplate'),
            'secondary',
            'submit',
            false,
            [
                'id'       => $buttonId,
                'disabled' => true,
            ]
        );

        echo '</form>';
    }
}
