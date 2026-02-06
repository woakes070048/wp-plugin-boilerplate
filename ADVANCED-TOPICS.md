# Advanced Topics

This document explains architectural constraints that protect v1.0 stability.

---

## Runtime Wiring

Public behavior must always be registered unconditionally.

Do not gate hook registration behind execution context checks.
Let WordPress control when hooks fire.

Context checks belong inside callbacks, not around registration.

---

## Settings as a Domain Boundary

Settings are domain data shared between admin and runtime.

- Admin writes settings
- Public reads settings
- Settings do not depend on Admin or Public

Settings are owned directly by tabs.
There is no schema or persistence abstraction layer.

---

## Capability Semantics

Capabilities are not hierarchical.

Menu visibility is determined dynamically:
A menu is visible if the current user can access at least one tab.

Tabs enforce their own capabilities at runtime.

---

## Import, Export, and Reset Scope

- Import / Export → global operations
- Reset → tab-scoped operation

Capability scope must match data scope.

---

## Lifecycle Boundaries

- Activation must not mutate runtime state
- Deactivation stops behavior without deleting data
- Uninstall runs in isolation and must remain procedural

Uninstall deletes ownership, not individual options.

---

## What Not To Do

- Call `get_option()` directly
- Gate runtime wiring during bootstrap
- Share option keys across tabs
- Treat Admin as a catch-all layer

---

## Final Note

The boilerplate is intentionally strict.
The constraints are what make v1.0 stable.
