@props([
    'action' => null,
    'active' => null,
    'icon' => null,
    'label' => null,
])
<button type="button"
    x-on:click="{{ $action }}"
    :class="{ 'bg-gray-300 text-gray-900 dark:bg-gray-600 dark:text-gray-300': isActive({{ $active }}, updatedAt) }"
    @class([
        'rounded block p-1 hover:bg-gray-200 focus:bg-gray-200',
        'dark:hover:bg-gray-800 dark:focus:bg-gray-800' => config(
            'filament.dark_mode'
        ),
    ])
    x-tooltip="'{{ $label }}'">
    <svg xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        aria-hidden="true"
        role="img"
        class="w-5 h-5 iconify iconify--ic"
        width="24"
        height="24"
        preserveAspectRatio="xMidYMid meet"
        viewBox="0 0 24 24">
        {{ $icon }}
    </svg>
    <span class="sr-only">{{ $label }}</span>
</button>
