# Advanced Topics

This document covers advanced capabilities built into the WP Plugin Boilerplate.
These features are optional, but they exist to support real-world, long-lived plugins.

Link this document from the main README instead of duplicating details.

---

## Multisite Awareness

The boilerplate supports WordPress multisite without assuming it.

### Core idea

Each settings tab explicitly declares its storage scope:

- `site` → per-site settings
- `network` → network-wide settings

There is no automatic switching at runtime.
Scope is a **design-time decision**, not a conditional.

### How scope works

- Site-scoped settings tabs use:
    - `get_option()`
    - `update_option()`
- Network-scoped settings tabs use:
    - `get_site_option()`
    - `update_site_option()`

The settings repository handles this internally.
No other code should access options directly.

### UI behavior

- Network-scoped tabs are visible **only** in Network Admin
- Site-scoped tabs appear in normal site admin
- Capability checks are enforced in addition to scope

This prevents accidental cross-site configuration leaks.

### Default behavior

On single-site installs:

- all settings behave as `site` scope
- no multisite-specific behavior is triggered

Multisite support never alters single-site behavior.

---

## Settings Migrations

Settings are designed to evolve safely over time.

### Why migrations exist

Migrations allow you to:

- add new settings without breaking existing installs
- change defaults safely
- restructure configuration gradually

There are:

- no activation-time upgrade hooks
- no one-off migration scripts

Migrations run lazily when settings are read.

### Failure philosophy

If a migration fails:

- the plugin must continue operating with the last valid data
- partial migrations must not corrupt stored settings

Migrations should be written defensively and assume imperfect data.

---

## Schema Versioning

Schemas may implement `MigratableSchemaContract`.

### Example

```php
class AdvancedSchema implements MigratableSchemaContract
{
    public static function version(): int
    {
        return 2;
    }

    public static function migrate(array $old, int $from_version): array
    {
        if ($from_version < 2) {
            $old['api_timeout'] = $old['api_timeout'] ?? 30;
        }

        return $old;
    }
}
```

### Rules

- Migrations must be idempotent
- Migrations must not produce side effects
- Migrations must be safe to run multiple times
- Existing values must not be deleted silently

---

## Per-Tab Capabilities

Each settings tab declares its own access policy.

Tabs specify:

- a capability required to view the tab
- a capability required to modify settings

This allows:

- read-only tabs
- admin-only tabs
- network-admin-only tabs
- future custom role support

Capabilities are enforced:

- when rendering the UI
- when saving, resetting, importing, or exporting settings

UI checks are not treated as security.
Server-side enforcement is mandatory.

---

## Import and Export

Settings can be exported and imported **per tab**.

### Design principles

- Import/export is opt-in per tab
- Data is validated against the schema
- Unknown fields are ignored
- Each tab is isolated from others

There is no global import or export.

### Tools Tab

Import and export actions live in a dedicated **Tools** tab.

This keeps:

- configuration tabs focused
- destructive actions explicit
- permissions centralized

---

## What Not To Do

- Do not call `get_option()` or `get_site_option()` directly
- Do not run migrations on plugin activation
- Do not share option keys between tabs
- Do not bypass capability checks for convenience

Breaking these rules undermines the architecture.

---

## When to Use These Features

Use advanced features when:

- your plugin will live across multiple versions
- your plugin runs on multisite
- you need strict permission boundaries
- settings are business-critical

If you do not need them, ignore them.
The boilerplate does not force complexity.
