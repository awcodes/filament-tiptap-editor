@props([
    'active' => null,
    'label' => null,
    'icon' => null,
    'indicator' => null,
    'list' => true,
    'scrollable' => false,
])
<div
    x-data="{
        indicator: () => {{ $indicator ?? 'null' }}
    }"
    class="relative"
    x-on:close-panel="$refs.panel.close()"
>
    @if ($indicator)
        <div
            x-text="{{ $indicator }}"
            class="text-[0.625rem] absolute top-0 right-0 font-mono text-gray-800 dark:text-gray-300 pointer-events-none"
            x-bind:class="{ 'hidden': ! indicator() }"
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
        x-float.placement.bottom-middle.flip.offset.arrow="{
            arrow: {
              element: $refs.arrow
            }
        }"
        x-cloak
        @class([
            'absolute z-30 bg-gray-800 rounded-md shadow-md top-full',
            'overflow-y-scroll max-h-48' => ! $active,
        ])
    >
        <div x-ref="arrow" class="absolute z-0 bg-inherit w-2 h-2 transform rotate-45"></div>
        @if ($list)
            <ul class="text-sm divide-y divide-gray-700 min-w-[144px]">
                {{ $slot }}
            </ul>
        @else
            <div class="flex gap-1 items-center p-1">
                {{ $slot }}
            </div>
        @endif
    </div>
</div>
