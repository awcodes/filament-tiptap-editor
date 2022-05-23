<button type="button"
    x-on:click="editor().chain().focus().toggleCode().run()"
    :class="{ 'active': isActive('code', updatedAt) }"
    x-tooltip="'Code'">

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
            d="m16 18l-1.425-1.425l4.6-4.6L14.6 7.4L16 6l6 6Zm-8 0l-6-6l6-6l1.425 1.425l-4.6 4.6L9.4 16.6Z"></path>
    </svg>

    <span class="sr-only">Code</span>
</button>
