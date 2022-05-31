<?php

namespace FilamentTiptapEditor\Components;

use Livewire\Component;
use Illuminate\Support\Str;
use Filament\Facades\Filament;
use Livewire\TemporaryUploadedFile;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\BaseFileUpload;
use Filament\Forms\Concerns\InteractsWithForms;

class SourceModal extends Component implements HasForms
{
    use InteractsWithForms;

    public $data;
    public $fieldId = null;

    public function mount()
    {
        $this->form->fill();
    }

    protected function getFormStatePath(): string
    {
        return 'data';
    }

    protected function getFormSchema(): array
    {
        return [
            TextArea::make('source')->rows(10),
        ];
    }

    public function setState($source)
    {
        $this->form->fill([
            'source' => $source,
        ]);
    }

    public function resetForm(): void
    {
        $this->resetErrorBag();
        $this->form->fill();
    }

    public function create(): void
    {
        $data = $this->form->getState();
        $this->form->fill();
        $this->dispatchBrowserEvent('close-modal', ['id' => 'filament-tiptap-editor-source-modal']);
        $this->dispatchBrowserEvent('insert-source', ['id' => 'filament-tiptap-editor-source-modal', 'source' => $data['source'], 'fieldId' => $this->fieldId]);
    }

    public function render()
    {
        return view('filament-tiptap-editor::components.source-modal');
    }
}
