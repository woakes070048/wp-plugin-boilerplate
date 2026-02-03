# How to Use This Boilerplate

This document explains how to use the **WP Plugin Boilerplate** to build a real, maintainable WordPress plugin.

It assumes you are familiar with:
- WordPress plugin development
- PHP namespaces and Composer
- Basic WordPress admin concepts

This is **not** a WordPress tutorial.

---

## Step 1: Create a New Plugin From the Boilerplate

Clone or copy this repository and rename it for your plugin.

At minimum, update:

- Repository name
- Plugin folder name
- Entry file name
- Plugin header metadata
- PHP namespace

### Example

```
wp-plugin-boilerplate
↓
my-awesome-plugin
```

Rename:

```
wp-plugin-boilerplate.php
↓
my-awesome-plugin.php
```

Update namespaces everywhere:

```
WPPluginBoilerplate
↓
MyAwesomePlugin
```

Then regenerate autoloading:

```bash
composer dump-autoload
```

---

## Step 2: Understand the Entry File

The entry file exists only to **wire the plugin together**.

It should:
- load Composer autoload
- register activation / deactivation hooks
- instantiate the main `Plugin` class

It must **not** contain:
- business logic
- conditionals
- direct WordPress hooks

Think of it as a handshake, not a brain.

---

## Step 3: Add a Feature (The Right Way)

To add new behavior:

1. Create a new class
2. Decide where it belongs:
    - `Admin` → admin-only behavior
    - `Public` → frontend behavior
    - `Support` → shared infrastructure
3. Register hooks via the Loader

```php
class ExampleFeature
{
    public function register(Loader $loader): void
    {
        $loader->action('init', $this, 'boot');
    }

    public function boot(): void
    {
        // feature logic
    }
}
```

Never call `add_action()` directly.

---

## Step 4: Working With Settings

Settings are built around **tabs + schemas**.

Each settings tab:
- owns its schema
- owns its option key
- persists independently

Read settings via:

```php
$settings = SettingsRepository::get(GeneralTab::class);
```

---

## Step 5: Defining Fields

Fields are **schema-defined** and **intent-based**.

```php
'project_name' => [
    'type'    => 'string',
    'field'   => 'text',
    'default' => '',
];
```

Media fields store attachment IDs and enforce intent safely.

See the full list in **FIELDS.md**.

---

## Step 6: Multisite Considerations

- Declare scope per tab (`site` or `network`)
- Network tabs appear only in Network Admin
- Single-site installs are unaffected

---

## Step 7: Import, Export, and Reset

- Tools tab handles import/export
- Imports are validated
- Reset restores defaults per tab

---

## Step 8: Evolving Settings Safely

- Version schemas
- Migrate lazily on read
- Never mutate options directly

See **advanced-topics.md**.

---

## Step 9: What Not to Do

- Don’t bypass the Loader
- Don’t bypass the SettingsRepository
- Don’t mix admin and public logic
- Don’t add global helpers

---

## Final Advice

This boilerplate is intentionally strict.

Use it when long-term maintainability matters.
