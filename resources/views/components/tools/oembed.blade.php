@props([
    'statePath' => null,
])

<x-filament-tiptap-editor::button
        action="openModal()"
        active="oembed"
        label="{{ __('filament-tiptap-editor::editor.video.oembed') }}"
        icon="oembed"
        x-data="{
            openModal() {
                $wire.dispatchFormEvent('tiptap::setOEmbedContent', '{{ $statePath }}');
            }
        }"
/>