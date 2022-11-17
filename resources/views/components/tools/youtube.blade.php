@props([
    'statePath' => null,
])

<x-filament-tiptap-editor::button
        action="openModal()"
        active="'youtube'"
        label="{{ __('filament-tiptap-editor::editor.video.youtube') }}"
        icon="youtube"
        x-on:insert-video.window="$event.detail.statePath === '{{ $statePath }}' ? insertVideo($event.detail.video) : null"
        x-data="{
            openModal() {
                $wire.dispatchFormEvent('tiptap::setYoutubeContent', '{{ $statePath }}');
            },
            insertVideo(video) {
                if (video.url === null) {
                    return;
                }

                console.log(video);

                this.editor()
                .chain()
                .focus()
                .setYoutubeVideo({
                    src: video.url,
                    width: video.width ?? 640,
                    height: video.height ?? 480,
                    responsive: video.responsive ?? true,
                })
                .run();
            }
        }"
/>