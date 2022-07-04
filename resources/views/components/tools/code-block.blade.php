<div x-show="tools.includes('codeblock')"
    style="display: none;">
    <x-filament-tiptap-editor::button action="editor().chain().focus().toggleCodeBlock().run()"
        active="'codeblock'"
        label="{{ __('filament-tiptap-editor::editor.code_block') }}">
        <x-filament-tiptap-editor::icon icon="code-block" />
    </x-filament-tiptap-editor::button>
</div>
