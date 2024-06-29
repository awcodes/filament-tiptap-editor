<?php

return [
    'direction' => 'ltr',
    'max_content_width' => '5xl',
    'disable_stylesheet' => false,
    'disable_link_as_button' => false,

    /*
    |--------------------------------------------------------------------------
    | Profiles
    |--------------------------------------------------------------------------
    |
    | Profiles determine which tools are available for the toolbar.
    | 'default' is all available tools, but you can create your own subsets.
    | The order of the tools doesn't matter.
    |
    */
    'profiles' => [
        'default' => [
            'heading', 'bullet-list', 'ordered-list', 'checked-list', 'blockquote', 'hr', '|',
            'bold', 'italic', 'strike', 'underline', 'superscript', 'subscript', 'lead', 'small', 'color', 'highlight', 'align-left', 'align-center', 'align-right', '|',
            'link', 'media', 'oembed', 'table', 'grid-builder', 'details', '|', 'code', 'code-block', 'source', 'blocks',
        ],
        'simple' => ['heading', 'hr', 'bullet-list', 'ordered-list', 'checked-list', '|', 'bold', 'italic', 'lead', 'small', '|', 'link', 'media'],
        'minimal' => ['bold', 'italic', 'link', 'bullet-list', 'ordered-list'],
        'none' => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | Actions
    |--------------------------------------------------------------------------
    |
    */
    'media_action' => FilamentTiptapEditor\Actions\MediaAction::class,
    //    'media_action' => Awcodes\Curator\Actions\MediaAction::class,
    'edit_media_action' => FilamentTiptapEditor\Actions\EditMediaAction::class,
    'link_action' => FilamentTiptapEditor\Actions\LinkAction::class,

    /*
    |--------------------------------------------------------------------------
    | Output format
    |--------------------------------------------------------------------------
    |
    | Which output format should be stored in the Database.
    |
    | See: https://tiptap.dev/guide/output
    */
    'output' => FilamentTiptapEditor\Enums\TiptapOutput::Html,

    /*
    |--------------------------------------------------------------------------
    | Media Uploader
    |--------------------------------------------------------------------------
    |
    | These options will be passed to the native file uploader modal when
    | inserting media. They follow the same conventions as the
    | Filament Forms FileUpload field.
    |
    | See https://filamentphp.com/docs/3.x/panels/installation#file-upload
    |
    */
    'accepted_file_types' => ['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml', 'application/pdf'],
    'disk' => 'public',
    'directory' => 'images',
    'visibility' => 'public',
    'preserve_file_names' => false,
    'max_file_size' => 2042,
    'min_file_size' => 0,
    'image_resize_mode' => null,
    'image_crop_aspect_ratio' => null,
    'image_resize_target_width' => null,
    'image_resize_target_height' => null,
    'use_relative_paths' => true,

    /*
    |--------------------------------------------------------------------------
    | Menus
    |--------------------------------------------------------------------------
    |
    */
    'disable_floating_menus' => false,
    'disable_bubble_menus' => false,
    'disable_toolbar_menus' => false,

    'bubble_menu_tools' => ['bold', 'italic', 'strike', 'underline', 'superscript', 'subscript', 'lead', 'small', 'link'],
    'floating_menu_tools' => ['media', 'grid-builder', 'details', 'table', 'oembed', 'code-block', 'blocks'],

    /*
    |--------------------------------------------------------------------------
    | Extensions
    |--------------------------------------------------------------------------
    |
    */
    'extensions_script' => null,
    'extensions_styles' => null,
    'extensions' => [],
];
