<x-filament-tiptap-editor::button
    x-show="tools.includes('details')"
    style="display: none;"
    action="isActive('details', updatedAt) ? editor().chain().focus().unsetDetails().run() : editor().chain().focus().setDetails().run()"
    active="'details'"
    label="{{ __('filament-tiptap-editor::editor.details') }}"
    icon="details"
/>
