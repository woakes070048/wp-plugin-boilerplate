<?php

namespace WPPluginBoilerplate\Support\Settings\Schemas;

use WPPluginBoilerplate\Support\Settings\Fields\FieldDefinition;

class EnhancedFieldsSchema extends BaseSchema
{
    public static function definition(): array
    {
        return [
            'media' => [
                'type'  => 'integer',
                'field' => 'media',
            ],

            'file' => [
                'type'  => 'integer',
                'field' => 'file',
            ],

            'image' => [
                'type'  => 'integer',
                'field' => 'image',
            ],

            'document' => [
                'type'  => 'integer',
                'field' => 'document',
            ],

            'audio' => [
                'type'  => 'integer',
                'field' => 'audio',
            ],

            'video' => [
                'type'  => 'integer',
                'field' => 'video',
            ],

            'archive' => [
                'type'  => 'integer',
                'field' => 'archive',
            ],

            'color' => [
                'type'  => 'string',
                'field' => 'color',
                // hex color value
            ],

            'editor' => [
                'type'  => 'string',
                'field' => 'editor',
                'rows'  => 8,
                'media_buttons' => false,
            ],
        ];
    }

    public static function defaults(): array
    {
        $defaults = [];

        foreach (self::definition() as $key => $schema) {
            $field = FieldDefinition::fromSchema($key, $schema);
            $defaults[$key] = $field->default;
        }

        return $defaults;
    }

    public static function version(): int
    {
        return 2;
    }

    public static function migrate(array $old, int $fromVersion): array
    {
        if ($fromVersion < 2) {
            $old['api_timeout'] = $old['api_timeout'] ?? 30;
        }

        return $old;
    }

    public static function optionKey(): string
    {
        return 'wppb_ef';
    }
}
