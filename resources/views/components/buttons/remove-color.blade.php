@props([
    'fieldId' => null,
])
<button type="button"
    class="relative"
    x-show="isActive('textStyle', updatedAt)"
    x-on:click="editor().chain().focus().unsetColor().run()"
    x-tooltip="'Remove Color'">
    <svg xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        aria-hidden="true"
        role="img"
        class="w-5 h-5 iconify iconify--ic"
        width="24"
        height="24"
        preserveAspectRatio="xMidYMid meet"
        viewBox="0 0 24 24">
        <g fill="none"
            stroke="currentColor"
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2">
            <path
                d="M7.934 3.97A8.993 8.993 0 0 1 12 3c4.97 0 9 3.582 9 8c0 1.06-.474 2.078-1.318 2.828a4.515 4.515 0 0 1-1.118.726M15 15h-1a2 2 0 0 0-1 3.75A1.3 1.3 0 0 1 12 21A9 9 0 0 1 5.628 5.644">
            </path>
            <circle cx="7.5"
                cy="10.5"
                r="1"></circle>
            <circle cx="12"
                cy="7.5"
                r="1"></circle>
            <circle cx="16.5"
                cy="10.5"
                r="1"></circle>
            <path d="m3 3l18 18"></path>
        </g>
    </svg>
    <span class="sr-only">Remove Color</span>
</button>
