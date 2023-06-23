<x-filament-tiptap-editor::button
    action="editor().chain().focus().extendMarkRange('link').unsetLink().selectTextblockEnd().run()"
    icon="link-remove"
    label="{{ __('filament-tiptap-editor::editor.link.remove') }}"
/>