@props([
    'fieldId' => null,
])

<x-filament-tiptap-editor::button
    x-show="tools.includes('source')"
    style="display: none;"
    action="openModal()"
    label="{{ __('filament-tiptap-editor::editor.source') }}"
    x-on:insert-source.window="$event.detail.fieldId === '{{ $fieldId }}' ? insertSource($event.detail.source) : null"
    icon="source"
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
    }"
/>