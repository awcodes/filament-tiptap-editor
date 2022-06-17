<?php

namespace FilamentTiptapEditor\Components;

use Livewire\Component;
use Filament\Forms\Components\Group;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;

class YoutubeModal extends Component implements HasForms
{
    use InteractsWithForms;

    public $data;
    public ?string $fieldId = null;

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
            TextInput::make('url')
                ->label(__('filament-tiptap-editor::youtube-modal.labels.url'))
                ->required(),
            Checkbox::make('responsive')->default(true)->helperText('If video is not responsive, set width and height to the actual pixel dimensions the video should be. Default is 640x480.'),
            Group::make([
                TextInput::make('width')
                    ->default('16')
                    ->label(__('filament-tiptap-editor::youtube-modal.labels.width')),
                TextInput::make('height')
                    ->default('9')
                    ->label(__('filament-tiptap-editor::youtube-modal.labels.width')),
            ])->columns(['md' => 2])
        ];
    }

    public function resetForm(): void
    {
        $this->resetErrorBag();
        $this->form->fill();
    }

    public function create(): void
    {
        $video = $this->form->getState();
        $this->resetForm();
        $this->dispatchBrowserEvent('close-modal', ['id' => 'filament-tiptap-editor-youtube-modal']);
        $this->dispatchBrowserEvent('insert-youtube', ['id' => 'filament-tiptap-editor-youtube-modal', 'video' => $video, 'fieldId' => $this->fieldId]);
    }

    public function render()
    {
        return view('filament-tiptap-editor::components.youtube-modal');
    }
}
