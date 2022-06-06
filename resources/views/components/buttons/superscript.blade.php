<div x-show="buttons.includes('superscript')"
    style="display: none;">
    <x-filament-tiptap-editor::button action="editor().chain().focus().toggleSuperscript().run()"
        active="'superscript'"
        label="Superscript">
        <x-filament-tiptap-editor::icon icon="superscript" />
    </x-filament-tiptap-editor::button>
</div>
