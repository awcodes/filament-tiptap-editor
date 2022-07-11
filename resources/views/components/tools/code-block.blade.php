<x-filament-tiptap-editor::button
    x-show="tools.includes('codeblock')"
    style="display: none;"
    action="editor().chain().focus().toggleCodeBlock().run()"
    active="'codeblock'"
    label="{{ __('filament-tiptap-editor::editor.code_block') }}"
    icon="code-block"
/>
