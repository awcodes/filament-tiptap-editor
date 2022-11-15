<x-filament-support::modal id="filament-tiptap-editor-media-uploader-modal"
    heading="{{ __('filament-tiptap-editor::media-modal.heading') }}"
    width="md"
    :dark-mode="config('filament.dark_mode')"
    class="filament-tiptap-editor-media-uploader-modal"
>

    <form wire:submit.prevent="create"
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
          x-on:close-modal.window="$event.detail.id == '{{ config('filament-tiptap-editor.media_uploader_id') }}' ? toggleOpen($event) : null"
          x-on:open-modal.window="$event.detail.id == '{{ config('filament-tiptap-editor.media_uploader_id') }}' ? toggleOpen($event) : null"
          x-on:new-media-added.window="isOpen = false;"
    >

        {{ $this->form }}

        <div class="flex items-center justify-end gap-4 pt-3 mt-3 border-t border-gray-300 dark:border-gray-700">
            <x-filament::button type="button"
                x-on:click="isOpen = false; $wire.resetForm();"
                wire:click="cancelInsert"
                color="secondary">
                {{ __('filament-tiptap-editor::media-modal.buttons.cancel') }}
            </x-filament::button>
            <x-filament::button type="submit">
                {{ __('filament-tiptap-editor::media-modal.buttons.insert') }}
            </x-filament::button>
        </div>
    </form>

</x-filament-support::modal>
