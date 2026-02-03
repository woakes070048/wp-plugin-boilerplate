<?php

namespace WPPluginBoilerplate\Support\Settings\Schemas;

use WPPluginBoilerplate\Support\Settings\Contracts\SchemaContract;
use WPPluginBoilerplate\Support\Settings\Fields\FieldDefinition;

abstract class BaseSchema implements SchemaContract
{
    /**
     * Concrete schemas must define their fields
     */
    abstract public static function definition(): array;

    /**
     * Storage key (required)
     */
    abstract public static function optionKey(): string;

    /**
     * Scope: site | network
     */
    public static function scope(): string
    {
        return 'site';
    }

    /**
     * Schema version (for migrations)
     */
    public static function version(): int
    {
        return 1;
    }

    /**
     * Default migration hook
     */
    public static function migrate(array $old, int $fromVersion): array
    {
        return $old;
    }

    /**
     * Canonical defaults resolver
     */
    public static function defaults(): array
    {
        $defaults = [];

        foreach (static::definition() as $key => $schema) {
            $field = FieldDefinition::fromSchema($key, $schema);
            $defaults[$key] = $field->resolvedDefault();
        }

        return $defaults;
    }

    /**
     * FieldDefinition map (useful for tooling)
     */
    public static function fields(): array
    {
        $fields = [];

        foreach (static::definition() as $key => $schema) {
            $fields[$key] = FieldDefinition::fromSchema($key, $schema);
        }

        return $fields;
    }
}
