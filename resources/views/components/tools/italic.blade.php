<x-filament-tiptap-editor::button
    x-show="tools.includes('italic')"
    style="display: none;"
    action="editor().chain().focus().toggleItalic().run()"
    active="'italic'"
    label="{{ __('filament-tiptap-editor::editor.italic') }}"
    icon="italic"
/>