<div x-show="tools.includes('align')"
    style="display: none;">
    <x-filament-tiptap-editor::button action="editor().chain().focus().setTextAlign('justify').run()"
        active="{ textAlign: 'justify' }"
        label="{{ __('filament-tiptap-editor::editor.align_justify') }}">
        <x-filament-tiptap-editor::icon icon="align-justify" />
    </x-filament-tiptap-editor::button>
</div>
