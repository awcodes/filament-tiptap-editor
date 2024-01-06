<x-filament-tiptap-editor::button
    action="editor().chain().focus().setTextAlign('end').run()"
    active="{ textAlign: 'end' }"
    label="{{
        config('filament-tiptap-editor.direction') === 'rtl'
            ? trans('filament-tiptap-editor::editor.align_left')
            : trans('filament-tiptap-editor::editor.align_right')
    }}"
    icon="{{
        config('filament-tiptap-editor.direction') === 'rtl'
            ? 'align-left'
            : 'align-right'
    }}"
/>
