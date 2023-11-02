<div wire:ignore.self>
    <x-filament::modal
        id="tiptap-bus"
        :visible="true"
        display-classes="block"
        heading="Insert Block"
    >
        @if ($view && $data)
            @livewire($view, ['data' => $data])
        @endif
    </x-filament::modal>
</div>