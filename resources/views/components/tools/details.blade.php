<x-filament-tiptap-editor::button
    action="editor().isActive('details', updatedAt) ? editor().chain().focus().unsetDetails().run() : editor().chain().focus().setDetails().run()"
    active="details"
    label="{{ trans('filament-tiptap-editor::editor.details') }}"
    icon="details"
/>
