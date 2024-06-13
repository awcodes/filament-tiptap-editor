@props([
    'source' => null,
    'width' => null,
    'height' => null,
    'alt' => '',
])

<div class="w-full h-64 fi-input-wrp rounded-lg shadow-sm ring-1 bg-white dark:bg-white/5 ring-gray-950/10 dark:ring-white/20 overflow-hidden">
    <img
        src="{{ $source }}"
        alt="{{ $alt }}"
        width="{{ $width }}"
        height="{{ $height }}"
        class="w-full h-full object-cover"
    />
</div>