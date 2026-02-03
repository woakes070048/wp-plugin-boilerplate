<?php

namespace WPPluginBoilerplate\Support\Settings\Contracts;

interface MigratableSchemaContract extends SchemaContract
{
    public static function version(): int;

    /**
     * @param array $old Stored settings
     * @param int   $fromVersion
     */
    public static function migrate(array $old, int $fromVersion): array;
}

