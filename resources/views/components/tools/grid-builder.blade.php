@props([
    'statePath' => null,
])

<x-filament-tiptap-editor::button
    action="$wire.dispatchFormEvent('tiptap::setGridContent', '{{ $statePath }}')"
    active="grid-builder"
    label="{{ __('filament-tiptap-editor::editor.grid-builder.label') }}"
    icon="grid-builder"
/>
