<x-filament-tiptap-editor::button
    action="editor().chain().focus().toggleBulletList().run()"
    active="bulletlist"
    label="{{ trans('filament-tiptap-editor::editor.list.bulleted') }}"
    icon="bulletlist"
/>
