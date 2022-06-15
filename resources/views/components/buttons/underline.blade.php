<div x-show="buttons.includes('underline')"
    style="display: none;">
    <x-filament-tiptap-editor::button action="editor().chain().focus().toggleUnderline().run()"
        active="'underline'"
        label="{{ __('filament-tiptap-editor::editor.underline') }}">
        <x-filament-tiptap-editor::icon icon="underline" />
    </x-filament-tiptap-editor::button>
</div>
