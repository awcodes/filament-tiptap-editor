@props([
    'statePath' => null,
])

<x-filament-tiptap-editor::button
    action="openModal()"
    active="'link'"
    label="{{ __('filament-tiptap-editor::editor.link') }}"
    icon="link"
    x-on:insert-link.window="$event.detail.statePath === '{{ $statePath }}' ? insertLink($event.detail) : null"
    x-on:unset-link.window="unsetLink();"
    x-data="{
        openModal() {
            let link = this.editor().getAttributes('link');
            let linkProps = {
                href: link.href || '',
                target: link.target || null,
                hreflang: link.hreflang || null,
                rel: link.rel ? link.rel.split(' ') : null,
                as_button: link.as_button || null,
                button_theme: link.button_theme || '',
            };

            $wire.dispatchFormEvent('tiptap::setLinkContent', '{{ $statePath }}', linkProps);
        },
        unsetLink() {
            this.editor().chain().focus().extendMarkRange('link').unsetLink().selectTextblockEnd().run();
        },
        insertLink(link) {
            if (link.href === null) {
                return;
            }

            if (link.href === '') {
                this.unsetLink();
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
                .selectTextblockEnd()
                .run();
        }
    }"
/>
