<div x-show="buttons.includes('subscript')"
    style="display: none;">
    <x-filament-tiptap-editor::button action="editor().chain().focus().toggleSubscript().run()"
        active="'subscript'"
        label="Subscript">
        <x-filament-tiptap-editor::icon icon="subscript" />
    </x-filament-tiptap-editor::button>
</div>
