<?php

namespace FilamentTiptapEditor\Tests\Fixtures;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;

class Livewire extends Component implements HasForms
{
    use InteractsWithForms;

    public $data;

    public static function make(): static
    {
        return new static();
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    public function data($data): static
    {
        $this->data = $data;

        return $this;
    }

    public function getData()
    {
        return $this->data;
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $model = app($this->form->getModel());

        $model->update($data);
    }

    public function create(): void
    {
        $data = $this->form->getState();
        $model = app($this->form->getModel());

        $model->create($data);
    }
}
