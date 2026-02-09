# WP Plugin Boilerplate

![PHP](https://img.shields.io/badge/PHP-%3E%3D7.4-777BB4?logo=php&logoColor=white)
![Version](https://img.shields.io/github/v/tag/golchha21/wp-plugin-boilerplate)
![Downloads](https://img.shields.io/github/downloads/golchha21/wp-plugin-boilerplate/total)
![License](https://img.shields.io/github/license/golchha21/wp-plugin-boilerplate)

An opinionated WordPress plugin boilerplate for building **long-lived plugins** with explicit structure and predictable lifecycle behavior.

This project does not provide user-facing features and does not try to replace WordPress conventions, Git workflows, or existing development practices.

It exists to provide a constrained starting point for plugins that are expected to grow over time — where admin configuration, settings, frontend behavior, and lifecycle concerns tend to blur and accumulate accidental complexity.

If you are building a small, short-lived plugin, this may feel like more structure than you need. If you are maintaining plugins over years, with evolving requirements or multiple contributors, the constraints are intentional.

---

## Who This Is Not For

This boilerplate is probably **not** a good fit if:

- You are building a small, one-off plugin
- You prefer ad-hoc patterns over explicit structure
- You are comfortable managing complexity manually as a plugin grows
- You want quick scaffolding with minimal constraints
- You are looking for a plugin that provides user-facing features

The constraints in this project are intentional and opinionated.
They trade short-term convenience for long-term predictability.

---

## Core Principles

- Clear separation between admin, settings, and public runtime
- Settings treated as a domain boundary, not just UI configuration
- Unconditional runtime wiring with context resolved at execution time
- Predictable, testable lifecycle behavior
- Minimal magic and no hidden side effects
- Deliberate constraints to reduce long-term drift

---

## What Problem This Solves (In Practice)

This boilerplate is meant for plugins that start simple and then gradually accumulate settings, frontend behavior, permissions, and lifecycle edge cases. Over time, those concerns tend to bleed into each other, making changes harder and riskier than they need to be. The structure here exists to keep those boundaries explicit from the start, so growth doesn’t automatically mean increasing fragility or rewrites later on.

---

## Folder Responsibilities

| Directory        | Responsibility |
|------------------|----------------|
| `src/Admin`      | Admin UI, menus, admin actions |
| `src/Settings`   | Settings tabs, defaults, scope, persistence |
| `src/Public`     | Frontend and runtime behavior |
| `src/Support`    | Shared infrastructure |
| `src/Lifecycle`  | Activation and deactivation |
| `assets`         | CSS, JS, static assets |
| `vendor`         | Bundled dependencies (shipped with the plugin) |

Each directory represents a deliberate boundary. Code should not cross layers without an explicit reason.
The `vendor/` directory is part of the distributed plugin and must not be removed.
It exists to support internal architecture and requires no action from plugin authors.

---

## Dependencies & Setup

This boilerplate is distributed as a **fully self-contained WordPress plugin**.

All required dependencies are already included in the repository.
You do **not** need to run Composer, install build tools, or perform any setup beyond installing and activating the plugin.

If you are extending the boilerplate to build a plugin, you can start writing code immediately inside the `src/` directory.

Composer is only required if you choose to modify or add dependencies yourself.

If the plugin is not working after cloning the repository, ensure the `vendor/` directory is present. If it is missing, reinstall the plugin from a clean copy.

---

## Settings Architecture

Settings are owned directly by individual tabs.

Each settings tab is responsible for:
- its option key
- default values
- sanitization
- storage scope (site or network)
- capability enforcement

There is no separate schema layer.

Settings must be accessed exclusively through the `SettingsRepository`.
Direct calls to `get_option()` or `get_site_option()` are considered architectural violations.

---

## Mental Model

- Classes define behavior
- Tabs define configuration boundaries
- Settings connect admin and runtime
- Admin configures behavior
- Public executes behavior
- Uninstall runs outside the plugin context and must remain procedural

---

## Who This Is For

This boilerplate is a good fit if:

- your plugin has frontend behavior
- settings and permissions matter
- the plugin is expected to evolve over time
- long-term maintainability is a priority

If you are building a quick, disposable plugin, this may be more structure than you need.

---

## v1.0 Stability Guarantees

Starting with v1.0.0, this boilerplate guarantees:

- Public behavior is always registered unconditionally
- Admin configuration flows cleanly into runtime behavior
- Plugin lifecycle behavior is predictable and safe
- Uninstall cleans up all plugin-owned data
- Renaming the plugin does not break behavior
- The plugin is distributed as a self-contained package with all required dependencies bundled

Breaking these guarantees requires a major version bump.

---

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on recent changes.

## Security

If you discover any security-related issues, please email **vardhans@ulhas.net** instead of using the issue tracker.

## Credits

- [Ulhas Vardhan Golchha](https://github.com/golchha21) — *Initial work*

See also the list of [contributors](https://github.com/golchha21/wp-plugin-boilerplate/graphs/contributors).

---

## License

This project is licensed under the **GNU General Public License v2.0 or later (GPL-2.0-or-later)**.

WordPress is licensed under the GPL, and any plugin that runs within WordPress
and uses its APIs is required to be GPL-compatible.

You are free to use, modify, and distribute this software under the terms
of the GPL. See the [LICENSE](LICENSE) file for details.


---

If this boilerplate has been useful to you, you can support its development here:  
[Buy me a coffee](https://www.buymeacoffee.com/golchha21)
