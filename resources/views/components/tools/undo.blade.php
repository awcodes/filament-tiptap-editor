<x-filament-tiptap-editor::button
    action="editor().chain().focus().undo().run()"
    active="undo"
    label="{{ __('filament-tiptap-editor::editor.undo') }}"
    icon="undo"
/>