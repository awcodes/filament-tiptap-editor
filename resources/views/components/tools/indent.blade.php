<x-filament-tiptap-editor::button
        action="editor().commands.outdent()"
        active="indent"
        label="{{ trans('filament-tiptap-editor::editor.outdent') }}"
        icon="outdent"
/>
<x-filament-tiptap-editor::button
    action="editor().commands.indent()"
    active="indent"
    label="{{ trans('filament-tiptap-editor::editor.indent') }}"
    icon="indent"
/>
