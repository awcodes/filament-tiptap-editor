<div>
    <form wire:submit="create">
        {{ $this->form }}

        <x-filament::button type="submit" class="mt-4">
            Submit
        </x-filament::button>
    </form>

    <x-filament-actions::modals />
</div>