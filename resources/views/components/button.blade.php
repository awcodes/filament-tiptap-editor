@props([
    'action' => null,
    'active' => null,
    'label' => null,
    'icon' => null,
    'secondary' => false,
])
<button
    type="button"
    x-on:click="{{ $action }}"
    x-tooltip="'{{ $label }}'"
    {{ $attributes }}
    @class([
        'tiptap-tool rounded block p-1',
        'hover:bg-gray-200 focus:bg-gray-200 dark:hover:bg-gray-800 dark:focus:bg-gray-800' => ! $secondary,
        'hover:bg-gray-100 focus:bg-gray-100 dark:hover:bg-gray-700 dark:focus:bg-gray-700' => $secondary,
    ])
    @if ($active)
    x-bind:class="{ 'bg-gray-300 text-gray-800 dark:bg-gray-600 dark:text-gray-300': editor().isActive({{ $active }}, updatedAt) && focused }"
    @endif
>
    {{ $slot }}
    <span class="sr-only">{{ $label }}</span>
    <x-filament-tiptap-editor::icon icon="{{ $icon }}" />
</button>
