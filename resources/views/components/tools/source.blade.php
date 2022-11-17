@props([
    'statePath' => null,
])

<x-filament-tiptap-editor::button
    action="openModal()"
    label="{{ __('filament-tiptap-editor::editor.source') }}"
    x-on:insert-source.window="$event.detail.statePath === '{{ $statePath }}' ? insertSource($event.detail.source) : null"
    icon="source"
    x-data="{
        openModal() {
            $wire.dispatchFormEvent('tiptap::setSourceContent', '{{ $statePath }}', this.editor().getHTML());
        },
        insertSource(source) {
            this.editor().commands.setContent(source, {emitUpdate: true});
        }
    }"
/>
