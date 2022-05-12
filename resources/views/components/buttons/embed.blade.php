@props([
    'fieldId' => null,
])
<button type="button"
    class="p-2"
    x-on:click="openModal()"
    x-on:insert-embed.window="$event.detail.fieldId === '{{ $fieldId }}' ? insertEmbed($event.detail.url) : null"
    x-tooltip="'Embed'"
    x-data="{
        openModal() {
                $dispatch('open-modal', {
                    id: 'filament-tiptap-editor-embed-modal',
                    fieldId: '{{ $fieldId }}',
                });
            },
            insertEmbed(url) {
                console.log(url);
                this.editor().chain().focus().setIframe({ src: url }).run();
            },
    }">
    <svg xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        aria-hidden="true"
        role="img"
        class="w-5 h-5 iconify iconify--ic"
        width="24"
        height="24"
        preserveAspectRatio="xMidYMid meet"
        viewBox="0 0 36 36">
        <path fill="currentColor"
            d="M30 4H6a2 2 0 0 0-2 2v24a2 2 0 0 0 2 2h24a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2Zm0 26H6V6h24Z"
            class="clr-i-outline clr-i-outline-path-1"></path>
        <path fill="currentColor"
            d="M14.6 23.07a1.29 1.29 0 0 0 1.24.09l8.73-4a1.3 1.3 0 0 0 0-2.37l-8.73-4A1.3 1.3 0 0 0 14 14v8a1.29 1.29 0 0 0 .6 1.07Zm1-8.6L23.31 18l-7.71 3.51Z"
            class="clr-i-outline clr-i-outline-path-2"></path>
        <path fill="currentColor"
            d="M8 7h2v3H8z"
            class="clr-i-outline clr-i-outline-path-3"></path>
        <path fill="currentColor"
            d="M14 7h2v3h-2z"
            class="clr-i-outline clr-i-outline-path-4"></path>
        <path fill="currentColor"
            d="M20 7h2v3h-2z"
            class="clr-i-outline clr-i-outline-path-5"></path>
        <path fill="currentColor"
            d="M26 7h2v3h-2z"
            class="clr-i-outline clr-i-outline-path-6"></path>
        <path fill="currentColor"
            d="M8 26h2v3H8z"
            class="clr-i-outline clr-i-outline-path-7"></path>
        <path fill="currentColor"
            d="M14 26h2v3h-2z"
            class="clr-i-outline clr-i-outline-path-8"></path>
        <path fill="currentColor"
            d="M20 26h2v3h-2z"
            class="clr-i-outline clr-i-outline-path-9"></path>
        <path fill="currentColor"
            d="M26 26h2v3h-2z"
            class="clr-i-outline clr-i-outline-path-10"></path>
        <path fill="none"
            d="M0 0h36v36H0z"></path>
    </svg>
    <span class="sr-only">Embed</span>
</button>
