<div x-show="buttons.includes('small')"
    style="display: none;">
    <x-filament-tiptap-editor::button action="editor().chain().focus().toggleSmall().run()"
        active="'small'"
        label="{{ __('filament-tiptap-editor::editor.small') }}">
        <x-filament-tiptap-editor::icon icon="small" />
    </x-filament-tiptap-editor::button>
</div>
