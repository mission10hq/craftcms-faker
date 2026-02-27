# Craft CMS Faker

Craft CMS Faker provides fake/mock models for Twig template development. It lets you prototype templates without needing real CMS data by creating fake entries, assets, collections, and third-party field types.

All fake models are accessed via `craft.faker.*` in your Twig templates.

## Installation

Require the plugin via Composer:
```
composer require mission10/craftcms-faker
```

Then install it via the Craft control panel or the CLI:
```
php craft plugin/install faker
```

## Usage

### Entry

Designed to fake a CMS entry, the Entry model accepts any attributes and returns them on the model. Use the `get()` method to access the attributes as an object.

If not provided, the model automatically assigns default values for `title`, `url`, `slug`, and `postDate`.

```twig
{% set entry = craft.faker.entry({
    title: "My entry",
    myCustomField: "My custom field value"
}).get() %}

{{ entry.title }}
{{ entry.myCustomField }}
```

### Collection

A Collection wraps an array and provides query-like methods for looping in Twig templates. It implements `Countable`, so you can use `|length` in Twig.

```twig
{% set items = craft.faker.collection([
    "my first item",
    "my second item",
    "my third item"
]) %}
```

#### Methods

| Method | Description |
|---|---|
| `all()` | Returns the underlying array of items |
| `one()` | Returns the first item |
| `first()` | Returns the first item |
| `nth(n)` | Returns the item at index `n` |
| `limit(n)` | Returns a new Collection with only the first `n` items |
| `offset(n)` | Returns a new Collection skipping the first `n` items |
| `count()` | Returns the number of items |
| `add(item)` | Appends an item (or array of items) and returns the Collection |

```twig
{% for item in items.limit(2).all() %}
    {{ item }}
{% endfor %}

{{ items.count() }}
{{ items.first() }}
```

### Asset

Designed to mimic an asset from Craft's asset volumes. Supports both images and videos.

#### Attributes

| Attribute | Default | Description |
|---|---|---|
| `url` | Auto-generated from source | The asset URL |
| `title` | `"Default asset title"` | The asset title |
| `alt` | Same as title | Alt text |
| `kind` | `"image"` | `"image"` or `"video"` |
| `source` | Plugin setting | Image source provider |
| `width` | `200` | Width in pixels |
| `height` | `200` | Height in pixels |
| `id` | — | Unsplash image ID |
| `query` | — | Unsplash search query |
| `text` | — | Placeholder text (for placeholder/dummyimage sources) |
| `customFields` | `[]` | Key-value pairs accessible as properties |

#### Image Sources

You can select a default image source in the plugin's CP settings (supports environment variables). Available sources:

