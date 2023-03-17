@props([
    'statePath' => null,
])

@php
    if (str(config('filament-tiptap-editor.media_action'))->contains('\\')) {
        $action = "\$wire.dispatchFormEvent('tiptap::setMediaContent', '" . $statePath . "', mediaProps);";
    } else {
        $action = "this.\$dispatch('open-modal', {id: '" . config('filament-tiptap-editor.media_action') . "', statePath: '" . $statePath . "'}, mediaProps)";
    }
@endphp

<x-filament-tiptap-editor::button
    action="openModal()"
    x-on:insert-media.window="$event.detail.statePath === '{{ $statePath }}' ? insertMedia($event.detail.media) : null"
    label="{{ __('filament-tiptap-editor::editor.media') }}"
    active="'image'"
    icon="media"
    x-data="{
        openModal() {
            let media = this.editor().getAttributes('image');
            let mediaProps = {
                src: media.src || '',
                alt: media.alt || '',
                title: media.title || '',
                width: media.width || '',
                height: media.height || '',
            };

            {{ $action }}
        },
        insertMedia(media) {
            if (media) {
                const src = media?.url || media?.src;
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
            }
        },
    }"
/>