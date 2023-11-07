<?php

namespace FilamentTiptapEditor\Livewire;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Contracts\View\View;
use Livewire\Component;

abstract class TiptapBlock extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public string $preview = '';

    public ?string $label = null;

    public string $context = 'insert';

    public string $width = 'sm';

    public bool $slideOver = false;

    public function mount(array $data = [], string $context = 'insert'): void
    {
        $this->context = $context;
        $this->form->fill($data);
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema($this->getFormSchema());
    }

    public function getLabel(): string
    {
        return $this->label ?? $this->getName();
    }

    public function getModalWidth(): string
    {
        return $this->width ?? 'sm';
    }

    public function isSlideOver(): bool
    {
        return $this->slideOver ?? false;
    }

    public function getFormSchema(): array
    {
        return [];
    }

    public function create(): void
    {
        $data = $this->form->getState();

        $preview = view($this->preview, $data)->render();

        $this->dispatch(
            event: $this->context . '-bus-block',
            id: 'tiptap-bus',
            data: $data,
            preview: $preview,
            label: $this->getLabel(),
        );
    }

    public function render(): View
    {
        return view('filament-tiptap-editor::tiptap-block');
    }
}