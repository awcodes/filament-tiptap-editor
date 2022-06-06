<?php

return [
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
            'undo',
            'redo',
            'erase',
            'bold',
            'italic',
            'strike',
            'underline',
            'h1',
            'h2',
            'h3',
            'h4',
            'h5',
            'h6',
            'lead',
            'small',
            'hr',
            'table',
            'bulletList',
            'orderedList',
            'checkedList',
            'link',
            'media',
            'blockquote',
            'superscript',
            'subscript',
            'color',
            'code',
            'codeblock',
            'align',
            'source',
        ],
        'simple' => ['undo', 'redo', 'bold', 'italic', 'h1', 'h2', 'h3', 'lead', 'hr', 'bulletList', 'orderedList', 'checkedList', 'link', 'media'],
        'barebone' => ['bold', 'italic', 'link', 'redo', 'undo'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Media Uploader
    |--------------------------------------------------------------------------
    |
    | These options will be passed to the native file uploader modal when
    | inserting media. They follow the same conventions as the
    | Filament Forms FileUpload field.
    |
    | See https://filamentphp.com/docs/2.x/forms/fields#file-upload
    |
    */
    'accepted_file_types' => ['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml', 'application/pdf'],
    'disk' => 'public',
    'directory' => 'images',
    'visibility' => 'public',
    'preserve_file_names' => false,
    'max_file_size' => 2042,
    'image_crop_aspect_ratio' => null,
    'image_resize_target_width' => null,
    'image_resize_target_height' => null,
    'media_uploader_id' => 'filament-tiptap-editor-media-uploader-modal', // Default
    // 'media_uploader_id' => 'filament-curator-media-picker', // Filament Curator
];
