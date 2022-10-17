<x-filament-tiptap-editor::dropdown-button
    label="{{ __('filament-tiptap-editor::editor.table.label') }}"
    active="'table'"
    icon="table"
>
    <x-filament-tiptap-editor::dropdown-button-item
        action="editor().chain().focus().insertTable({ rows: 3, cols: 3, withHeaderRow: true }).run()"
    >
        {{ __('filament-tiptap-editor::editor.table.insert_table') }}
    </x-filament-tiptap-editor::dropdown-button-item>

    <x-filament-tiptap-editor::dropdown-button-item
        action="editor().chain().focus().addColumnBefore().run()"
    >
        {{ __('filament-tiptap-editor::editor.table.add_column_before') }}
    </x-filament-tiptap-editor::dropdown-button-item>

    <x-filament-tiptap-editor::dropdown-button-item
        action="editor().chain().focus().addColumnAfter().run()"
    >
        {{ __('filament-tiptap-editor::editor.table.add_column_after') }}
    </x-filament-tiptap-editor::dropdown-button-item>

    <x-filament-tiptap-editor::dropdown-button-item
        action="editor().chain().focus().deleteColumn().run()"
    >
        {{ __('filament-tiptap-editor::editor.table.delete_column') }}
    </x-filament-tiptap-editor::dropdown-button-item>

    <x-filament-tiptap-editor::dropdown-button-item
        action="editor().chain().focus().addRowBefore().run()"
    >
        {{ __('filament-tiptap-editor::editor.table.add_row_before') }}
    </x-filament-tiptap-editor::dropdown-button-item>

    <x-filament-tiptap-editor::dropdown-button-item
        action="editor().chain().focus().addRowAfter().run()"
    >
        {{ __('filament-tiptap-editor::editor.table.add_row_after') }}
    </x-filament-tiptap-editor::dropdown-button-item>

    <x-filament-tiptap-editor::dropdown-button-item
        action="editor().chain().focus().deleteRow().run()"
    >
        {{ __('filament-tiptap-editor::editor.table.delete_row') }}
    </x-filament-tiptap-editor::dropdown-button-item>

    <x-filament-tiptap-editor::dropdown-button-item
        action="editor().chain().focus().deleteTable().run()"
    >
        {{ __('filament-tiptap-editor::editor.table.delete_table') }}
    </x-filament-tiptap-editor::dropdown-button-item>

    <x-filament-tiptap-editor::dropdown-button-item
        action="editor().chain().focus().mergeCells().run()"
    >
        {{ __('filament-tiptap-editor::editor.table.merge_cells') }}
    </x-filament-tiptap-editor::dropdown-button-item>

    <x-filament-tiptap-editor::dropdown-button-item
        action="editor().chain().focus().splitCell().run()"
    >
        {{ __('filament-tiptap-editor::editor.table.split_cell') }}
    </x-filament-tiptap-editor::dropdown-button-item>

    <x-filament-tiptap-editor::dropdown-button-item
        action="editor().chain().focus().toggleHeaderColumn().run()"
    >
        {{ __('filament-tiptap-editor::editor.table.toggle_header_column') }}
    </x-filament-tiptap-editor::dropdown-button-item>

    <x-filament-tiptap-editor::dropdown-button-item
        action="editor().chain().focus().toggleHeaderRow().run()"
    >
        {{ __('filament-tiptap-editor::editor.table.toggle_header_row') }}
    </x-filament-tiptap-editor::dropdown-button-item>

    <x-filament-tiptap-editor::dropdown-button-item
        action="editor().chain().focus().toggleHeaderCell().run()"
    >
        {{ __('filament-tiptap-editor::editor.table.toggle_header_cell') }}
    </x-filament-tiptap-editor::dropdown-button-item>

    <x-filament-tiptap-editor::dropdown-button-item
        action="editor().chain().focus().mergeOrSplit().run()"
    >
        {{ __('filament-tiptap-editor::editor.table.merge_or_split') }}
    </x-filament-tiptap-editor::dropdown-button-item>

    <x-filament-tiptap-editor::dropdown-button-item
        action="editor().chain().focus().fixTables().run()"
    >
        {{ __('filament-tiptap-editor::editor.table.fix_tables') }}
    </x-filament-tiptap-editor::dropdown-button-item>

    <x-filament-tiptap-editor::dropdown-button-item
        action="editor().chain().focus().goToNextCell().run()"
    >
        {{ __('filament-tiptap-editor::editor.table.go_to_next_cell') }}
    </x-filament-tiptap-editor::dropdown-button-item>

    <x-filament-tiptap-editor::dropdown-button-item
        action="editor().chain().focus().goToPreviousCell().run()"
    >
        {{ __('filament-tiptap-editor::editor.table.go_to_previous_cell') }}
    </x-filament-tiptap-editor::dropdown-button-item>

</x-filament-tiptap-editor::dropdown-button>
