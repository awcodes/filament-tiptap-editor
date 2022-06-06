<div x-show="buttons.includes('align')"
    style="display: none;">
    <x-filament-tiptap-editor::button action="editor().chain().focus().unsetTextAlign().run()"
        active="'textAlign'"
        label="Align Text Left">
        <x-filament-tiptap-editor::icon icon="align-left" />
    </x-filament-tiptap-editor::button>
</div>
