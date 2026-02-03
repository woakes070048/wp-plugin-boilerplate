<?php

namespace WPPluginBoilerplate\Support\Settings\Contracts;

interface SchemaContract
{
    public static function optionKey(): string;
    public static function definition(): array;

    /**
     * site | network
     */
    public static function scope(): string;
}
