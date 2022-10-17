<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Theme overrides
    |--------------------------------------------------------------------------
    |
    | Theme overrides can be used to style parts of the editor content.
    | theme_builder : 'mix' | 'vite'
    | theme_path : css/tiptap-editor-styles.css
    |
    */
    'theme_builder' => 'mix',
    'theme_path' => null,

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
            'heading',
            'lead',
            'small',
            'color',
            'bullet-list',
            'ordered-list',
            'checked-list',
            'align-left',
            'align-center',
            'align-right',
            'blockquote',
            'hr',
            'highlight',
            'link',
            'superscript',
            'subscript',
            'table',
            'grid',
            'media',
            'code',
            'code-block',
            'source',
            'youtube',
            'vimeo',
            'details',
            'hurdle',
        ],
        'simple' => ['bold', 'italic', 'heading', 'lead', 'hr', 'bullet-list', 'ordered-list', 'checked-list', 'link', 'media'],
        'barebone' => ['bold', 'italic', 'link', 'bullet-list', 'ordered-list'],
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
