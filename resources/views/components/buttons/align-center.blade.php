<button type="button"
    x-on:click="editor().chain().focus().setTextAlign('center').run()"
    :class="{ 'bg-gray-300 text-gray-900 dark:bg-gray-600 dark:text-gray-300': isActive({ textAlign: 'center' }, updatedAt) }"
    @class([
        'rounded block p-1 hover:bg-gray-200 focus:bg-gray-200',
        'dark:hover:bg-gray-800 dark:focus:bg-gray-800' => config(
            'filament.dark_mode'
        ),
    ])
    x-tooltip="'Align Text Center'">
    <svg xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        aria-hidden="true"
        role="img"
        class="w-5 h-5 iconify iconify--ic"
        width="24"
        height="24"
        preserveAspectRatio="xMidYMid meet"
        viewBox="0 0 24 24">
        <path fill="currentColor"
            d="M7 16c0 .55.45 1 1 1h8c.55 0 1-.45 1-1s-.45-1-1-1H8c-.55 0-1 .45-1 1zm-3 5h16c.55 0 1-.45 1-1s-.45-1-1-1H4c-.55 0-1 .45-1 1s.45 1 1 1zm0-8h16c.55 0 1-.45 1-1s-.45-1-1-1H4c-.55 0-1 .45-1 1s.45 1 1 1zm3-5c0 .55.45 1 1 1h8c.55 0 1-.45 1-1s-.45-1-1-1H8c-.55 0-1 .45-1 1zM3 4c0 .55.45 1 1 1h16c.55 0 1-.45 1-1s-.45-1-1-1H4c-.55 0-1 .45-1 1z">
        </path>
    </svg>
    <span class="sr-only">Align Text Center</span>
</button>
