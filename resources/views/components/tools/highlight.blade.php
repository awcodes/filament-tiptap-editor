<x-filament-tiptap-editor::button
    x-show="tools.includes('highlight')"
    style="display: none;"
    action="editor().chain().focus().toggleHighlight().run()"
    active="'highlight'"
    label="{{ __('filament-tiptap-editor::editor.highlight') }}"
    icon="highlight"
/>