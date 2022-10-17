@props([
    'fieldId' => null,
])

<x-filament-tiptap-editor::button
    action="openModal()"
    active="'link'"
    x-on:insert-link.window="$event.detail.fieldId === '{{ $fieldId }}' ? insertLink($event.detail.link) : null"
    x-on:remove-link.window="$event.detail.fieldId === '{{ $fieldId }}' ? unsetLink($event.detail.link) : null"
    label="{{ __('filament-tiptap-editor::editor.link') }}"
    icon="link"
    x-data="{
        unsetLink() {
            this.editor().chain().focus().extendMarkRange('link').unsetLink().run();
        },
        openModal() {
            let href = this.editor().getAttributes('link').href;
            let target = this.editor().getAttributes('link').target || null;
            let hreflang = this.editor().getAttributes('link').hreflang || null;
            let rel = this.editor().getAttributes('link').rel || null;
            let as_button = this.editor().getAttributes('link').as_button || null;
            let button_theme = this.editor().getAttributes('link').button_theme || '';

            $dispatch('open-modal', {
                id: '{{ config('filament-tiptap-editor.link_modal_id') }}',
                fieldId: '{{ $fieldId }}',
                href: href,
                hreflang: hreflang,
                target: target,
                rel: rel,
                as_button: as_button,
                button_theme: button_theme,
            });
        },
        insertLink(link) {
            if (link.href === null) {
                return;
            }

            if (link.href === '') {
                this.editor().chain().focus().extendMarkRange('link').unsetLink().run();
                return;
            }

            this.editor()
                .chain()
                .focus()
                .extendMarkRange('link')
                .setLink({
                    href: link.href,
                    target: link.target ?? null,
                    hreflang: link.hreflang ?? null,
                    rel: link.rel.length ? link.rel.join(' ') : null,
                    as_button: link.as_button ? true : false,
                    button_theme: link.button_theme ?? '',
                    class: link.as_button ? `btn btn-${link.button_theme}` : null
                })
                .run();
        }
    }"
/>
