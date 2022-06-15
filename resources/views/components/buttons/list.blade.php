<div x-show="buttons.includes('bulletList')"
    style="display: none;">
    <x-filament-tiptap-editor::button action="editor().chain().focus().toggleBulletList().run()"
        active="'bulletlist'"
        label="{{ __('filament-tiptap-editor::editor.list.bulleted') }}">
        <x-filament-tiptap-editor::icon icon="bulletlist" />
    </x-filament-tiptap-editor::button>
</div>

<div x-show="buttons.includes('orderedList')"
    style="display: none;">
    <x-filament-tiptap-editor::button action="editor().chain().focus().toggleOrderedList().run()"
        active="'orderedlist'"
        label="{{ __('filament-tiptap-editor::editor.list.ordered') }}">
        <x-filament-tiptap-editor::icon icon="orderedlist" />
    </x-filament-tiptap-editor::button>
</div>

<div x-show="buttons.includes('checkedList')"
    style="display: none;">
    <x-filament-tiptap-editor::button action="editor().chain().focus().toggleCheckedList().run()"
        active="'checkedlist'"
        label="{{ __('filament-tiptap-editor::editor.list.checked') }}">
        <x-filament-tiptap-editor::icon icon="checkedlist" />
    </x-filament-tiptap-editor::button>
</div>
