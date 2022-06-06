<div x-show="buttons.includes('italic')"
    style="display: none;">
    <x-filament-tiptap-editor::button action="editor().chain().focus().toggleItalic().run()"
        active="'italic'"
        label="Italic">
        <x-filament-tiptap-editor::icon icon="italic" />
    </x-filament-tiptap-editor::button>
</div>
