<x-filament-tiptap-editor::button
    action="editor().chain().focus().toggleCodeBlock().run()"
    active="codeBlock"
    label="{{ trans('filament-tiptap-editor::editor.code_block') }}"
    icon="code-block"
/>
