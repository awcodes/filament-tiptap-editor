<x-filament-tiptap-editor::button
    action="editor().chain().focus().setTextAlign('start').run()"
    label="{{ config('filament-tiptap-editor.direction') === 'rtl' ? __('filament-tiptap-editor::editor.align_right') : __('filament-tiptap-editor::editor.align_left') }}"
    icon="{{ config('filament-tiptap-editor.direction') === 'rtl' ? 'align-right' : 'align-left' }}"
/>