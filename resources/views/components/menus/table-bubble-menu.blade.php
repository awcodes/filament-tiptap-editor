@props([
    'statePath' => null,
    'tools' => [],
])

@if (in_array('table', $tools))
<div
    class="flex gap-1 items-center"
    x-show="editor().isActive('table', updatedAt)"
    style="display: none;"
>
    <x-filament-tiptap-editor::button
        action="editor().chain().focus().addColumnBefore().run()"
        icon="table-add-column-before"
        label="{{ trans('filament-tiptap-editor::editor.table.add_column_before') }}"
    />

    <x-filament-tiptap-editor::button
        action="editor().chain().focus().addColumnAfter().run()"
        icon="table-add-column-after"
        label="{{ trans('filament-tiptap-editor::editor.table.add_column_after') }}"
    />

    <x-filament-tiptap-editor::button
        action="editor().chain().focus().deleteColumn().run()"
        icon="table-delete-column"
        label="{{ trans('filament-tiptap-editor::editor.table.delete_column') }}"
    />

    <x-filament-tiptap-editor::button
        action="editor().chain().focus().addRowBefore().run()"
        icon="table-add-row-before"
        label="{{ trans('filament-tiptap-editor::editor.table.add_row_before') }}"
    />

    <x-filament-tiptap-editor::button
        action="editor().chain().focus().addRowAfter().run()"
        icon="table-add-row-after"
        label="{{ trans('filament-tiptap-editor::editor.table.add_row_after') }}"
    />

    <x-filament-tiptap-editor::button
        action="editor().chain().focus().deleteRow().run()"
        icon="table-delete-row"
        label="{{ trans('filament-tiptap-editor::editor.table.delete_row') }}"
    />

    <x-filament-tiptap-editor::button
        action="editor().chain().focus().mergeCells().run()"
        icon="table-merge-cells"
        label="{{ trans('filament-tiptap-editor::editor.table.merge_cells') }}"
    />

    <x-filament-tiptap-editor::button
        action="editor().chain().focus().splitCell().run()"
        icon="table-split-cells"
        label="{{ trans('filament-tiptap-editor::editor.table.split_cell') }}"
    />

    <x-filament-tiptap-editor::button
        action="editor().chain().focus().deleteTable().run()"
        icon="table-delete"
        label="{{ trans('filament-tiptap-editor::editor.table.delete_table') }}"
    />
</div>
@endif
