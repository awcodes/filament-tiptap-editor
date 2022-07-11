<x-filament-tiptap-editor::button
    x-show="tools.includes('bulletList')"
    style="display: none;"
    action="editor().chain().focus().toggleBulletList().run()"
    active="'bulletlist'"
    label="{{ __('filament-tiptap-editor::editor.list.bulleted') }}"
    icon="bulletlist"
/>

<x-filament-tiptap-editor::button
    x-show="tools.includes('orderedList')" style="display: none;"
    action="editor().chain().focus().toggleOrderedList().run()"
    active="'orderedlist'"
    label="{{ __('filament-tiptap-editor::editor.list.ordered') }}"
    icon="orderedlist"
/>

<x-filament-tiptap-editor::button
    x-show="tools.includes('checkedList')" style="display: none;"
    action="editor().chain().focus().toggleCheckedList().run()"
    active="'checkedlist'"
    label="{{ __('filament-tiptap-editor::editor.list.checked') }}"
    icon="checkedlist"
/>
