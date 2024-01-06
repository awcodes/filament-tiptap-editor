<x-filament-tiptap-editor::button
    action="editor().chain().focus().toggleCheckedList().run()"
    active="checkedlist"
    label="{{ trans('filament-tiptap-editor::editor.list.checked') }}"
    icon="checkedlist"
/>