<x-filament-tiptap-editor::button
    action="editor().chain().focus().undo().run()"
    active="undo"
    label="{{ trans('filament-tiptap-editor::editor.undo') }}"
    icon="undo"
/>