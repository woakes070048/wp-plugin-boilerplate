<?php

namespace WPPluginBoilerplate\Support\Settings\Fields;

use WPPluginBoilerplate\Support\Media\WPMimeGroups;

final class FieldDefinition
{
    public string $key;
    public string $type;
    public string $field;
    public string $label;
    public string $description;
    public mixed  $default;
    public $sanitize;
    public array  $options;
    public array  $meta;

    private function __construct() {}

    public static function fromSchema(string $key, array $schema): self
    {
        $self = new self();

        $self->key         = $key;
        $self->type        = $schema['type'] ?? 'string';
        $self->default     = $schema['default'] ?? null; // ✅ FIX
        $self->sanitize    = $schema['sanitize'] ?? self::defaultSanitizer($self->type);
        $self->label       = $schema['label'] ?? self::humanize($key);
        $self->description = $schema['description'] ?? '';
        $self->options     = $schema['options'] ?? [];
        $self->meta        = $schema;

        $self->field = $schema['field']
            ?? self::inferFieldType($self->type);

        return $self;
    }

    private static function inferFieldType(string $type): string
    {
        return match ($type) {
            'boolean' => 'checkbox',
            'integer', 'number' => 'number',
            'array' => 'textarea',
            default => 'text',
        };
    }

    private static function defaultSanitizer(string $type): callable|string
    {
        return match ($type) {
            'boolean' => 'rest_sanitize_boolean',
            'integer', 'number' => 'absint',
            default => 'sanitize_text_field',
        };
    }

    private static function humanize(string $key): string
    {
        return ucwords(str_replace('_', ' ', $key));
    }

    public function resolvedDefault(): mixed
    {
        if ($this->default !== null) {
            return $this->default;
        }

        return match ($this->field) {
            'checkbox'      => false,
            'number',
            'range'         => 0,
            'multiselect'   => [],
            'select',
            'radio',
            'text',
            'textarea',
            'email',
            'url',
            'password',
            'hidden',
            'date',
            'time',
            'datetime-local',
            'color',
            'editor'        => '',
            'media',
            'image',
            'file'          => 0,
            default         => '',
        };
    }

    public function allowedMimes(): ?array
    {
        return match ($this->field) {
            'image'    => WPMimeGroups::group('image'),
            'document' => WPMimeGroups::group('document'),
            'audio' => WPMimeGroups::group('audio'),
            'video' => WPMimeGroups::group('video'),
            'archive' => WPMimeGroups::group('archive'),
            default    => null, // media & file = unrestricted
        };
    }

    public function previewMode(): string
    {
        return match ($this->field) {
            'image'  => 'image',
            'media'  => 'auto',
            default  => 'file',
        };
    }
}
