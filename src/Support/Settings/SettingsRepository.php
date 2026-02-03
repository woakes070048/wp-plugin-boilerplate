<?php

namespace WPPluginBoilerplate\Support\Settings;

use WPPluginBoilerplate\Support\Settings\Contracts\SchemaContract;
use WPPluginBoilerplate\Support\Settings\Contracts\MigratableSchemaContract;

class SettingsRepository
{
    /**
     * Get normalized settings for a schema-backed tab
     */
    public static function get(SchemaContract|string $schema): array
    {
        if (is_string($schema)) {
            $schema = new $schema();
        }

        $saved = self::getOption(
            $schema::optionKey(),
            $schema::scope()
        );

        // Run migrations if supported
        if ($schema instanceof MigratableSchemaContract) {
            $storedVersion = $saved['_version'] ?? 0;

            if ($storedVersion < $schema::version()) {
                $saved = $schema::migrate($saved, $storedVersion);
                $saved['_version'] = $schema::version();

                self::updateOption(
                    $schema::optionKey(),
                    $saved,
                    $schema::scope()
                );
            }
        }

        $defaults = $schema::defaults();

        // Never expose internal metadata
        unset($saved['_version']);

        return wp_parse_args($saved, $defaults);
    }

    /**
     * Reset settings for a tab back to defaults
     */
    public static function reset(SchemaContract|string $schema): void
    {
        if (is_string($schema)) {
            $schema = new $schema();
        }

        $data = $schema::defaults();

        if ($schema instanceof MigratableSchemaContract) {
            $data['_version'] = $schema::version();
        }

        self::updateOption(
            $schema::optionKey(),
            $data,
            $schema::scope()
        );
    }

    /**
     * Import settings for a schema-backed tab
     */
    public static function import(
        SchemaContract|string $schema,
        array $data
    ): void {
        if (is_string($schema)) {
            $schema = new $schema();
        }

        $sanitized = [];

        foreach ($schema::definition() as $key => $field) {
            if (! array_key_exists($key, $data)) {
                continue;
            }

            $sanitized[$key] = call_user_func(
                $field['sanitize'],
                $data[$key]
            );
        }

        if ($schema instanceof MigratableSchemaContract) {
            $sanitized['_version'] = $schema::version();
        }

        self::updateOption(
            $schema::optionKey(),
            $sanitized,
            $schema::scope()
        );
    }

    /**
     * Internal getter with multisite awareness
     */
    protected static function getOption(
        string $key,
        string $scope
    ): array {
        if (is_multisite() && $scope === 'network') {
            return (array) get_site_option($key, []);
        }

        return (array) get_option($key, []);
    }

    /**
     * Internal setter with multisite awareness
     */
    protected static function updateOption(
        string $key,
        array $value,
        string $scope
    ): void {
        if (is_multisite() && $scope === 'network') {
            update_site_option($key, $value);
            return;
        }

        update_option($key, $value);
    }
}
