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

class LinkModal extends Component implements HasForms
{
    use InteractsWithForms;

    public $data;
    public ?string $fieldId = null;
    public ?string $href = null;
    public ?string $target = null;

    public function mount(string $fieldId)
    {
        $this->fieldId = $fieldId;
        $this->form->fill();
    }

    protected function getFormStatePath(): string
    {
        return 'data';
    }

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('url')
                ->label('Url')
                ->required(),
            Select::make('target')
                ->label('Link target')
                ->options([
                    '_self' => 'Default',
                    '_blank' => 'New window',
                    '_parent' => 'Parent',
                    '_top' => 'Top',
                ])
                ->required(),
        ];
    }

    public function setState(?string $href, ?string $target)
    {
        $this->href = $href;
        $this->target = $target ?: '_self';

        $this->form->fill([
            'url' => $this->href,
            'target' => $this->target,
        ]);
    }

    public function resetForm(): void
    {
        $this->resetErrorBag();
        $this->form->fill();
    }

    public function removeLink(): void
    {
        $this->resetForm();
        $this->dispatchBrowserEvent('close-modal', ['id' => 'filament-tiptap-editor-link-modal']);
        $this->dispatchBrowserEvent('remove-link', ['id' => 'filament-tiptap-editor-link-modal', 'fieldId' => $this->fieldId]);
    }

    public function create(): void
    {
        $link = $this->form->getState();
        $this->form->fill();
        $this->dispatchBrowserEvent('close-modal', ['id' => 'filament-tiptap-editor-link-modal']);
        $this->dispatchBrowserEvent('insert-link', ['id' => 'filament-tiptap-editor-link-modal', 'link' => $link, 'fieldId' => $this->fieldId]);
    }

    public function render()
    {
        return view('filament-tiptap-editor::components.link-modal');
    }
}
