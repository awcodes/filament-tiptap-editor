<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Theme overrides
    |--------------------------------------------------------------------------
    |
    | Theme overrides can be used to style parts of the editor content.
    | theme_builder : 'mix' | 'vite'
    | theme_file : resources/css/tiptap-editor-styles.css
    | theme_folder : 'build'
    |
    */
    'theme_builder' => 'mix',
    'theme_file' => null,
    'theme_folder' => 'build',

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
            'heading',
            'bullet-list',
            'ordered-list',
            'checked-list',
            'blockquote',
            'hr',
            '|',
            'bold',
            'italic',
            'strike',
            'underline',
            'superscript',
            'subscript',
            'lead',
            'small',
            'color',
            'highlight',
            'align-left',
            'align-center',
            'align-right',
            '|',
            'link',
            'media',
            'table',
            'grid',
            'details',
            'hurdle',
            'youtube',
            'vimeo',
            '|',
            'code',
            'code-block',
            'source',
        ],
        'simple' => ['heading', 'hr', 'bullet-list', 'ordered-list', 'checked-list', '|', 'bold', 'italic', 'lead', 'small', '|', 'link', 'media'],
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
