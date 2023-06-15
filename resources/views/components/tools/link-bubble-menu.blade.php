<div x-ref="linkBubbleMenu" class="flex gap-1 items-center" x-cloak>

    <x-filament-tiptap-editor::button
        action="document.querySelector('[x-ref=linkBubbleMenu]').closest('.tiptap-editor').querySelector('[data-link-button]').click();"
        icon="edit"
        label="{{ __('filament-tiptap-editor::editor.link.edit') }}"
    />

    <x-filament-tiptap-editor::button
        action="editor().chain().focus().extendMarkRange('link').unsetLink().selectTextblockEnd().run()"
        icon="link-remove"
        label="{{ __('filament-tiptap-editor::editor.link.remove') }}"
    />
</div>


