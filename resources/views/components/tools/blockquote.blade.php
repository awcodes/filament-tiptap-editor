<x-filament-tiptap-editor::button
    x-show="tools.includes('blockquote')"
    style="display: none;"
    action="editor().chain().focus().toggleBlockquote().run()"
    active="'blockquote'"
    label="{{ __('filament-tiptap-editor::editor.blockquote') }}"
    icon="blockquote"
/>
