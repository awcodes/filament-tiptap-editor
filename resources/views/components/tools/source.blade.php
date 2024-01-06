@props([
    'statePath' => null,
])

<x-filament-tiptap-editor::button
    action="openModal()"
    label="{{ trans('filament-tiptap-editor::editor.source') }}"
    icon="source"
    x-data="{
        openModal() {
            $wire.dispatchFormEvent('tiptap::setSourceContent', '{{ $statePath }}', { html: this.editor().getHTML() });
        }
    }"
/>
