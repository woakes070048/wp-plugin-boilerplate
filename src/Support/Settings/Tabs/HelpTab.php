<?php

namespace WPPluginBoilerplate\Support\Settings\Tabs;

use WPPluginBoilerplate\Support\Settings\Contracts\TabContract;

class HelpTab implements TabContract
{
    public function id(): string
    {
        return 'help';
    }

    public function label(): string
    {
        return 'Help';
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
        return 'read';
    }

    public function manageCapability(): string
    {
        return 'do_not_allow';
    }

    public function render(): void
    {
        echo '<h2>Help</h2>';

        echo '<p>';
        echo esc_html__(
            'This boilerplate is designed to be predictable and well-documented, with clear boundaries between architecture, configuration, and behavior.',
            'wp-plugin-boilerplate'
        );
        echo '</p>';

        echo '<p>';
        echo esc_html__(
            'If you are new to the structure, start with the high-level documentation before diving into individual classes.',
            'wp-plugin-boilerplate'
        );
        echo '</p>';

        echo '<hr />';

        echo '<h3>Getting Started</h3>';
        echo '<ul style="list-style: disc; margin-left: 20px;">';

        echo '<li>';
        echo '<a href="https://github.com/golchha21/wp-plugin-boilerplate/blob/main/README.md" target="_blank" rel="noopener noreferrer">';
        echo esc_html__('README', 'wp-plugin-boilerplate');
        echo '</a> – ';
        echo esc_html__('core rules and mental model', 'wp-plugin-boilerplate');
        echo '</li>';

        echo '<li>';
        echo '<a href="https://github.com/golchha21/wp-plugin-boilerplate/blob/main/HOW-TO-USE.md" target="_blank" rel="noopener noreferrer">';
        echo esc_html__('How to Use', 'wp-plugin-boilerplate');
        echo '</a> – ';
        echo esc_html__('practical guide to building a real plugin', 'wp-plugin-boilerplate');
        echo '</li>';

        echo '<li>';
        echo '<a href="https://github.com/golchha21/wp-plugin-boilerplate/blob/main/ADVANCED-TOPICS.md" target="_blank" rel="noopener noreferrer">';
        echo esc_html__('Advanced Topics', 'wp-plugin-boilerplate');
        echo '</a> – ';
        echo esc_html__('multisite, migrations, capabilities, and tools', 'wp-plugin-boilerplate');
        echo '</li>';

        echo '</ul>';

        echo '<hr />';

        echo '<h3>Making Changes</h3>';
        echo '<ul style="list-style: disc; margin-left: 20px;">';
        echo '<li>' . esc_html__('Add new behavior by creating new classes', 'wp-plugin-boilerplate') . '</li>';
        echo '<li>' . esc_html__('Avoid modifying core orchestration unless absolutely necessary', 'wp-plugin-boilerplate') . '</li>';
        echo '<li>' . esc_html__('Keep settings schema-driven and isolated per tab', 'wp-plugin-boilerplate') . '</li>';
        echo '</ul>';

        echo '<hr />';

        echo '<h3>Contributing and Feedback</h3>';
        echo '<p>';
        echo esc_html__(
            'If you plan to contribute or propose changes, please review the contribution guidelines first.',
            'wp-plugin-boilerplate'
        );
        echo '</p>';

        echo '<p>';
        echo '<a href="https://github.com/golchha21/wp-plugin-boilerplate/blob/main/CONTRIBUTING.md" target="_blank" rel="noopener noreferrer">';
        echo esc_html__('Read CONTRIBUTING.md', 'wp-plugin-boilerplate');
        echo '</a>';
        echo '</p>';

        echo '<p style="font-style: italic; color: #555;">';
        echo esc_html__(
            'This boilerplate favors clarity and long-term maintainability over flexibility. When in doubt, choose the more explicit solution.',
            'wp-plugin-boilerplate'
        );
        echo '</p>';

        echo '<hr />';

        echo '<p style="margin-top: 12px;">';
        echo esc_html__(
            'If this boilerplate has been useful in your work, you can support its continued development here:',
            'wp-plugin-boilerplate'
        );
        echo '</p>';

        echo '<p>';
        echo '<a href="https://www.buymeacoffee.com/golchha21" target="_blank" rel="noopener noreferrer" class="button button-secondary">';
        echo esc_html__('Buy me a coffee', 'wp-plugin-boilerplate');
        echo '</a>';
        echo '</p>';
    }
}
