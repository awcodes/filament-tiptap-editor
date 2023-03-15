# Filament Tiptap Editor

A Tiptap ingtegration for Filament Admin/Forms.

![tiptap-editor-og](https://user-images.githubusercontent.com/3596800/225422449-b1aa125f-7704-42c8-9efa-440972d88ca0.png)

- Supports Light/Dark Mode
- Fullscreen editing
- Overrideable Media uploading
- Profile based toolbars to simplify reusing features

## Installation

Install the package via composer

```bash
composer require awcodes/filament-tiptap-editor
```

## Usage

The editor extends the default Field class so most other methods available on that class can be used when adding it to a form.

```php
use FilamentTiptapEditor\TiptapEditor;

TiptapEditor::make('content')
    ->profile('default|simple|barebone|custom')
    ->tools([]) // individual tools to use in the editor, overwrites profile
    ->disk('string') // optional, defaults to config setting
    ->directory('string or Closure returning a string') // optional, defaults to config setting
    ->acceptedFileTypes(['array of file types']) // optional, defaults to config setting
    ->maxFileSize('integer in KB') // optional, defaults to config setting
    ->output('json') // optional, change the output format. defaults is html
    ->maxContentWidth('5xl')
    ->required();
```

## Config

Publish the config file.

```bash
php artisan vendor:publish --tag="filament-tiptap-editor-config"
```

### Profiles / Tools

The package comes with 3 profiles for buttons/tools out of the box.

- default: includes all available tools
- simple
- barebone

See `filament-tiptap-editor.php` config file for modifying profiles to add / remove buttons from the editor or to create your own.

Tools can also be added on a per instance basis. Using the `->tools()` modifier will overwrite the profile set for the instance. A full list of tools can be found in the `filament-tiptap-editor.php` config file under the default profile setting.

### Media / Images

- accepted_file_types: ['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml', 'application/pdf']
- disk: 'public'
- directory: 'images'
- visibility: 'public'
- preserve_file_names: false
- max_file_size: 2042
- image_crop_aspect_ratio: null
- image_resize_target_width: null
- image_resize_target_height: null

### Output format

Tiptap editor has 3 different output formats.
See: https://tiptap.dev/guide/output

If you want to change the output format you can change the default config or specify it in each form instances.
For each form field instances you can add the following option:

```php
    TiptapEditor::make('content')
        // ... other options
        ->output(FilamentTiptapEditor\TiptapEditor::OUTPUT_JSON);
```

- HTML (Format type: `FilamentTiptapEditor\TiptapEditor::OUTPUT_HTML`)
- JSON (Format type: `FilamentTiptapEditor\TiptapEditor::OUTPUT_JSON`)
- Text (Format type: `FilamentTiptapEditor\TiptapEditor::OUTPUT_TEXT`)

**or as string**
```php
    TiptapEditor::make('content')
        // ... other options
        ->output('json');
```

- HTML (`html`)
- JSON (`json`)
- Text (`text`)

**Note:**

If you want to store the editor content as array / json you have to set the database column as `longText` or `json` type. And cast it appropriately in your model class.

For example:

```php
   $table->json('content');
```

### RTL Support

In order for things like text align to work properly with RTL languages you 
can switch the `direction` key in the config to 'rtl'.

```php
[
    'direction' => 'rtl'
    ...
]
```

### Max Content Width

To adjust the max content width of the editor globally set `max_content_width` 
key in the config to one of the tailwind max width sizes or `full` for full width. 
This could also be set on a per instance basis with the `->maxContentWidth()` method.

```php
[
    'max_content_width' => 'full'
    ...
]
```

```php
use FilamentTiptapEditor\TiptapEditor;

TiptapEditor::make('content')
    ->maxContentWidth('3xl');
```

## Overrides

The Link and Media modals are built using Filament Form Component Actions. This means it is easy enough to swap them out with your own implementations.

### Link Modal

You may override the default Link modal with your own Action and assign to the `link_action` key in the config file. Make sure the default name for your action is `filament_tiptap_link`.

See `vendor/awcodes/filament-tiptap-editor/src/Actions/LinkAction.php` for implementation.

### Media Modal

You may override the default Media modal with your own Action and assign to the `media_action` key in the config file. Make sure the default name for your action is `filament_tiptap_media`.

See `vendor/awcodes/filament-tiptap-editor/src/Actions/MediaAction.php` for implementation.

### Initial height of editor field

You can add extra input attributes to the field with the `extraInputAttributes()` method. This allows you to do things like set the initial height of the editor.

```php
TiptapEditor::make('barebone')
    ->profile('barebone')
    ->extraInputAttributes(['style' => 'min-height: 12rem;']),
```

## Usage in Standalone Forms Package

1. Publish the JS/CSS assets

```bash
php artisan vendor:publish --tag="filament-tiptap-editor-assets"
```

2. Include the CSS files in your page / layout
2. Include the JS files in your page / layout before Filament's scripts
3. Include a `@stack('modals')` in your page / layout if it doesn't exist

## Theming

If you are using a custom theme for Filament you will need to add this plugin's views to your Tailwind CSS config.

```js
content: [
    ...
    "./vendor/awcodes/filament-tiptap-editor/resources/views/**/*.blade.php",
],
```

## Versioning

This projects follow the [Semantic Versioning](https://semver.org/) guidelines.

## License

Copyright (c) 2022 Adam Weston and contributors

Licensed under the MIT license, see [LICENSE.md](LICENSE.md) for details.
