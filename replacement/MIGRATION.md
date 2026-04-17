# Removing craftcms-faker: Migration Guide

This guide walks you through completely removing the `mission10/craftcms-faker` plugin and replacing it with a self-contained Craft module (or pure Twig macros) that lives in your project.

## Choose Your Approach

| Approach | Effort | Template changes? | PHP required? | Full API parity? |
|----------|--------|-------------------|---------------|------------------|
| **Module** (recommended) | 15 min | None | Yes | Yes |
| **Twig macros** | 10 min | Yes (minor) | No | No (returns hashes, not objects) |
| **Hybrid** | 15 min | Partial | Minimal | Mostly |

---

## Option 1: Drop-in Module (zero template changes)

This keeps the exact same `craft.faker.*` API so your templates stay untouched.

### Step 1: Copy module files

Copy the `modules/faker/` directory into your project:

```
your-project/
├── modules/
│   └── faker/
│       ├── FakerModule.php
│       ├── FakerVariable.php
│       ├── models/
│       │   ├── FakeAsset.php
│       │   ├── FakeCollection.php
│       │   ├── FakeDonkeyTail.php
│       │   ├── FakeEntry.php
│       │   ├── FakeIcon.php
│       │   ├── FakeLink.php
│       │   └── FakeSuperTable.php
│       └── twigextensions/
│           └── FakerTwigExtension.php
```

### Step 2: Register the module

In `config/app.php`:

```php
<?php

return [
    'modules' => [
        'faker' => [
            'class' => \modules\faker\FakerModule::class,
            // Change the default image source here if needed:
            // 'imageSource' => 'unsplash',
        ],
    ],
    'bootstrap' => ['faker'],
];
```

### Step 3: Register the autoloader

In `composer.json`, add the module namespace to `autoload.psr-4`:

```json
{
    "autoload": {
        "psr-4": {
            "modules\\faker\\": "modules/faker/"
        }
    }
}
```

Then run:

```bash
composer dump-autoload
```

### Step 4: Remove the plugin

```bash
composer remove mission10/craftcms-faker
```

If the plugin is listed in `config/plugins.php`, remove the `'faker'` entry.

Also remove its project config entry if present:

```bash
rm config/project/plugins/faker.yaml  # if it exists
```

### Step 5: Configure image source (optional)

The old plugin stored the image source in CP settings. The module uses a simple config value instead. Set it in `config/app.php` (shown in Step 2) or by setting the `imageSource` property.

Available sources: `picsum` (default), `unsplash`, `placeholder`, `dummyImage`, `local`

### Step 6: Test

Your templates should work exactly as before. All `craft.faker.*` calls remain valid:

```twig
{# These all still work unchanged #}
{% set image = craft.faker.asset({ width: 1080, height: 720 }) %}
{% set entry = craft.faker.entry({ title: "Test" }) %}
{% set nav = craft.faker.navigation(4, true) %}
{% set link = craft.faker.link({ url: "/page", text: "Click" }) %}
```

---

## Option 2: Twig Macros (no PHP at all)

Use this if you want zero PHP code and don't mind adjusting template calls.

### Step 1: Copy the macros file

Copy `templates/_macros/faker.twig` into your project's `templates/_macros/` directory.

### Step 2: Update templates

Change every template that uses `craft.faker`:

**Before (plugin):**
```twig
{% set image = craft.faker.asset({ width: 800, height: 600 }) %}
<img src="{{ image.url }}" alt="{{ image.alt }}">

{% set entry = craft.faker.entry({ title: "Hello" }).get() %}
<h1>{{ entry.title }}</h1>

{% set nav = craft.faker.navigation(4, true) %}
{% for item in nav.all() %}
    <a href="{{ item.url }}">{{ item.title }}</a>
{% endfor %}
```

**After (macros):**
```twig
{% import '_macros/faker' as faker %}

{% set image = faker.asset({ width: 800, height: 600 }) %}
<img src="{{ image.url }}" alt="{{ image.alt }}">

{% set entry = faker.entry({ title: "Hello" }) %}
<h1>{{ entry.title }}</h1>

{% set nav = faker.navigation(4, true) %}
{% for item in nav %}
    <a href="{{ item.url }}">{{ item.title }}</a>
{% endfor %}
```

**Key differences:**
- Add `{% import '_macros/faker' as faker %}` at the top of each template
- `craft.faker.` becomes `faker.`
- `.get()` on entries is no longer needed (it's already a hash)
- `.all()` on collections is no longer needed (it's already an array)
- `.one()`, `.first()`, `.nth()`, `.limit()`, `.offset()` are replaced with Twig filters: `|first`, `|slice(0, 3)`, etc.
- `.url()` method calls become `.url` property access
- `.text()` method calls become `.text` property access

### Step 3: Remove the plugin

```bash
composer remove mission10/craftcms-faker
```

---

## Option 3: Hybrid

Use the module for Asset and Collection (where you need methods like `.getUrl()`, `.setTransform()`, `.one()`, `.limit()`), and macros for the simple types.

Follow the module setup (Option 1 Steps 1-4), then also copy the macros file and use whichever approach fits each template.

---

## API Reference: What Changed

### Module (Option 1) — nothing changes

| Plugin API | Module API | Notes |
|-----------|-----------|-------|
| `craft.faker.asset(...)` | `craft.faker.asset(...)` | Identical |
| `craft.faker.entry(...)` | `craft.faker.entry(...)` | Identical |
| `craft.faker.link(...)` | `craft.faker.link(...)` | Identical |
| `craft.faker.collection(...)` | `craft.faker.collection(...)` | Identical |
| `craft.faker.supertable(...)` | `craft.faker.supertable(...)` | Identical |
| `craft.faker.donkeytail(...)` | `craft.faker.donkeytail(...)` | Identical |
| `craft.faker.navigation(n, children)` | `craft.faker.navigation(n, children)` | Identical |
| `craft.faker.icon(...)` | `craft.faker.icon(...)` | Identical |

### Module bonus Twig functions

| Function | Example | Notes |
|----------|---------|-------|
| `faker_image(w, h, source)` | `{{ faker_image(800, 600) }}` | Returns just the URL string |
| `faker_video()` | `{{ faker_video() }}` | Returns just the URL string |

### Icon model changes

The original plugin's `Icon.getUrl()`, `Icon.getPath()`, `Icon.getInline()`, `Icon.getDimensions()`, and `Icon.getRemoteSet()` methods depended on the Icon Picker plugin being installed. The replacement `FakeIcon` drops these — if you need them, you already have Icon Picker installed and should use its real data instead of faking it.

---

## Checklist

- [ ] Copy module files (or macros file) into your project
- [ ] Register the module in `config/app.php` (if using module)
- [ ] Add PSR-4 autoload entry to `composer.json` (if using module)
- [ ] Run `composer dump-autoload`
- [ ] Run `composer remove mission10/craftcms-faker`
- [ ] Remove `faker` from `config/plugins.php` (if present)
- [ ] Remove `config/project/plugins/faker.yaml` (if present)
- [ ] Update templates (if using macros approach)
- [ ] Test all pages that used `craft.faker`
- [ ] Clear Craft caches: `php craft clear-caches/all`
