<div x-show="tools.includes('align')"
    style="display: none;">
    <x-filament-tiptap-editor::button action="editor().chain().focus().unsetTextAlign().run()"
        active="'textAlign'"
        label="{{ __('filament-tiptap-editor::editor.align_left') }}">
        <x-filament-tiptap-editor::icon icon="align-left" />
    </x-filament-tiptap-editor::button>
</div>
