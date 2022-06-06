<div x-show="buttons.includes('undo')"
    style="display: none;">
    <x-filament-tiptap-editor::button action="editor().chain().focus().undo().run()"
        active="'undo'"
        label="Undo">
        <x-filament-tiptap-editor::icon icon="undo" />
    </x-filament-tiptap-editor::button>
</div>
