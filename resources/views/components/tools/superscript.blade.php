<x-filament-tiptap-editor::button
    x-show="tools.includes('superscript')"
    style="display: none;"
    action="editor().chain().focus().toggleSuperscript().run()"
    active="'superscript'"
    label="{{ __('filament-tiptap-editor::editor.superscript') }}"
    icon="superscript"
/>