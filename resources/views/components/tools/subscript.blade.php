<div x-show="tools.includes('subscript')"
    style="display: none;">
    <x-filament-tiptap-editor::button action="editor().chain().focus().toggleSubscript().run()"
        active="'subscript'"
        label="{{ __('filament-tiptap-editor::editor.subscript') }}">
        <x-filament-tiptap-editor::icon icon="subscript" />
    </x-filament-tiptap-editor::button>
</div>
