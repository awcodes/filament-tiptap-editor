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

class EmbedModal extends Component implements HasForms
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
            TextInput::make('url')->type('url')->required(),
        ];
    }

    public function resetForm(): void
    {
        $this->resetErrorBag();
        $this->form->fill();
    }

    public function create(): void
    {
        $data = $this->form->getState();
        ray($data);
        $this->resetForm();
        $this->dispatchBrowserEvent('close-modal', ['id' => 'filament-tiptap-editor-embed-modal']);
        $this->dispatchBrowserEvent('insert-embed', ['id' => 'filament-tiptap-editor-embed-modal', 'url' => $data['url'], 'fieldId' => $this->fieldId]);
    }

    public function render()
    {
        return view('filament-tiptap-editor::components.embed-modal');
    }
}
