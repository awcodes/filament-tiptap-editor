<div x-show="buttons.includes('hr')"
    style="display: none;">
    <x-filament-tiptap-editor::button action="editor().chain().focus().setHorizontalRule().run()"
        active="'horizontalRule'"
        label="{{ __('filament-tiptap-editor::editor.hr') }}">
        <x-filament-tiptap-editor::icon icon="hr" />
    </x-filament-tiptap-editor::button>
</div>
