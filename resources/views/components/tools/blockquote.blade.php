<div x-show="tools.includes('blockquote')"
    style="display: none;">
    <x-filament-tiptap-editor::button action="editor().chain().focus().toggleBlockquote().run()"
        active="'blockquote'"
        label="{{ __('filament-tiptap-editor::editor.blockquote') }}">
        <x-filament-tiptap-editor::icon icon="blockquote" />
    </x-filament-tiptap-editor::button>
</div>
