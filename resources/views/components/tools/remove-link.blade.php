<x-filament-tiptap-editor::button
    action="editor().chain().focus().extendMarkRange('link').unsetLink().selectTextblockEnd().run()"
    icon="link-remove"
    label="{{ trans('filament-tiptap-editor::editor.link.remove') }}"
/>