@props([
    'fieldId' => null,
])
<button type="button"
    class="p-2"
    x-on:click="openModal()"
    x-on:insert-link.window="$event.detail.fieldId === '{{ $fieldId }}' ? insertLink($event.detail.link) : null"
    x-on:remove-link.window="$event.detail.fieldId === '{{ $fieldId }}' ? unsetLink($event.detail.link) : null"
    x-tooltip="'Link'"
    x-data="{
        unsetLink() {
                this.editor().chain().focus().extendMarkRange('link').unsetLink().run();
            },
            openModal() {
                let href = this.editor().getAttributes('link').href;
                let target = this.editor().getAttributes('link').target || null;
                let hreflang = this.editor().getAttributes('link').hreflang || null;
                let rel = this.editor().getAttributes('link').rel || null;
    
                $dispatch('open-modal', {
                    id: 'filament-tiptap-editor-link-modal',
                    fieldId: '{{ $fieldId }}',
                    href: href,
                    hreflang: hreflang,
                    target: target,
                    rel: rel,
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
                        rel: link.rel.length ? link.rel.join(' ') : null
                    })
                    .run();
            }
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
            d="M17 7h-3c-.55 0-1 .45-1 1s.45 1 1 1h3c1.65 0 3 1.35 3 3s-1.35 3-3 3h-3c-.55 0-1 .45-1 1s.45 1 1 1h3c2.76 0 5-2.24 5-5s-2.24-5-5-5zm-9 5c0 .55.45 1 1 1h6c.55 0 1-.45 1-1s-.45-1-1-1H9c-.55 0-1 .45-1 1zm2 3H7c-1.65 0-3-1.35-3-3s1.35-3 3-3h3c.55 0 1-.45 1-1s-.45-1-1-1H7c-2.76 0-5 2.24-5 5s2.24 5 5 5h3c.55 0 1-.45 1-1s-.45-1-1-1z">
        </path>
    </svg>
    <span class="sr-only">Link</span>
</button>
