@props([
    'fieldId' => null,
])

<div x-show="buttons.includes('source')"
    style="display: none;">
    <x-filament-tiptap-editor::button action="openModal()"
        label="{{ __('filament-tiptap-editor::editor.source') }}"
        x-on:insert-source.window="$event.detail.fieldId === '{{ $fieldId }}' ? insertSource($event.detail.source) : null"
        x-data="{
            openModal() {
                    $dispatch('open-modal', {
                        id: 'filament-tiptap-editor-source-modal',
                        fieldId: '{{ $fieldId }}',
                        source: this.editor().getHTML()
                    });
                },
                insertSource(source) {
                    this.editor().commands.setContent(source);
                }
        }">
        <x-filament-tiptap-editor::icon icon="source" />
    </x-filament-tiptap-editor::button>
</div>
