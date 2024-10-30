@props([
    'statePath' => null,
    'icon' => 'iframe',
    'label' => trans('filament-tiptap-editor::iframe-modal.title'),
])

<x-filament-tiptap-editor::button
    action="openModal()"
    label="{{ __('Iframe') }}"
    icon="iframe"
    x-data="{
        openModal() {
            let iframe = this.editor().getAttributes('iframe');
            let arguments = {
                src: iframe.src || '',
                frameborder: iframe.frameborder || 0,
                scrolling: iframe.scrolling || 'auto',
                allowfullscreen: iframe.allowfullscreen || true,
                allow: iframe.allow || '',
                width: iframe.width || '',
                height: iframe.height || '',
                style: iframe.style || '',
            };

            $wire.dispatchFormEvent('tiptap::setIframeContent', '{{ $statePath }}', arguments);
        }
    }"
/>