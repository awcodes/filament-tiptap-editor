<x-filament-tiptap-editor::button
    x-show="tools.includes('lead')"
    style="display: none;"
    action="editor().chain().focus().toggleLead().run()"
    active="'leadParagraph'"
    label="{{ __('filament-tiptap-editor::editor.lead') }}"
    icon="lead"
/>
