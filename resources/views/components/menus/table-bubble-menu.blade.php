@props([
    'statePath' => null,
    'tools' => [],
])

<div x-ref="tableBubbleMenu" class="flex gap-1 items-center" x-cloak>
    <x-filament-tiptap-editor::button
        action="editor().chain().focus().addColumnBefore().run()"
        icon="table-add-column-before"
        label="{{ __('filament-tiptap-editor::editor.table.add_column_before') }}"
    />

    <x-filament-tiptap-editor::button
        action="editor().chain().focus().addColumnAfter().run()"
        icon="table-add-column-after"
        label="{{ __('filament-tiptap-editor::editor.table.add_column_after') }}"
    />

    <x-filament-tiptap-editor::button
        action="editor().chain().focus().deleteColumn().run()"
        icon="table-delete-column"
        label="{{ __('filament-tiptap-editor::editor.table.delete_column') }}"
    />

    <x-filament-tiptap-editor::button
        action="editor().chain().focus().addRowBefore().run()"
        icon="table-add-row-before"
        label="{{ __('filament-tiptap-editor::editor.table.add_row_before') }}"
    />

    <x-filament-tiptap-editor::button
        action="editor().chain().focus().addRowAfter().run()"
        icon="table-add-row-after"
        label="{{ __('filament-tiptap-editor::editor.table.add_row_after') }}"
    />

    <x-filament-tiptap-editor::button
        action="editor().chain().focus().deleteRow().run()"
        icon="table-delete-row"
        label="{{ __('filament-tiptap-editor::editor.table.delete_row') }}"
    />

    <x-filament-tiptap-editor::button
        action="editor().chain().focus().mergeCells().run()"
        icon="table-merge-cells"
        label="{{ __('filament-tiptap-editor::editor.table.merge_cells') }}"
    />

    <x-filament-tiptap-editor::button
        action="editor().chain().focus().splitCell().run()"
        icon="table-split-cells"
        label="{{ __('filament-tiptap-editor::editor.table.split_cell') }}"
    />

    <x-filament-tiptap-editor::button
        action="editor().chain().focus().deleteTable().run()"
        icon="table-delete"
        label="{{ __('filament-tiptap-editor::editor.table.delete_table') }}"
    />
</div>


