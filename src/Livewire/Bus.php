<?php

namespace FilamentTiptapEditor\Livewire;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;

class Bus extends Component
{
    public ?string $type = null;

    public array $data = [];

    public string $context = 'insert';

    public string $heading = '';

    public string $modalWidth = 'sm';

    public bool $slideOver = false;

    public string $blockId;

    public string $blockLabel;

    #[On('close-modal')]
    public function resetBus($id): void
    {
        if ($id === 'tiptap-bus') {
            $this->type = null;
            $this->data = [];
            $this->context = 'insert';
        }
    }

    #[On('render-bus')]
    public function renderBus(
        string $type,
        array $data = [],
        string $context = 'insert',
    ): void {
        $this->type = $type;
        $this->data = $data;
        $this->context = $context;

        $block = app($this->type);
        $this->blockLabel = $block->getLabel();
        $this->modalWidth = $block->getWidth();
        $this->slideOver = $block->slideOver();
        $this->heading = $context === 'insert'
            ? trans('filament-tiptap-editor::editor.blocks.insert')
            : trans('filament-tiptap-editor::editor.blocks.update');

        $this->dispatch('open-modal', id: 'tiptap-bus');
    }

    #[On('render-preview')]
    public function renderPreview(
        string $type,
        array $data = [],
    ): string {
        $block = app($type);
        return view($block->preview, $data)->render();
    }

    #[On('insert-bus-block')]
    public function insertBlock(array $data = [], string $preview = ''): void
    {
        $data = $data ?? $this->data;

        $this->blockId = Str::ulid();

        $this->dispatch(
            event: 'insert-block',
            settings: [
                'id' => $this->blockId,
                'type' => $this->type,
                'label' => $this->blockLabel,
                'data' => $data,
                'width' => $this->modalWidth,
                'slideOver' => $this->slideOver,
            ],
            preview: $preview,
        );

        $this->dispatch('close-modal', id: 'tiptap-bus');
    }

    #[On('update-bus-block')]
    public function updateBlock(array $data = [], string $preview = ''): void
    {
        $data = $data ?? $this->data;

        $this->dispatch(
            event: 'update-block',
            settings: [
                'id' => $this->blockId,
                'type' => $this->type,
                'label' => $this->blockLabel,
                'data' => $data,
                'width' => $this->modalWidth,
                'slideOver' => $this->slideOver,
            ],
            preview: $preview,
        );

        $this->dispatch('close-modal', id: 'tiptap-bus');
    }

    public function render(): View
    {
        return view('filament-tiptap-editor::bus');
    }
}