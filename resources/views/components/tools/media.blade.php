@props([
    'statePath' => null,
])

@php
    if (str(config('filament-tiptap-editor.media_action'))->contains('\\')) {
        $action = "\$wire.dispatchFormEvent('tiptap::setMediaContent', '" . $statePath . "', mediaProps);";
    } else {
        $action = "this.\$dispatch('open-modal', {id: '" . config('filament-tiptap-editor.media_action') . "', statePath: '" . $statePath . "'}, mediaProps)";
    }
@endphp

<x-filament-tiptap-editor::button
    action="openModal()"
    label="{{ __('filament-tiptap-editor::editor.media') }}"
    active="image"
    icon="media"
    x-data="{
        openModal() {
            let media = this.editor().getAttributes('image');
            let mediaProps = {
                src: media.src || '',
                alt: media.alt || '',
                title: media.title || '',
                width: media.width || '',
                height: media.height || '',
            };

            {{ $action }}
        }
    }"
/>