<x-filament-tiptap-editor::button
    x-show="tools.includes('align')"
    style="display: none;"
    action="editor().chain().focus().setTextAlign('right').run()"
    active="{ textAlign: 'right' }"
    label="{{ __('filament-tiptap-editor::editor.align_right') }}"
    icon="align-right"
/>
