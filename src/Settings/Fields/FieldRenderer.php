<?php

namespace WPPluginBoilerplate\Settings\Fields;

use InvalidArgumentException;
use WPPluginBoilerplate\Settings\Fields\Types\{InputField,
	RepeaterField,
	TextareaField,
	NumberField,
	RangeField,
	CheckboxField,
	SelectField,
	MultiSelectField,
	RadioField,
	ColorField,
	EditorField,
	MediaField};

class FieldRenderer
{
	public static function render(
		string $optionKey,
		FieldDefinition $field,
		mixed $value
	): void {

		$instance = self::resolve($field, $value);

		$instance->render($optionKey);
	}

	protected static function resolve(
		FieldDefinition $field,
		mixed $value
	) {

		$type = $field->field ?? $field->type ?? null;

		if (!$type) {
			throw new InvalidArgumentException(
				sprintf('Field "%s" has no type defined.', $field->key)
			);
		}

		return match ($type) {

			// Media
			'media',
			'image',
			'file',
			'document',
			'audio',
			'video',
			'archive'
			=> new MediaField($field->key, $field->meta, $value),

			// Input-based
			'text',
			'email',
			'url',
			'password',
			'hidden',
			'date',
			'time',
			'datetime-local'
			=> new InputField($field->key, $field->meta, $value),

			// Distinct types
			'textarea'    => new TextareaField($field->key, $field->meta, $value),
			'checkbox'    => new CheckboxField($field->key, $field->meta, $value),
			'select'      => new SelectField($field->key, $field->meta, $value),
			'multiselect' => new MultiSelectField($field->key, $field->meta, $value),
			'radio'       => new RadioField($field->key, $field->meta, $value),
			'color'       => new ColorField($field->key, $field->meta, $value),
			'editor'      => new EditorField($field->key, $field->meta, $value),
			'range'       => new RangeField($field->key, $field->meta, $value),
			'number'      => new NumberField($field->key, $field->meta, $value),

			'repeater' => new RepeaterField($field->key, $field->meta, $value),

			default => throw new InvalidArgumentException(
				sprintf('Unknown field type "%s" for field "%s".', $type, $field->key)
			),
		};
	}
}
