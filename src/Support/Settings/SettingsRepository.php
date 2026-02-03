<?php

namespace WPPluginBoilerplate\Support\Settings;

use WPPluginBoilerplate\Support\Settings\Contracts\SchemaContract;

class SettingsRepository
{
    /**
     * Get normalized settings for a schema
     */
    public static function get(string|SchemaContract $schema): array
    {
        $schemaClass = self::resolveSchema($schema);

        $saved = self::getOption(
            $schemaClass::optionKey(),
            $schemaClass::scope()
        );

        $defaults = $schemaClass::defaults();
        $saved    = is_array($saved) ? $saved : [];

        // Clean legacy null / "null"
        foreach ($saved as $key => $value) {
            if ($value === null || $value === 'null') {
                unset($saved[$key]);
            }
        }

        return wp_parse_args($saved, $defaults);
    }

    /**
     * Reset settings back to schema defaults
     */
    public static function reset(string|SchemaContract $schema): void
    {
        $schemaClass = self::resolveSchema($schema);

        self::updateOption(
            $schemaClass::optionKey(),
            $schemaClass::defaults(),
            $schemaClass::scope()
        );
    }

    /**
     * Import settings with full backend validation
     */
    public static function import(string|SchemaContract $schema, array $data): void
    {
        $schemaClass = self::resolveSchema($schema);
        $sanitized   = [];

        foreach ($schemaClass::fields() as $key => $field) {

            if (! array_key_exists($key, $data)) {
                continue;
            }

            $value = call_user_func(
                $field->sanitize,
                $data[$key]
            );

            // -------------------------------------------------
            // Media field validation (attachment ID based)
            // -------------------------------------------------
            if (in_array($field->field, ['image', 'media', 'file', 'document'], true)) {

                // Must be a valid attachment ID
                if (! $value || get_post_type($value) !== 'attachment') {
                    continue;
                }

                $mime = get_post_mime_type($value);

                // Enforce allowed MIME groups (image / document)
                if ($field->allowedMimes() && ! in_array($mime, $field->allowedMimes(), true)) {
                    continue;
                }

                // Explicitly block images for document fields
                if ($field->field === 'document' && wp_attachment_is_image($value)) {
                    continue;
                }

                // Optional file size limit
                if ($field->maxFileSize()) {
                    $path = get_attached_file($value);
                    if ($path && filesize($path) > $field->maxFileSize()) {
                        continue;
                    }
                }
            }

            // Passed validation → save
            $sanitized[$key] = $value;
        }

        self::updateOption(
            $schemaClass::optionKey(),
            $sanitized,
            $schemaClass::scope()
        );
    }

    /* -----------------------------------------------------------------
     * Internals
     * ----------------------------------------------------------------- */

    protected static function resolveSchema(string|SchemaContract $schema): string
    {
        return is_string($schema)
            ? $schema
            : get_class($schema);
    }

    protected static function getOption(string $key, string $scope): array
    {
        if (is_multisite() && $scope === 'network') {
            return get_site_option($key, []);
        }

        return get_option($key, []);
    }

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
