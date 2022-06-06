<div x-show="buttons.includes('underline')"
    style="display: none;">
    <x-filament-tiptap-editor::button action="editor().chain().focus().toggleUnderline().run()"
        active="'underline'"
        label="Underline">
        <x-filament-tiptap-editor::icon icon="underline" />
    </x-filament-tiptap-editor::button>
</div>
