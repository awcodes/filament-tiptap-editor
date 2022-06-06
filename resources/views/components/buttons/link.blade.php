@props([
    'fieldId' => null,
])
<div x-show="buttons.includes('link')"
    style="display: none;">
    <x-filament-tiptap-editor::button action="openModal()"
        active="'link'"
        x-on:insert-link.window="$event.detail.fieldId === '{{ $fieldId }}' ? insertLink($event.detail.link) : null"
        x-on:remove-link.window="$event.detail.fieldId === '{{ $fieldId }}' ? unsetLink($event.detail.link) : null"
        label="Link"
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
        <x-filament-tiptap-editor::icon icon="link" />
    </x-filament-tiptap-editor::button>
</div>
