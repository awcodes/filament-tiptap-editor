<div x-show="buttons.includes('redo')"
    style="display: none;">
    <x-filament-tiptap-editor::button action="editor().chain().focus().redo().run()"
        active="'redo'"
        label="Redo">
        <x-filament-tiptap-editor::icon icon="redo" />
    </x-filament-tiptap-editor::button>
</div>
