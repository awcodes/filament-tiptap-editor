@props([
    'statePath' => null,
])

<x-filament-tiptap-editor::button
    action="openModal()"
    label="{{ __('filament-tiptap-editor::editor.source') }}"
    icon="source"
    x-data="{
        openModal() {
            $wire.dispatchFormEvent('tiptap::setSourceContent', '{{ $statePath }}', this.editor().getHTML());
        }
    }"
/>
