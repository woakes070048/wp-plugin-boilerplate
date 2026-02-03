<?php

namespace WPPluginBoilerplate\Support\Settings\Fields;

final class FieldTypeRegistry
{
    public static function all(): array
    {
        return [
            // Core
            'text',
            'textarea',
            'checkbox',
            'number',
            'email',
            'url',
            'password',
            'hidden',
            'select',
            'radio',
            'multiselect',
            'range',
            'date',
            'time',
            'datetime',

            // WordPress enhanced
            'media',
            'image',
            'file',
            'color',
            'editor',
        ];
    }
}
