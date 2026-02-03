<?php

namespace WPPluginBoilerplate\Support\Settings\Tabs;

use WPPluginBoilerplate\Support\Settings\Schemas\EnhancedFieldsSchema;

class EnhancedFields extends AbstractSettingsTab
{
    public static function schema(): string
    {
        return EnhancedFieldsSchema::class;
    }

    protected function config(): array
    {
        return [
            'id'   => 'EnhancedFields',
            'label'=> 'Enhanced Fields',
            'view_capability'   => 'manage_options',
            'manage_capability' => 'update_core',
        ];
    }
}
