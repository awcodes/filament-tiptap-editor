<div wire:ignore.self>
    <x-filament::modal
        id="tiptap-bus"
        :visible="true"
        display-classes="block"
        :heading="$heading"
        :width="$modalWidth"
        :slide-over="$slideOver"
    >
        @if ($type)
            @livewire($type, ['data' => $data, 'context' => $context])
        @endif
    </x-filament::modal>
</div>