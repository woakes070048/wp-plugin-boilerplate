# How to Use This Boilerplate

This document explains how to use the WP Plugin Boilerplate to build a real plugin.

It assumes you are familiar with:
- WordPress plugin development
- PHP namespaces and Composer
- Basic WordPress admin concepts

This is not a tutorial on WordPress itself.

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

Update the namespace everywhere:

```
WPPluginBoilerplate
↓
MyAwesomePlugin
```

Then run:

```bash
composer dump-autoload
```

---

## Step 2: Understand the Entry File

The entry file exists only to wire the plugin together.

It should:
- load Composer autoload
- register activation / deactivation hooks
- instantiate the main Plugin class

It must not contain:
- business logic
- conditionals
- WordPress hooks

---

## Step 3: Add a Feature (The Right Way)

To add new behavior:

1. Create a new class
2. Decide where it belongs:
    - `Admin` → admin-only behavior
    - `Public` → frontend behavior
    - `Support` → shared infrastructure
3. Register hooks via the Loader

### Example

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

Then register the feature in `Plugin.php`.

No direct `add_action`. Ever.

---

## Step 4: Working With Settings

Settings are built around tabs and schemas.

### Creating a settings tab

1. Create a schema class
2. Create a tab class implementing `SettingsTabContract`
3. Register the tab in the Tabs registry

Each settings tab:
- owns its schema
- owns its option key
- persists independently

### Reading settings anywhere

Never call `get_option()` directly.

Always use the repository:

```php
$settings = SettingsRepository::get(GeneralTab::class);
```

Defaults are always applied automatically.

---

## Step 5: Multisite Considerations

If your plugin runs on multisite:

- Decide scope per settings tab:
    - `site` → per-site configuration
    - `network` → network-wide configuration
- Declare scope in the schema
- Network-scoped tabs automatically appear only in Network Admin

Single-site installs are unaffected.

---

## Step 6: Import, Export, and Reset

- Import/export lives in the Tools tab
- Settings are exported per tab
- Imports are schema-validated and sanitized
- Reset returns a single tab to its defaults

These actions are capability-protected and explicit by design.

---

## Step 7: Evolving Settings Safely

If you need to change settings over time:

- Version the schema
- Implement a migration
- Let migrations run lazily on read

Never:
- run migrations on activation
- modify stored settings directly

See `advanced-topics.md` for details.

---

## Step 8: What Not to Do (Common Mistakes)

- Don’t bypass the Loader
- Don’t bypass the SettingsRepository
- Don’t mix admin and public concerns
- Don’t add global helpers
- Don’t weaken contracts just to make it work

If you’re fighting the structure, you’re probably adding the wrong abstraction.

---

## Final Advice

This boilerplate is intentionally strict.

Use it when:
- your plugin will live for years
- settings matter
- multisite and permissions matter
- maintainability matters

If you just need a quick plugin, this is not the right tool.
