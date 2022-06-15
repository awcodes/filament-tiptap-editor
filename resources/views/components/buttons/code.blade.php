<div x-show="buttons.includes('code')"
    style="display: none;">
    <x-filament-tiptap-editor::button action="editor().chain().focus().toggleCode().run()"
        active="'code'"
        label="{{ __('filament-tiptap-editor::editor.code') }}">
        <x-filament-tiptap-editor::icon icon="code" />
    </x-filament-tiptap-editor::button>
</div>
