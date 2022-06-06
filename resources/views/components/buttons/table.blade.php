<div x-show="buttons.includes('table')"
    style="display: none;">
    <x-filament-tiptap-editor::dropdown-button label="Table"
        active="'table'"
        icon="table">
        <x-filament-tiptap-editor::dropdown-button-item
            action="editor().chain().focus().insertTable({ rows: 3, cols: 3, withHeaderRow: true }).run()">
            Insert Table
        </x-filament-tiptap-editor::dropdown-button-item>
        <x-filament-tiptap-editor::dropdown-button-item action="editor().chain().focus().addColumnBefore().run()">
            Add Column Before
        </x-filament-tiptap-editor::dropdown-button-item>
        <x-filament-tiptap-editor::dropdown-button-item action="editor().chain().focus().addColumnAfter().run()">
            Add Column After
        </x-filament-tiptap-editor::dropdown-button-item>
        <x-filament-tiptap-editor::dropdown-button-item action="editor().chain().focus().deleteColumn().run()">
            Delete Column
        </x-filament-tiptap-editor::dropdown-button-item>
        <x-filament-tiptap-editor::dropdown-button-item action="editor().chain().focus().addRowBefore().run()">
            Add Row Before
        </x-filament-tiptap-editor::dropdown-button-item>
        <x-filament-tiptap-editor::dropdown-button-item action="editor().chain().focus().addRowAfter().run()">
            Add Row After
        </x-filament-tiptap-editor::dropdown-button-item>
        <x-filament-tiptap-editor::dropdown-button-item action="editor().chain().focus().deleteRow().run()">
            Delete Row
        </x-filament-tiptap-editor::dropdown-button-item>
        <x-filament-tiptap-editor::dropdown-button-item action="editor().chain().focus().deleteTable().run()">
            Delete Table
        </x-filament-tiptap-editor::dropdown-button-item>
        <x-filament-tiptap-editor::dropdown-button-item action="editor().chain().focus().mergeCells().run()">
            Merge Cells
        </x-filament-tiptap-editor::dropdown-button-item>
        <x-filament-tiptap-editor::dropdown-button-item action="editor().chain().focus().splitCell().run()">
            Split Cell
        </x-filament-tiptap-editor::dropdown-button-item>
        <x-filament-tiptap-editor::dropdown-button-item action="editor().chain().focus().toggleHeaderColumn().run()">
            Toggle Header Column
        </x-filament-tiptap-editor::dropdown-button-item>
        <x-filament-tiptap-editor::dropdown-button-item action="editor().chain().focus().toggleHeaderRow().run()">
            Toggle Header Row
        </x-filament-tiptap-editor::dropdown-button-item>
        <x-filament-tiptap-editor::dropdown-button-item action="editor().chain().focus().toggleHeaderCell().run()">
            Toggle Header Cell
        </x-filament-tiptap-editor::dropdown-button-item>
        <x-filament-tiptap-editor::dropdown-button-item action="editor().chain().focus().mergeOrSplit().run()">
            Merge Or Split
        </x-filament-tiptap-editor::dropdown-button-item>
        <x-filament-tiptap-editor::dropdown-button-item action="editor().chain().focus().fixTables().run()">
            Fix Tables
        </x-filament-tiptap-editor::dropdown-button-item>
        <x-filament-tiptap-editor::dropdown-button-item action="editor().chain().focus().goToNextCell().run()">
            Go To Next Cell
        </x-filament-tiptap-editor::dropdown-button-item>
        <x-filament-tiptap-editor::dropdown-button-item action="editor().chain().focus().goToPreviousCell().run()">
            Go To Previous Cell
        </x-filament-tiptap-editor::dropdown-button-item>
    </x-filament-tiptap-editor::dropdown-button>
</div>
