<div x-show="tools.includes('superscript')"
    style="display: none;">
    <x-filament-tiptap-editor::button action="editor().chain().focus().toggleSuperscript().run()"
        active="'superscript'"
        label="{{ __('filament-tiptap-editor::editor.superscript') }}">
        <x-filament-tiptap-editor::icon icon="superscript" />
    </x-filament-tiptap-editor::button>
</div>
