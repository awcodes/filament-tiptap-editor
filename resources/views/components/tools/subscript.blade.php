<x-filament-tiptap-editor::button
    x-show="tools.includes('subscript')"
    style="display: none;"
    action="editor().chain().focus().toggleSubscript().run()"
    active="'subscript'"
    label="{{ __('filament-tiptap-editor::editor.subscript') }}"
    icon="subscript"
/>