@props([
    'statePath' => null,
])

<x-filament-tiptap-editor::button
    action="$wire.dispatchFormEvent('tiptap::setGridBuilderContent', '{{ $statePath }}', {})"
    active="grid-builder"
    label="{{ trans('filament-tiptap-editor::editor.grid-builder.label') }}"
    icon="grid-builder"
/>
