<x-filament-tiptap-editor::button
    x-show="tools.includes('bold')"
    style="display: none;"
    action="editor().chain().focus().toggleBold().run()"
    active="'bold'"
    label="{{ __('filament-tiptap-editor::editor.bold') }}"
    icon="bold"
/>