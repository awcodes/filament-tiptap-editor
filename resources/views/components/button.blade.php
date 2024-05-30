@props([
    'action' => null,
    'active' => null,
    'label' => null,
    'icon' => null,
    'secondary' => false,
])

@php
    if ($active && ! (str_starts_with($active, '{') || str_starts_with($active, '['))) {
        $active = "'{$active}'";
    }
@endphp

<button
    type="button"
    x-on:click="{{ $action }}"
    {{ $attributes }}
    @if ($label)
        x-tooltip="'{{ $label }}'"
    @endif
    @class([
        'tiptap-tool rounded block p-0.5 outline-none ring-1 ring-transparent hover:ring-primary-500 focus:ring-primary-500',
        'hover:bg-gray-500/20 focus:bg-gray-500/20' => ! $secondary,
        'hover:bg-gray-500/40 focus:bg-gray-500/40' => $secondary,
    ])
    @if ($active)
        x-bind:class="{ '!bg-gray-500/30': editor().isActive({{ $active }}, updatedAt) }"
    @endif
>
    {{ $slot }}
    @if ($icon)
        <span class="sr-only">{{ $label }}</span>
        <x-filament-tiptap-editor::icon icon="{{ $icon }}" />
    @endif
</button>
