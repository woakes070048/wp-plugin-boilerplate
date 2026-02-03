# WP Plugin Boilerplate

An opinionated, OOP-first WordPress plugin boilerplate.

This repository is a **foundation**, not a demo plugin.

---

## Core Principles

### 1. One entry point
- `wp-plugin-boilerplate.php` is the only file WordPress knows about
- No logic lives there mentioned beyond wiring

### 2. Centralized hooks only
- `add_action` and `add_filter` are allowed **only** inside `Loader`
- Every hook must be registered via `$loader->action()` or `$loader->filter()`

### 3. No WordPress globals in business logic
- No `$_POST`, `$_GET`, `$_REQUEST` outside controlled entry points
- No `global $wpdb` scattered across classes

### 4. Extend by adding classes, not editing core
- New features = new class
- Core classes should rarely change

### 5. PSR-4 is non-negotiable
- File name = class name
- Namespace = folder structure
- Case-sensitive always

### 6. Tabs are first-class concepts
- Admin screens are composed of tabs
- Tabs may be:
    - settings tabs (persist data)
    - presentation-only tabs (About, Help, Docs)
- Not all tabs save data, by design

### 7. Settings are schema-driven and isolated
- Each settings tab owns its own schema
- Each settings tab persists data under its own option key
- Defaults are defined centrally in schemas
- Raw `get_option()` must not be used outside the settings layer

---

## Folder Responsibilities

| Folder | Responsibility |
|------|---------------|
| `src/` | All PHP source code |
| `src/Admin` | Admin-only behavior |
| `src/Public` | Frontend behavior |
| `src/Support` | Shared helpers |
| `assets/` | JS / CSS |
| `languages/` | Translations |

---

## Settings Architecture

- `TabContract` defines navigation and rendering
- `SchemaContract` defines persistence, defaults, and sanitization
- `SettingsTabContract` explicitly marks tabs that own settings

Rules:
- Tabs render content only
- Tabs never render `<form>` tags or action buttons
- UI chrome (forms, actions) is owned by the renderer
- Informational tabs must never touch settings storage

---

## What NOT to do

- ❌ No logic in the entry file
- ❌ No direct `add_action` in feature classes
- ❌ No dumping helpers into `functions.php`-style files
- ❌ No silent side effects in constructors

---

## Mental Model

- Entry file = handshake
- Plugin = orchestration
- Loader = wiring
- Classes = behavior

If you break these rules, this stops being a boilerplate.
