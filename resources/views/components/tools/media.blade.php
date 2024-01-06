@props([
    'statePath' => null,
])

@php
    if (str(config('filament-tiptap-editor.media_action'))->contains('\\')) {
        $action = "\$wire.dispatchFormEvent('tiptap::setMediaContent', '" . $statePath . "', arguments);";
    } else {
        $action = "this.\$dispatch('open-modal', {id: '" . config('filament-tiptap-editor.media_action') . "', statePath: '" . $statePath . "'}, arguments)";
    }
@endphp

<x-filament-tiptap-editor::button
    action="openModal()"
    label="{{ trans('filament-tiptap-editor::editor.media') }}"
    active="image"
    icon="media"
    x-data="{
        openModal() {
            let media = this.editor().getAttributes('image');
            let arguments = {
                type: 'media',
                src: media.src || '',
                alt: media.alt || '',
                title: media.title || '',
                width: media.width || '',
                height: media.height || '',
                lazy: media.lazy || false,
            };

            {{ $action }}
        }
    }"
/>