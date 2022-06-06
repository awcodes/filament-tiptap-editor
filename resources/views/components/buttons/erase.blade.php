<div x-show="buttons.includes('erase')"
    style="display: none;">
    <x-filament-tiptap-editor::button action="editor().commands.clearContent(true)"
        label="Erase All Content">
        <x-filament-tiptap-editor::icon icon="erase" />
    </x-filament-tiptap-editor::button>
</div>
