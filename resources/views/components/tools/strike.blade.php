<x-filament-tiptap-editor::button
    x-show="tools.includes('strike')"
    style="display: none;"
    action="editor().chain().focus().toggleStrike().run()"
    active="'strike'"
    label="{{ __('filament-tiptap-editor::editor.strike') }}"
    icon="strike"
/>
