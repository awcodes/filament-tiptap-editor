<x-filament-tiptap-editor::button
    x-show="tools.includes('color') && editor().isActive('textStyle', updatedAt)"
    style="display: none;"
    action="editor().chain().focus().unsetColor().run()"
    label="{{ trans('filament-tiptap-editor::editor.remove_color') }}"
    icon="remove-color"
/>