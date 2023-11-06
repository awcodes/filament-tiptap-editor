<?php

namespace FilamentTiptapEditor\Livewire;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Blade;
use Livewire\Attributes\On;
use Livewire\Component;

class Bus extends Component
{
    public ?string $view = null;

    public array $data = [];

    #[On('render-bus')]
    public function renderBus($view, $data): void
    {
        $this->view = $view;
        $this->data = $data;

        $this->dispatch('open-modal', id: 'tiptap-bus');
    }

    #[On('close-modal')]
    public function insertBlock($id, $data = null, $preview = ''): void
    {
        $data = $data ?? $this->data;

        if ($id === 'tiptap-bus') {
            if ($data !== $this->data) {
                ray('update-block');
                $this->dispatch('update-block', view: $this->view, data: $data, preview: $preview);
            } else {
                $this->dispatch('insert-block', view: $this->view, data: $data, preview: $preview);
            }
        }
    }

    public function render(): View
    {
        return view('filament-tiptap-editor::bus');
    }
}