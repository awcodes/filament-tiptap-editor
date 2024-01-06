<x-filament-tiptap-editor::button
    action="editor().chain().focus().insertTable({ rows: 3, cols: 3, withHeaderRow: true }).run()"
    icon="table"
    :secondary="true"
    label="{{ trans('filament-tiptap-editor::editor.table.insert_table') }}"
/>
