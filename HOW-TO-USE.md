# How to Use

This guide walks through building a real plugin using the boilerplate.
It assumes familiarity with WordPress and PHP.

---

## Step 1: Rename the Boilerplate

Start by making the boilerplate yours.

- Rename the plugin directory
- Rename the main plugin file
- Update the namespace, prefix, and text domain by replacing all boilerplate identifiers  (`wp-plugin-boilerplate`, `WPPluginBoilerplate`, `WP Plugin Boilerplate`, `WPPB_`, `wppb_`, `WPPB-`, `wppb-`)
- Regenerate the autoloader:

```bash
composer dump-autoload


---

## Step 2: Define Settings Tabs

Settings are defined directly by tabs.

A settings tab owns:
- its option key
- default values
- sanitization rules
- storage scope
- capability enforcement

There is no schema abstraction.

---

## Step 3: Add Runtime Behavior

Runtime behavior lives in `PublicPlugin` (or equivalent).

Public behavior must always be registered unconditionally by the Plugin orchestrator.

Do **not** gate runtime wiring behind `is_admin()` or other context checks.
Context must be resolved inside hook callbacks.

---

## Step 4: Admin Configuration

Admin is responsible only for:
- rendering settings UI
- validating input
- triggering admin-only actions

Admin must never contain runtime logic.

---

## Step 5: Import, Export, and Reset

Not all actions have the same scope.

- Import and Export are **global operations** affecting all tabs
- Reset is a **tab-scoped operation** affecting only the active tab

Capability enforcement must match the scope of the action.

---

## Step 6: Lifecycle

- Activation must be side-effect free
- Deactivation pauses behavior but keeps data
- Uninstall deletes all plugin-owned data and runs without plugin context

---

## Final Rule

If something feels convenient but implicit, it probably does not belong.
Choose explicit behavior over shortcuts.
