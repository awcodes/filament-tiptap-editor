<button type="button"
    x-on:click="editor().chain().focus().toggleLead().run()"
    class="p-2"
    :class="{ 'active': isActive('leadParagraph', updatedAt) }"
    x-tooltip="'Lead Paragraph'">
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
            d="M5.12 14L7.5 7.67L9.87 14M6.5 5L1 19h2.25l1.12-3h6.25l1.13 3H14L8.5 5h-2M18 7l-5 5.07l1.41 1.43L17 10.9V17h2v-6.1l2.59 2.6L23 12.07L18 7Z">
        </path>
    </svg>
    <span class="sr-only">Lead Paragraph</span>
</button>
