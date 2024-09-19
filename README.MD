# Craft CMS Faker

Craft CMS Faker provides a handful of models that you can create an instance of in your Twig code to "fake" various components of Craft CMS. 

For example, if you're passing an image to a template, you can access the attributes of the "fake" image just as you would with an Asset from Craft CMS.

## Installation
To install the plugin, require it as a composer dependancy as follows: 
```
composer require Mission10/craftcms-faker
```

You can then install the plugin via the plugins section of Craft or by running the following command. 
```
php craft plugin/install faker
```

## Usage 

### Entry
Designed to fake a CMS entry, the Entry model can accept any attributes and be assigned to the returned model.
To access the attributes passed in, use the `get()` function.
```
craft.faker.entry({
    title: "My entry", 
    myCustomField: "My custom field value"
}).get();
```

### Collection
A collection allows you to run the functions `all()`, `nth()` and `first()` on any array. Helpful for looping in your Twig templates
```
craft.faker.collection([
    "my first item", 
    "my second item"
])
```

### Asset
Designed to mimic an asset from Craft's asset volumes, this can be used in many different ways. 

#### Available attributes
- url
- title
- alt
- kind
- source
- width 
- height
- text
- id
- query

#### Sources
You can select a source to be used when generating default images. You can choose [Lorem Picsum](https://picsum.photos) _(default)_, [Placeholder](https://placeholder.com) or [Unsplash](https_://unsplash.com/). 

This can either be selected in the CMS Settings and can be set to a ENV variable. 

You can also overwrite this setting per image by passing the `source` attribute with either `picsum`, `placeholder` or `unsplash` as the value. 

#### Default URL
If no URl is passed to the function, a URL will be created for you using your selected source (see above). 
If you want an image generated automatically, simply do not include the URL attribute, instead you can optionally pass a height and width. 

#### Sizing
You can pass `width` and `height` if you require. This is useful for passing images that are later transformed. Both will default to 200. 

#### ID
When using `unsplash`, you can pass an id to select a specific image.

#### Query
When using `unsplash`, you can pass a query string to retrieve images related to the query.

#### Example

```
craft.faker.asset({
    url: "my-image-url", 
    title: "Fake image", 
    alt: "My fake image", 
    kind: "image"
})

craft.faker.asset({
    source: "placeholder"
    width: 1080, 
    height: 720
})
```

### Super Table
Designed to mimic [Super Table](https://verbb.io/craft-plugins/super-table/features), this is identical to using Collections. Simply pass in an array of data to access. 
Each array item should be wrapped in curly brackets to mimic a row. 

```
craft.faker.superTable([
    {
        myColumnName: "my first columns data", 
        mySecondColumnName: "my second columns data", 
    },
    {
        myColumnName: "my first columns data", 
        mySecondColumnName: "my second columns data", 
    },
])
```

### Donkey Tail
Designed to replicate [Donkey Tail](https://simplygoodwork.com/blog/donkeytail-use-cases-for-craft-cms), you can use this to replicate a DonkeyTail field and pass in your image and pins.

#### Available attributes
- Image
- Pins

```
craft.faker.donkeytail({
    image: yourAssetField, 
    pins: [
        {
            x: 10, 
            y: 10, 
            element: yourElementField
        }
    ]
})
```

Each of the functions can be combined and used within each other. For example, if you had a collection of assets:

```
craft.faker.collection([
    craft.faker.asset(),
    craft.faker.asset(),
    craft.faker.asset()
])
```

or if you had an asset within a Super Table field

```
craft.faker.superTable([
    {
        image: craft.faker.asset()
    }
])
```

You can even fake entries and assets within a Donkey Tail field. 

```
craft.faker.donkeytail({
    image: craft.faker.asset(), 
    pins: [
        {
            x: 10, 
            y:10, 
            element: craft.faker.entry()
        }
    ]
})
```

If not provided in the attributes, the model will automatically assign a title, URL, slug and postDate.


### Navigation
Designed to mimic [Verbb's navigation plugin](https://verbb.io/craft-plugins/navigation/features), the navigation function will accept an integer for the total amount of items to return and will provide a Collection of items, each with: 
- url
- title
- active
- newWindow
- customAttributes
- hasDescendants
- children

If you would like to return multi-level navigation, you can add a `true` second parameter. This currently only supports two levels.

```
craft.faker.navigation(4)
craft.faker.navigation(4, true)
```

## Contact 
Jordan Beattie <br>
jordan@mission10.co.uk <br>
Aberdeen, Scotland
