<div x-show="buttons.includes('align')"
    style="display: none;">
    <x-filament-tiptap-editor::button action="editor().chain().focus().setTextAlign('justify').run()"
        active="{ textAlign: 'justify' }"
        label="Justify Text">
        <x-filament-tiptap-editor::icon icon="align-justify" />
    </x-filament-tiptap-editor::button>
</div>
