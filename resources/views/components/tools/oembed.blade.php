@props([
    'statePath' => null,
])

<x-filament-tiptap-editor::button
        action="openModal()"
        active="oembed"
        label="{{ __('filament-tiptap-editor::editor.video.oembed') }}"
        icon="oembed"
        x-on:insert-video.window="$event.detail.statePath === '{{ $statePath }}' ? insertVideo($event.detail.video) : null"
        x-data="{
            openModal() {
                $wire.dispatchFormEvent('tiptap::setOEmbedContent', '{{ $statePath }}');
            },
            insertVideo(video) {
                if (! video || video.url === null) {
                    return;
                }

                if (video.embed_type === 'youtube') {
                    this.editor().chain().focus().setYoutubeVideo({
                        src: video.url,
                        width: video.width ?? 640,
                        height: video.height ?? 480,
                        responsive: video.responsive ?? true,
                    }).run();
                } else {
                    this.editor().chain().focus().setVimeoVideo({
                        src: video.url,
                        width: video.width ?? 640,
                        height: video.height ?? 480,
                        autoplay: video.autoplay ? 1 : 0,
                        loop: video.loop ? 1 : 0,
                        title: video.show_title ? 1 : 0,
                        byline: video.byline ? 1 : 0,
                        portrait: video.portrait ? 1 : 0,
                        responsive: video.responsive ?? true,
                    }).run();
                }
            }
        }"
/>