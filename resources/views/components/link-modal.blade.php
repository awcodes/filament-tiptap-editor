<x-filament-support::modal id="filament-tiptap-editor-link-modal"
    heading="{{ $href ? __('filament-tiptap-editor::link-modal.heading.update') : __('filament-tiptap-editor::link-modal.heading.insert') }}"
    width="md"
    :dark-mode="config('filament.dark_mode')"
    x-data="{
        toggleOpen(event) {
            $wire.set('fieldId', event.detail.fieldId);
            $wire.setState(event.detail.href, event.detail.target, event.detail.hreflang, event.detail.rel, event.detail.as_button, event.detail.button_theme);
            this.$nextTick(() => {
                if (this.isOpen === true && this.$el.querySelector('input')) {
                    this.$el.querySelector('input').focus();
                }
            });
        }
    }"
    x-on:close-modal.window="$event.detail.id == '{{ config('filament-tiptap-editor.link_modal_id') }}' ? toggleOpen($event) : null"
    x-on:open-modal.window="$event.detail.id == '{{ config('filament-tiptap-editor.link_modal_id') }}' ? toggleOpen($event) : null"
    class="filament-tiptap-editor-link-modal">

    <form wire:submit.prevent="create">
        {{ $this->form }}

        <div class="flex items-center gap-4 pt-3 mt-3 border-t border-gray-300 dark:border-gray-700">
            @if ($href)
                <x-filament::button type="button"
                    x-on:click="isOpen = false;"
                    wire:click="removeLink"
                    color="danger">
                    {{ __('filament-tiptap-editor::link-modal.buttons.remove') }}
                </x-filament::button>
            @endif
            <div class="flex items-center gap-2 ml-auto">
                <x-filament::button type="button"
                    x-on:click="isOpen = false; $wire.resetForm();"
                    color="secondary">
                    {{ __('filament-tiptap-editor::link-modal.buttons.cancel') }}
                </x-filament::button>
                <x-filament::button type="submit">
                    {{ $href ? __('filament-tiptap-editor::link-modal.buttons.update') : __('filament-tiptap-editor::link-modal.buttons.insert') }}
                </x-filament::button>
            </div>
        </div>
    </form>

</x-filament-support::modal>
