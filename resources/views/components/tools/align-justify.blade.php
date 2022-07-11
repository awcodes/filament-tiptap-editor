<x-filament-tiptap-editor::button
    x-show="tools.includes('align')"
    style="display: none;"
    action="editor().chain().focus().setTextAlign('justify').run()"
    active="{ textAlign: 'justify' }"
    label="{{ __('filament-tiptap-editor::editor.align_justify') }}"
    icon="align-justify"
/>
