<div x-show="buttons.includes('bulletList')"
    style="display: none;">
    <x-filament-tiptap-editor::button action="editor().chain().focus().toggleBulletList().run()"
        active="'bulletlist'"
        label="Unordered List">
        <x-filament-tiptap-editor::icon icon="bulletlist" />
    </x-filament-tiptap-editor::button>
</div>

<div x-show="buttons.includes('orderedList')"
    style="display: none;">
    <x-filament-tiptap-editor::button action="editor().chain().focus().toggleOrderedList().run()"
        active="'orderedlist'"
        label="Ordered List">
        <x-filament-tiptap-editor::icon icon="orderedlist" />
    </x-filament-tiptap-editor::button>
</div>

<div x-show="buttons.includes('checkedList')"
    style="display: none;">
    <x-filament-tiptap-editor::button action="editor().chain().focus().toggleCheckedList().run()"
        active="'checkedlist'"
        label="Checked List">
        <x-filament-tiptap-editor::icon icon="checkedlist" />
    </x-filament-tiptap-editor::button>
</div>
