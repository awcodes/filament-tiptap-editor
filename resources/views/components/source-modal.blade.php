<div x-data="{
    isOpen: false,
    toggleOpen(event) {
        if (event.detail.id === 'filament-tiptap-editor-source-modal') {
            this.isOpen = !this.isOpen;
            $wire.set('fieldId', event.detail.fieldId);
            $wire.setState(event.detail.source)
        }
    }
}" x-on:close-modal.window="toggleOpen($event)" x-on:open-modal.window="toggleOpen($event)"
    x-on:new-media-added.window="isOpen = false;" aria-labelledby="filament-tiptap-editor-modal-header" role="dialog"
    aria-modal="true" class="inline-block filament-tiptap-editor-modal">

    <div x-show="isOpen" x-transition:enter="ease duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease duration-300"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak
        class="fixed inset-0 z-40 flex items-center min-h-screen p-10 overflow-y-auto transition">

        <button x-on:click="isOpen = false; $wire.resetForm();" type="button" aria-hidden="true"
            class="fixed inset-0 w-full h-full bg-black/50 focus:outline-none filament-tiptap-editor-modal-close-overlay"></button>

        <div x-show="isOpen" x-trap.noscroll="isOpen" x-on:keydown.window.escape="isOpen = false; $wire.resetForm();"
            x-transition:enter="ease duration-300" x-transition:enter-start="translate-y-8"
            x-transition:enter-end="translate-y-0" x-transition:leave="ease duration-300"
            x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-8" x-cloak
            class="relative w-full max-w-md mx-auto mt-auto cursor-pointer md:mb-auto">
            <div @class([
                'w-full mx-auto bg-white rounded-xl flex flex-col cursor-default filament-tiptap-editor-modal-window',
                'dark:bg-gray-800' => config('filament.dark_mode'),
            ])>
                <form wire:submit.prevent="create">
                    <div class="px-4 py-3 filament-tiptap-editor-modal-header">
                        <h3>{{ __('Insert Link') }}</h3>
                    </div>

                    <x-filament::hr />

                    <div class="p-4 space-y-4 filament-tiptap-editor-modal-content">
                        {{ $this->form }}
                    </div>

                    <x-filament::hr />

                    <div class="flex items-center gap-4 px-4 py-3 filament-tiptap-editor-modal-footer">
                        <div class="ml-auto">
                            <x-filament::button type="button" x-on:click="isOpen = false; $wire.resetForm();"
                                color="secondary">
                                Cancel
                            </x-filament::button>
                            <x-filament::button type="submit">
                                Insert
                            </x-filament::button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
