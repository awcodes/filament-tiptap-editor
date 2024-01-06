<x-filament-tiptap-editor::button
    action="editor().chain().focus().toggleLead().run()"
    active="leadParagraph"
    label="{{ trans('filament-tiptap-editor::editor.lead') }}"
    icon="lead"
/>
