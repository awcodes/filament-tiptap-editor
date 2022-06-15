<div x-show="buttons.includes('color') && editor().isActive('textStyle', updatedAt)"
    style="display: none;">
    <x-filament-tiptap-editor::button action="editor().chain().focus().unsetColor().run()"
        label="{{ __('filament-tiptap-editor::editor.remove_color') }}">
        <x-filament-tiptap-editor::icon icon="remove-color" />
    </x-filament-tiptap-editor::button>
</div>
