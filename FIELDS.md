# Fields Reference

This document lists **all supported field types** in the WP Plugin Boilerplate, along with their **settings definition syntax**, **behavior**, and **guarantees**.

The field system is **tab-owned** and **intent-based**.

---

## General Rules

- Fields are defined inside a **settings tab class**
- Each field is keyed by a unique string
- Storage format is deterministic
- Defaults are applied automatically
- Validation is enforced on **save and import**

```php
public static function fields(): array
{
    return [
        'example_field' => [
            'type'    => 'string',
            'field'   => 'text',
            'default' => '',
            'label'   => 'Example Field',
        ],
    ];
}
```

---

## Core Text-Based Fields

### text

```php
'project_name' => [
    'type'    => 'string',
    'field'   => 'text',
    'default' => '',
];
```

### textarea

```php
'description' => [
    'type'    => 'string',
    'field'   => 'textarea',
    'default' => '',
];
```

### email

```php
'contact_email' => [
    'type'    => 'string',
    'field'   => 'email',
];
```

### url

```php
'website_url' => [
    'type'    => 'string',
    'field'   => 'url',
];
```

### password

```php
'api_key' => [
    'type'    => 'string',
    'field'   => 'password',
];
```

---

## Boolean & Numeric Fields

### checkbox

```php
'enabled' => [
    'type'    => 'boolean',
    'field'   => 'checkbox',
    'default' => false,
];
```

### number

```php
'items_per_page' => [
    'type'    => 'integer',
    'field'   => 'number',
    'default' => 10,
];
```

---

## Choice Fields

### select

```php
'layout' => [
    'type'    => 'string',
    'field'   => 'select',
    'default' => 'grid',
    'options' => [
        'grid' => 'Grid',
        'list' => 'List',
    ],
];
```

### radio

```php
'mode' => [
    'type'    => 'string',
    'field'   => 'radio',
    'default' => 'basic',
    'options' => [
        'basic' => 'Basic',
        'advanced' => 'Advanced',
    ],
];
```

---

## Media Fields

All media fields store **attachment IDs**.

### image

```php
'logo' => [
    'type'  => 'integer',
    'field' => 'image',
];
```

### audio

```php
'intro_audio' => [
    'type'  => 'integer',
    'field' => 'audio',
];
```

### video

```php
'intro_video' => [
    'type'  => 'integer',
    'field' => 'video',
];
```

### document

```php
'terms_pdf' => [
    'type'     => 'integer',
    'field'    => 'document',
    'max_size' => 2 * MB_IN_BYTES,
];
```

---

## Popup Filtering Note

WordPress guarantees popup filtering only for:

- image
- audio
- video

Other media types are enforced after selection and on save.
