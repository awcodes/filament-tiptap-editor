<button type="button"
    x-on:click="editor().chain().focus().setHorizontalRule().run()"
    class="p-2"
    :class="{ 'active': isActive('horizontalRule', updatedAt) }"
    x-tooltip="'Horizontal Rule'">
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
            fill-rule="evenodd"
            d="M19 13H5c-.55 0-1-.45-1-1s.45-1 1-1h14c.55 0 1 .45 1 1s-.45 1-1 1z"></path>
    </svg>
    <span class="sr-only">Horizontal Rule</span>
</button>
