<x-filament-tiptap-editor::button
    x-show="tools.includes('underline')"
    style="display: none;"
    action="editor().chain().focus().toggleUnderline().run()"
    active="'underline'"
    label="{{ __('filament-tiptap-editor::editor.underline') }}"
    icon="underline"
/>