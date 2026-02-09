# Fields Reference

This document defines the **complete field definition structure** supported by the WP Plugin Boilerplate as of **v1.0**.

Fields are **tab-owned**, **explicit**, and **deterministic**.
There is no schema layer and no implicit behavior.

---

## General Rules

- Fields are defined inside a **settings tab**
- Each field is keyed by a unique string
- Defaults are mandatory
- Storage format is predictable
- Validation is enforced on **save and import**
- Frontend reads must tolerate missing or empty values

---

## Canonical Field Definition

This example shows **all supported options**.
Most fields only need a subset.

```php
public static function fields(): array
{
	return [
		'example_field' => [

			// Data type (sanitization & normalization)
			'type' => 'string', // string | int | bool | array

			// UI renderer
			'field' => 'text',
			// text | textarea | checkbox | select | radio
			// number | email | url | password | color
			// image | audio | video | document

			// Human-readable label
			'label' => 'Example Field',

			// Optional helper text
			'description' => 'Displayed below the field.',

			// Default value (required)
			'default' => '',

			// Placeholder (text-like fields only)
			'placeholder' => 'Enter a value',

			// Required flag (UI-level only)
			'required' => false,

			// Select / radio options
			'options' => [
				'option_1' => 'Option One',
				'option_2' => 'Option Two',
			],

			// Conditional visibility (admin UI only)
			'conditions' => [
				[
					'field' => 'another_field',
					'operator' => '==', // == | != | in | not_in
					'value' => 'yes',
				],
			],

			// Optional capability override
			// Falls back to tab capability
			'capability' => 'manage_options',

			// Custom CSS class
			'class' => 'widefat',

			// Field state flags
			'readonly' => false,
			'disabled' => false,

			// Optional sanitization callback
			// Receives raw value, must return sanitized value
			'sanitize_callback' => function ($value) {
				return sanitize_text_field($value);
			},
		],
	];
}
```

---

## Supported Field Types

### Text-Based Fields

#### text
```php
'type'  => 'string',
'field' => 'text',
```

#### textarea
```php
'type'  => 'string',
'field' => 'textarea',
```

#### email
```php
'type'  => 'string',
'field' => 'email',
```

#### url
```php
'type'  => 'string',
'field' => 'url',
```

#### password
```php
'type'  => 'string',
'field' => 'password',
```

---

### Boolean & Numeric Fields

#### checkbox
```php
'type'    => 'bool',
'field'   => 'checkbox',
'default' => false,
```

#### number
```php
'type'    => 'int',
'field'   => 'number',
'default' => 0,
```

---

### Choice Fields

#### select
```php
'type'    => 'string',
'field'   => 'select',
'options' => [
	'key' => 'Label',
],
```

#### radio
```php
'type'    => 'string',
'field'   => 'radio',
'options' => [
	'key' => 'Label',
],
```

---

### Media Fields

All media fields store **attachment IDs**.

#### image
```php
'type'  => 'int',
'field' => 'image',
```

#### audio
```php
'type'  => 'int',
'field' => 'audio',
```

#### video
```php
'type'  => 'int',
'field' => 'video',
```

#### document
```php
'type'  => 'int',
'field' => 'document',
```

---

## Notes and Guarantees

- `type` controls data safety, not UI
- `field` controls rendering, not storage
- Conditions affect admin visibility only
- Frontend code must never rely on conditions
- Missing values must always fall back to defaults
- Unknown keys are ignored safely

---

## Final Rule

If a field definition is unclear, make it explicit.
Explicit configuration always wins over convenience.
