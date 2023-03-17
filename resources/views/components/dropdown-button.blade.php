@props([
    'active' => null,
    'label' => null,
    'icon' => null,
])
<div
    x-data
    class="relative"
>

    <button
        type="button"
        x-on:click="$refs.panel.toggle"
        class="tiptap-tool rounded block p-1 hover:bg-gray-200 focus:bg-gray-200 dark:hover:bg-gray-800 dark:focus:bg-gray-800"
        @if ($active) :class="{ 'bg-gray-300 text-gray-900 dark:bg-gray-600 dark:text-gray-300': editor().isActive({{ $active }}, updatedAt) && focused }" @endif
        x-tooltip="'{{ $label }}'"
        {{ $attributes }}
    >
        <x-filament-tiptap-editor::icon icon="{{ $icon }}" title="{{ $label }}" />
    </button>

    <div
        x-ref="panel"
        x-float.placement.bottom-start.flip
        x-cloak
        class="absolute z-30 overflow-y-scroll text-white bg-gray-800 rounded-md shadow-md max-h-48 top-full"
    >
        <ul class="text-sm divide-y divide-gray-700 min-w-[144px]">
            {{ $slot }}
        </ul>
    </div>
</div>
