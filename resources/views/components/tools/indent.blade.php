<x-filament-tiptap-editor::button
        action="editor().chain().focus().undoIndent().run()"
        active="customIndent"
        label="{{ trans('filament-tiptap-editor::editor.outdent') }}"
        icon="outdent"
/>
<x-filament-tiptap-editor::button
    action="editor().chain().focus().doIndent().run()"
    active="customIndent"
    label="{{ trans('filament-tiptap-editor::editor.indent') }}"
    icon="indent"
/>