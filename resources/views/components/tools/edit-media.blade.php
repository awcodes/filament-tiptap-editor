@props([
    'statePath' => null,
    'icon' => 'media',
])

@php
    $action = "\$wire.dispatchFormEvent('tiptap::editMediaContent', '" . $statePath . "', arguments);";
@endphp

<x-filament-tiptap-editor::button
    action="openModal()"
    label="{{ trans('filament-tiptap-editor::editor.media.edit') }}"
    active="image"
    :icon="$icon"
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