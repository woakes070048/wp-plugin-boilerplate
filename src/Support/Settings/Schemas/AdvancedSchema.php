<?php

namespace WPPluginBoilerplate\Support\Settings\Schemas;

class AdvancedSchema
{
    public static function definition(): array
    {
        return [
            'debug_mode' => [
                'type'     => 'boolean',
                'default'  => false,
                'sanitize' => 'rest_sanitize_boolean',
                'label'    => 'Enable Debug Mode',
            ],
            'api_timeout' => [
                'type'     => 'integer',
                'default'  => 30,
                'sanitize' => 'absint',
                'label'    => 'API Timeout (seconds)',
            ],
        ];
    }

    public static function defaults(): array
    {
        return array_map(
            fn ($field) => $field['default'],
            self::definition()
        );
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
}
