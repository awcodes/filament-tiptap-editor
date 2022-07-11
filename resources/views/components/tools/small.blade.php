<x-filament-tiptap-editor::button
    x-show="tools.includes('small')"
    style="display: none;"
    action="editor().chain().focus().toggleSmall().run()"
    active="'small'"
    label="{{ __('filament-tiptap-editor::editor.small') }}"
    icon="small"
/>
