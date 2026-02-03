<?php

namespace WPPluginBoilerplate\Support\Settings\Schemas;

use WPPluginBoilerplate\Support\Settings\Fields\FieldDefinition;

class CoreFieldsSchema extends BaseSchema
{
    public static function definition(): array
    {
        return [

            'text' => [
                'type' => 'string',
                'field' => 'text',
            ],

            'textarea' => [
                'type' => 'string',
                'field' => 'textarea',
            ],

            'email' => [
                'type' => 'string',
                'field' => 'email',
            ],

            'url' => [
                'type' => 'string',
                'field' => 'url',
            ],

            'password' => [
                'type' => 'string',
                'field' => 'password',
            ],

            'hidden' => [
                'type' => 'string',
                'field' => 'hidden',
            ],

            'checkbox' => [
                'type' => 'boolean',
                'field' => 'checkbox',
            ],

            'number' => [
                'type' => 'integer',
                'field' => 'number',
                'min' => null,
                'max' => null,
            ],

            'range' => [
                'type' => 'integer',
                'field' => 'range',
                'min' => 0,
                'max' => 100,
            ],

            'select' => [
                'type' => 'string',
                'field' => 'select',
                'options' => ["Red", "Green", "Blue", "Yellow"],
            ],

            'multiselect' => [
                'type' => 'array',
                'field' => 'multiselect',
                'options' => ["Red", "Green", "Blue", "Yellow"],
            ],

            'radio' => [
                'type' => 'string',
                'field' => 'radio',
                'options' => ["Red", "Green", "Blue", "Yellow"],
            ],

            'date' => [
                'type' => 'string',
                'field' => 'date',
            ],

            'time' => [
                'type' => 'string',
                'field' => 'time',
            ],

            'datetime-local' => [
                'type' => 'string',
                'field' => 'datetime-local',
            ],
        ];
    }

    public static function optionKey(): string
    {
        return 'wppb_cf';
    }
}
