<x-filament-support::modal id="filament-tiptap-editor-media-uploader-modal"
    heading="{{ __('Insert Media') }}"
    width="md"
    :dark-mode="config('filament.dark_mode')"
    x-data="{
        toggleOpen(event) {
                $wire.set('fieldId', event.detail.fieldId);
            },
            init() {
                document.addEventListener('FilePond:processfiles', (e) => {
                    $wire.determineType(e.detail.pond.getFile().fileType);
                }, true);
            }
    }"
    x-on:close-modal.window="toggleOpen($event)"
    x-on:open-modal.window="toggleOpen($event)"
    x-on:new-media-added.window="isOpen = false;">

    <form wire:submit.prevent="create">

        {{ $this->form }}

        <div class="flex items-center justify-end gap-4 pt-3 mt-3 border-t border-gray-300 dark:border-gray-700">
            <x-filament::button type="button"
                x-on:click="isOpen = false; $wire.resetForm();"
                wire:click="cancelInsert"
                color="secondary">
                {{ __('Cancel') }}
            </x-filament::button>
            <x-filament::button type="submit">
                {{ __('Insert') }}
            </x-filament::button>
        </div>
    </form>

</x-filament-support::modal>