- **[Lorem Picsum](https://picsum.photos)** _(default)_ — Random photos
- **[Placeholder / DummyImage](https://dummyimage.com)** — Solid color placeholders with text
- **[Unsplash](https://unsplash.com)** — High-quality photos (supports `id` and `query`)
- **Local** — Inline SVG placeholder (no external requests)

Override the source per-asset by passing the `source` attribute.

#### Video Support

Set `kind` to `"video"` to get a fake video asset. When no `url` is provided, a sample video URL is generated automatically.

```twig
{% set video = craft.faker.asset({
    kind: "video"
}) %}

<video src="{{ video.url }}"></video>
```

#### Methods

| Method | Description |
|---|---|
| `url()` | Returns the asset URL |
| `getUrl()` | Alias of `url()` |
| `width()` | Returns the width |
| `height()` | Returns the height |
| `setTransform(attrs)` | Sets `width`/`height` from an attribute hash; returns `self` for chaining |
| `getSrcset(sizes)` | Generates a srcset string from an array of widths |

#### Custom Fields

Pass a `customFields` hash to add arbitrary properties to the asset, accessible via standard Twig property syntax:

```twig
{% set image = craft.faker.asset({
    title: "Hero image",
    customFields: {
        caption: "Photo by Example",
        photographer: "Jane Doe"
    }
}) %}

{{ image.caption }}
{{ image.photographer }}
```

#### Examples

```twig
{# Auto-generated image from default source #}
{% set image = craft.faker.asset() %}

{# Custom URL and attributes #}
{% set image = craft.faker.asset({
    url: "my-image-url",
    title: "Fake image",
    alt: "My fake image",
    kind: "image"
}) %}

{# Placeholder source with custom dimensions #}
{% set image = craft.faker.asset({
    source: "placeholder",
    width: 1080,
    height: 720
}) %}

{# Using setTransform and getSrcset #}
{% set image = craft.faker.asset({ width: 800, height: 600 }) %}
{% set transformed = image.setTransform({ width: 400, height: 300 }) %}
<img src="{{ image.url }}" srcset="{{ image.getSrcset([400, 800, 1200]) }}">
```

### Link

Designed to mimic a link field (e.g., Ether's Typed Link Field or the native Craft CMS link field). Provides methods compatible with common link field APIs.

#### Attributes

| Attribute | Default | Description |
|---|---|---|
| `url` | `"#"` | The link URL |
| `text` | `"Learn more"` | The link text |
| `target` | `null` | Target attribute (e.g., `"_blank"`) |

#### Methods

| Method | Description |
|---|---|
| `url()` / `getUrl()` | Returns the URL |
| `text()` / `getText()` | Returns the link text |
| `label()` | Alias of `text()` |
| `target()` / `getTarget()` | Returns the target, or `false` if none |
| `isEmpty()` | Always returns `false` |
| `getLinkAttributes(extraAttributes)` | Returns an HTML attribute string (`href="..."`, optional `target`, plus any extra attributes) |

#### Examples

```twig
{% set link = craft.faker.link({
    url: "/about",
    text: "About Us",
    target: "_blank"
}) %}

<a {{ link.getLinkAttributes() }}>{{ link.text }}</a>

<a {{ link.getLinkAttributes({ class: "btn", "data-track": "cta" }) }}>{{ link.text }}</a>
```

### Icon

Designed to mimic the [Icon Picker](https://verbb.io/craft-plugins/icon-picker/features) field type. Supports glyph, sprite, CSS, and SVG icon types.

#### Parameters

The `icon()` method accepts individual parameters:

```twig
craft.faker.icon(icon, sprite, glyphId, glyphName, iconSet, type, css, width, height)
```

| Parameter | Default | Description |
|---|---|---|
| `icon` | `null` | SVG icon path |
| `sprite` | `null` | Sprite reference |
| `glyphId` | `"59389"` | Glyph decimal ID |
| `glyphName` | `"unie7fd"` | Glyph name |
| `iconSet` | `"MaterialIconsSharp"` | Icon set name |
| `type` | `"glyph"` | One of: `glyph`, `sprite`, `css`, `svg` |
| `css` | `null` | CSS class name |
| `width` | `null` | Icon width |
| `height` | `null` | Icon height |

#### Examples

```twig
{# Default glyph icon #}
{% set icon = craft.faker.icon() %}
{{ icon.glyphName }}
{{ icon.getGlyph() }}

{# Sprite icon #}
{% set icon = craft.faker.icon(null, "my-sprite", null, null, null, "sprite") %}

{# CSS icon #}
{% set icon = craft.faker.icon(null, null, null, null, "FontAwesome", "css", "fa-home") %}
```

### Super Table

Designed to mimic [Super Table](https://verbb.io/craft-plugins/super-table/features). Identical to using a Collection — pass in an array of row objects.

```twig
{% set table = craft.faker.superTable([
    {
        myColumnName: "my first row data",
        mySecondColumnName: "my second column data",
    },
    {
        myColumnName: "my second row data",
        mySecondColumnName: "my second column data",
    },
]) %}

{% for row in table.all() %}
    {{ row.myColumnName }}
{% endfor %}
```

### Donkey Tail

Designed to replicate [Donkey Tail](https://simplygoodwork.com/blog/donkeytail-use-cases-for-craft-cms). Extends Asset, adding pin support for hotspots on images.

#### Attributes

| Attribute | Default | Description |
|---|---|---|
| `url` | `"https://picsum.photos/200"` | The image URL |
| `pins` | `[]` | Array of pin objects with `x`, `y`, and `element` |

```twig
{% set hotspot = craft.faker.donkeytail({
    image: craft.faker.asset(),
    pins: [
        {
            x: 10,
            y: 10,
            element: craft.faker.entry()
        }
    ]
}) %}
```

### Navigation

Designed to mimic [Verbb's Navigation plugin](https://verbb.io/craft-plugins/navigation/features). Accepts a total number of items and returns a Collection of navigation items.

Each item includes: `url`, `title`, `active`, `newWindow`, `customAttributes`, and optionally `hasDescendants` and `children`.

Pass `true` as the second parameter for two-level navigation.

```twig
{# Single-level navigation #}
{% set nav = craft.faker.navigation(4) %}

{# Two-level navigation #}
{% set nav = craft.faker.navigation(4, true) %}

{% for item in nav.all() %}
    <a href="{{ item.url }}">{{ item.title }}</a>
    {% if item.children is defined %}
        {% for child in item.children %}
            <a href="{{ child.url }}">{{ child.title }}</a>
        {% endfor %}
    {% endif %}
{% endfor %}
```

## Composing Models

All fake models are composable — you can nest them inside each other:

```twig
{# A collection of assets #}
{% set gallery = craft.faker.collection([
    craft.faker.asset(),
    craft.faker.asset(),
    craft.faker.asset()
]) %}

{# Assets inside a Super Table #}
{% set table = craft.faker.superTable([
    { image: craft.faker.asset() },
    { image: craft.faker.asset() }
]) %}

{# Entries and assets inside a Donkey Tail field #}
{% set hotspot = craft.faker.donkeytail({
    image: craft.faker.asset(),
    pins: [
        { x: 10, y: 10, element: craft.faker.entry() }
    ]
}) %}
```
