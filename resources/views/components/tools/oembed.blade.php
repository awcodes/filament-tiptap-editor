@props([
    'statePath' => null,
])

<x-filament-tiptap-editor::button
    action="$wire.dispatchFormEvent('tiptap::setOEmbedContent', '{{ $statePath }}', {})"
    active="oembed"
    label="{{ trans('filament-tiptap-editor::editor.video.oembed') }}"
    icon="oembed"
/>