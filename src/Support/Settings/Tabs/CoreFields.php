<?php

namespace WPPluginBoilerplate\Support\Settings\Tabs;

use WPPluginBoilerplate\Support\Settings\Schemas\CoreFieldsSchema;

class CoreFields extends AbstractSettingsTab
{
    public static function schema(): string
    {
        return CoreFieldsSchema::class;
    }

    protected function config(): array
    {
        return [
            'id'   => 'CoreFields',
            'label'=> 'Core Fields',
            'view_capability'   => 'manage_options',
            'manage_capability' => 'manage_options',
        ];
    }
}
