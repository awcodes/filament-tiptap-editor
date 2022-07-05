<div x-show="tools.includes('details')"
    style="display: none;">
    <x-filament-tiptap-editor::button action="editor().chain().focus().insertDetails().run()"
        active="'details'"
        label="{{ __('filament-tiptap-editor::editor.details') }}">
        <x-filament-tiptap-editor::icon icon="details" />
    </x-filament-tiptap-editor::button>
</div>
