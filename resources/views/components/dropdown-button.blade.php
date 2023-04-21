@props([
    'active' => null,
    'label' => null,
    'icon' => null,
    'indicator' => null,
])
<div
    x-data="{
        indicator: () => {{ $indicator ?? 'null' }},
    }"
    class="relative"
>
    @if ($indicator)
        <div
            x-text="{{ $indicator }}"
            class="text-[0.625rem] absolute top-0 right-0 font-mono text-gray-800 dark:text-gray-300 pointer-events-none"
            x-bind:class="{ 'hidden': !indicator() }"
        ></div>
    @endif

    <x-filament-tiptap-editor::button
        action="$refs.panel.toggle"
        :active="$active"
        :label="$label"
        :icon="$icon"
    />

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
