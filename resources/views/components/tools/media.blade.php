@props([
    'statePath' => null,
])

@php
    if (Str::of(config('filament-tiptap-editor.media_action'))->contains('\\')) {
        $action = "\$wire.dispatchFormEvent('tiptap::setMediaContent', '" . $statePath . "');";
    } else {
        $action = "this.\$dispatch('open-modal', {id: '" . config('filament-tiptap-editor.media_action') . "', statePath: '" . $statePath . "'})";
    }
@endphp

<x-filament-tiptap-editor::button
    action="openModal()"
    x-on:insert-media.window="$event.detail.statePath === '{{ $statePath }}' ? insertMedia($event.detail.media) : null"
    label="{{ __('filament-tiptap-editor::editor.media') }}"
    icon="media"
    x-data="{
        openModal() {
            {{ $action }}
        },
        insertMedia(media) {
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
        },
    }"
/>