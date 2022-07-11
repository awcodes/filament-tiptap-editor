<x-filament-tiptap-editor::button
    x-show="tools.includes('align')"
    style="display: none;"
    action="editor().chain().focus().unsetTextAlign().run()"
    active="'textAlign'"
    label="{{ __('filament-tiptap-editor::editor.align_left') }}"
    icon="align-left"
/>