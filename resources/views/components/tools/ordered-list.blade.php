<x-filament-tiptap-editor::button
    action="editor().chain().focus().toggleOrderedList().run()"
    active="orderedlist"
    label="{{ trans('filament-tiptap-editor::editor.list.ordered') }}"
    icon="orderedlist"
/>