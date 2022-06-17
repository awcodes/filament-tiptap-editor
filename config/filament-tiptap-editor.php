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
            'color',
            'bulletList',
            'orderedList',
            'checkedList',
            'align',
            'blockquote',
            'hr',
            'link',
            'superscript',
            'subscript',
            'table',
            'grid',
            'media',
            'code',
            'codeblock',
            'source',
            'youtube',
            'vimeo',
        ],
        'simple' => ['bold', 'italic', 'h1', 'h2', 'h3', 'lead', 'hr', 'bulletList', 'orderedList', 'checkedList', 'link', 'media'],
        'barebone' => ['bold', 'italic', 'link', 'bulletList', 'orderedList'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Link Modal
    |--------------------------------------------------------------------------
    |
    */
    'link_modal_id' => 'filament-tiptap-editor-link-modal',

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
