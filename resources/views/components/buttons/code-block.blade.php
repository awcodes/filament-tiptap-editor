<div x-show="buttons.includes('codeblock')"
    style="display: none;">
    <x-filament-tiptap-editor::button action="editor().chain().focus().toggleCodeBlock().run()"
        active="'codeblock'"
        label="Code Block">
        <x-filament-tiptap-editor::icon icon="code-block" />
    </x-filament-tiptap-editor::button>
</div>
