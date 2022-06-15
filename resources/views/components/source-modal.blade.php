<x-filament-support::modal id="filament-tiptap-editor-source-modal"
    heading="{{ __('filament-tiptap-editor::source-modal.heading') }}"
    width="3xl"
    :dark-mode="config('filament.dark_mode')"
    x-data="{
        toggleOpen(event) {
            $wire.set('fieldId', event.detail.fieldId);
            $wire.setState(event.detail.source)
        }
    }"
    x-on:close-modal.window="toggleOpen($event)"
    x-on:open-modal.window="toggleOpen($event)"
    class="filament-tiptap-editor-source-modal">

    <form wire:submit.prevent="create">
        {{ $this->form }}

        <div class="flex items-center gap-4 pt-3 mt-3 border-t border-gray-300 dark:border-gray-700">
            <div class="ml-auto">
                <x-filament::button type="button"
                    x-on:click="isOpen = false; $wire.resetForm();"
                    color="secondary">
                    {{ __('filament-tiptap-editor::source-modal.buttons.cancel') }}
                </x-filament::button>
                <x-filament::button type="submit">
                    {{ __('filament-tiptap-editor::source-modal.buttons.update') }}
                </x-filament::button>
            </div>
        </div>
    </form>

</x-filament-support::modal>
