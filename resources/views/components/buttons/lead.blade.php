<div x-show="buttons.includes('lead')"
    style="display: none;">
    <x-filament-tiptap-editor::button action="editor().chain().focus().toggleLead().run()"
        active="'leadParagraph'"
        label="{{ __('filament-tiptap-editor::editor.lead') }}">
        <x-filament-tiptap-editor::icon icon="lead" />
    </x-filament-tiptap-editor::button>
</div>
