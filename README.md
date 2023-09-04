# Filament Tiptap Editor

A Tiptap integration for Filament Admin/Forms.

> **Warning**
> If you are using the Curator integration for media uploads you will need to update to version 2.3.0 or higher.

![tiptap-editor-og](https://res.cloudinary.com/aw-codes/image/upload/w_1200,f_auto,q_auto/plugins/tiptap-editor/awcodes-tiptap-editor.jpg)

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

### Rendering content in Blade files

If you are storing your content as JSON then you will likely need to parse the data to HTML for output in Blade files. To help with this there is a helper function `tiptap_converter` that will convert the data to one of the three supported Tiptap formats. 

Styling the output is entirely up to you.

```blade
{!! tiptap_converter()->asHTML($post->content) !!}
{!! tiptap_converter()->asJSON($post->content) !!}
{!! tiptap_converter()->asText($post->content) !!}
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

## Bubble and Floating Menus

By default, the editor uses Bubble and Floating menus to help with creating content inline, so you don't have to use the toolbar. If you'd prefer to not use the menus you can disable them on a per-instance basis or globally in the config file.

```php
TiptapEditor::make('content')
    ->disableFloatingMenus()
    ->disableBubbleMenus();
```
    
```php
[
    'disable_floating_menus' => true,
    'disable_bubble_menus' => true,
    ...
]
```

You can also provide you own tools to for the floating menu, should you choose. Defaults can be overwritten via the config file.

```php
TiptapEditor::make('content')
    ->floatingMenuTools(['grid-builder', 'media', 'link'])
```

```php
[
    ...
    'floating_menu_tools' => ['media', 'grid', 'grid-builder', 'details', 'table', 'oembed', 'code-block']
]
```

## Usage in Standalone Forms Package

1. Install tippy.js and @ryangjchandler/alpine-tooltip

```bash
npm install -D tippy.js @ryangjchandler/alpine-tooltip
```

2. Import the plugin's JS file into your app's JS file and register Alpine Tooltip

```js
import Tooltip from '@ryangjchandler/alpine-tooltip'
import '../../vendor/awcodes/filament-tiptap-editor/resources/js/plugin.js'

Alpine.plugin(Tooltip);
```

3. Import the plugin's CSS and Tippy's CSS file into your app's CSS file

```css
@import '../../vendor/awcodes/filament-tiptap-editor/resources/css/plugin.css';
@import '../../node_modules/tippy.js/dist/tippy.css';
```

4. If you are using any of the tools that require a modal (e.g. Insert media, Insert video, etc.), make sure to add `{{ $this->modal }}` to your view after the custom form:

```php
<form wire:submit.prevent="submit">
    {{ $this->form }}

    <button type="submit">
        Save
    </button>
</form>

{{ $this->modal }}
```


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
