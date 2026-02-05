# Contributing Guide

Thank you for considering contributing to this repository.

This project is an **opinionated boilerplate**, not a feature plugin.  
Contributions are welcome **only if they strengthen the architecture** without diluting its principles.

Please read this document fully before opening a pull request.

---

## Contribution Philosophy

This repository values:

- architectural clarity over feature count
- explicit contracts over convenience
- long-term maintainability over short-term speed

If a change makes the system easier to misuse, it will be rejected.

---

## What This Repository Is (and Is Not)

### This repository **is**:

- a foundation for building WordPress plugins
- a reference architecture
- a place to encode best practices as enforceable rules

### This repository **is not**:

- a demo plugin
- a collection of snippets
- a playground for experimental patterns
- a place to add opinionated UI or business logic

---

## Ground Rules (Non-Negotiable)

### 1. Entry file discipline

- `wp-plugin-boilerplate.php` must remain wiring-only
- No logic, conditionals, or side effects are allowed there

### 2. Hook registration

- `add_action` and `add_filter` may only appear inside `Loader`
- Feature classes must register hooks via the loader

### 3. Settings discipline

- All settings must be tab-owned
- Direct calls to `get_option()` or `get_site_option()` are forbidden
- Each settings tab must own its option key
- Presentation-only tabs must never persist data

### 4. Multisite awareness

- Multisite behavior must be explicit
- Storage scope (`site` vs `network`) must be declared in settings tabs
- Network-scoped tabs must never appear in site admin

### 5. Migrations

- Migrations must be idempotent
- Migrations must not run on plugin activation
- Migrations must not produce side effects
- Data loss must be explicit and intentional

---

## Code Style & Structure

- PSR-4 autoloading is mandatory
- File name must match class name exactly
- Namespace must match folder structure
- Constructors must not perform work with side effects
- New behavior should be introduced via new classes

If a change requires modifying multiple core classes, it should be justified clearly in the PR.

---

## Adding New Features

Before proposing a new feature, ask:

1. Does this belong in a boilerplate, or in a real plugin?
2. Does this introduce a new abstraction or reinforce an existing one?
3. Can this be ignored safely by users who don’t need it?

Features that increase surface area without strengthening the foundation are unlikely to be accepted.

---

## Documentation Changes

Documentation is considered **part of the API**.

- [README](README.md) documents rules and mental models
- [ADVANCED-TOPICS.md](ADVANCED-TOPICS.md) documents deep mechanics
- Changes to behavior must include documentation updates
- Avoid duplicating content across documents

---

## Pull Request Guidelines

A good pull request:

- is small and focused
- explains *why* a change is needed, not just *what* changed
- references existing architecture instead of bypassing it
- does not mix refactors with new features

If a PR introduces a new concept, it must also introduce:

- clear naming
- clear ownership
- clear documentation

---

## What Will Be Rejected Without Discussion

- Direct WordPress API usage that bypasses existing layers
- “Just this once” exceptions
- Silent behavioral changes
- Global helpers or utility dumping grounds
- Features added without documentation

This is not personal. It’s architectural hygiene.

---

## Final Note

This boilerplate is intentionally strict.

If you find yourself fighting the architecture, pause and reassess.  
The goal is not flexibility at all costs, but **clarity that scales**.

If you’re unsure about a change, open a discussion before writing code.
