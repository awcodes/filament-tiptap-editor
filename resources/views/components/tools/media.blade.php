@props([
    'fieldId' => null,
])

<x-filament-tiptap-editor::button
    action="$dispatch('open-modal', {id: '{{ config('filament-tiptap-editor.media_uploader_id') }}', fieldId: '{{ $fieldId }}'})"
    x-on:insert-media.window="$event.detail.fieldId === '{{ $fieldId }}' ? insertMedia($event.detail.media) : null"
    label="{{ __('filament-tiptap-editor::editor.media') }}"
    icon="media"
    x-data="{
        insertMedia(media) {
            const src = media?.url || media.src;
            const imageTypes = ['jpg', 'jpeg', 'svg', 'png', 'webp'];

            if (imageTypes.includes(src.split('.').pop())) {
                this.editor()
                    .chain()
                    .focus()
                    .setImage({
                        src: src,
                        alt: media?.alt,
                        title: media?.title,
                        width: media?.width,
                        height: media?.height,
                    })
                    .run();
            } else {
                this.editor().chain().focus().extendMarkRange('link').setLink({ href: src }).insertContent(media?.link_text).run();
            }
        },
    }"
/>