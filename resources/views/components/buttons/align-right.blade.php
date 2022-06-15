<div x-show="buttons.includes('align')"
    style="display: none;">
    <x-filament-tiptap-editor::button action="editor().chain().focus().setTextAlign('right').run()"
        active="{ textAlign: 'right' }"
        label="{{ __('filament-tiptap-editor::editor.align_right') }}">
        <x-filament-tiptap-editor::icon icon="align-right" />
    </x-filament-tiptap-editor::button>
</div>
