<x-filament-tiptap-editor::button
    x-show="tools.includes('code')"
    style="display: none;"
    action="editor().chain().focus().toggleCode().run()"
    active="'code'"
    label="{{ __('filament-tiptap-editor::editor.code') }}"
    icon="code"
/>