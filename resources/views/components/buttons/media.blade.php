@props([
    'fieldId' => null,
])
<button type="button"
    x-on:click="$dispatch('open-modal', {id: '{{ config('filament-tiptap-editor.media_uploader_id') }}', fieldId: '{{ $fieldId }}'})"
    x-on:insert-media.window="$event.detail.fieldId === '{{ $fieldId }}' ? insertMedia($event.detail.media) : null"
    class="p-2"
    x-tooltip="'Insert Media'"
    x-data="{
        insertMedia(media) {
            const src = media?.url || media.src;
            const imageTypes = ['jpg', 'jpeg', 'svg', 'png'];
    
            if (imageTypes.includes(src.split('.').pop())) {
                this.editor()
                    .chain()
                    .focus()
                    .setImage({
                        src: src,
                        alt: media?.alt,
                        title: media?.title,
                    })
                    .run();
            } else {
                this.editor().chain().focus().extendMarkRange('link').setLink({ href: src }).insertContent(media?.link_text).run();
            }
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
        viewBox="0 0 24 24">
        <path fill="currentColor"
            d="M19 5v14H5V5h14m0-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-4.86 8.86l-3 3.87L9 13.14L6 17h12l-3.86-5.14z">
        </path>
    </svg>
    <span class="sr-only">Media</span>
</button>
