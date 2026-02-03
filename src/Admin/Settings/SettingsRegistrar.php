<?php

namespace WPPluginBoilerplate\Admin\Settings;

use WPPluginBoilerplate\Support\Settings\Contracts\SettingsTabContract;
use WPPluginBoilerplate\Support\Settings\Fields\FieldDefinition;
use WPPluginBoilerplate\Support\Settings\Fields\FieldRenderer;
use WPPluginBoilerplate\Support\Settings\SettingsRepository;
use WPPluginBoilerplate\Support\Settings\Tabs;

class SettingsRegistrar
{
    public function register(): void
    {
        foreach (Tabs::all() as $tab) {
            if (! $tab instanceof SettingsTabContract) {
                continue;
            }

            $this->register_tab($tab);
        }
    }

    protected function register_tab(SettingsTabContract $tab): void
    {
        $optionKey = $tab::optionKey();

        register_setting($optionKey, $optionKey);

        add_settings_section(
            'default',
            '',
            '__return_null',
            $optionKey
        );

        foreach ($tab::definition() as $fieldKey => $schema) {

            $field = FieldDefinition::fromSchema($fieldKey, $schema);

            add_settings_field(
                $field->key,
                esc_html($field->label),
                function () use ($tab, $field) {

                    $values = SettingsRepository::get($tab);
                    $raw    = $values[$field->key] ?? null;

                    // 🔒 FINAL GUARANTEE: renderer never sees null
                    if ($raw === null || $raw === 'null') {
                        $value = $field->resolvedDefault();
                    } else {
                        $value = $raw;
                    }

                    FieldRenderer::render(
                        $tab::optionKey(),
                        $field,
                        $value
                    );
                },
                $optionKey,
                'default'
            );
        }
    }
}
