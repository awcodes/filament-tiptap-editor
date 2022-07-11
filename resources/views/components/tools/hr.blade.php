<x-filament-tiptap-editor::button
    x-show="tools.includes('hr')"
    style="display: none;"
    action="editor().chain().focus().setHorizontalRule().run()"
    active="'horizontalRule'"
    label="{{ __('filament-tiptap-editor::editor.hr') }}"
    icon="hr"
/>