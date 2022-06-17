<?php

namespace FilamentTiptapEditor\Components;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Grid;
use Livewire\Component;
use Filament\Forms\Components\Group;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;

class VimeoModal extends Component implements HasForms
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
                ->label(__('filament-tiptap-editor::vimeo-modal.labels.url'))
                ->required(),
            Checkbox::make('responsive')->default(true)->helperText('If video is not responsive, set width and height to the actual pixel dimensions the video should be. Default is 640x480.'),
            Group::make([
                TextInput::make('width')
                    ->default('16')
                    ->label(__('filament-tiptap-editor::vimeo-modal.labels.width')),
                TextInput::make('height')
                    ->default('9')
                    ->label(__('filament-tiptap-editor::vimeo-modal.labels.height')),
            ])->columns(['md' => 2]),
            Grid::make(['md' => 3])
                ->schema([
                    Group::make([
                        Checkbox::make('autoplay')->default(false),
                        Checkbox::make('loop')->default(false),
                    ]),
                    Group::make([
                        Checkbox::make('show_title')->label('Title')->default(false),
                        Checkbox::make('byline')->default(false),
                    ]),
                    Group::make([
                        Checkbox::make('portrait')->default(false),
                    ]),
                ]),
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
        $this->dispatchBrowserEvent('close-modal', ['id' => 'filament-tiptap-editor-vimeo-modal']);
        $this->dispatchBrowserEvent('insert-vimeo', ['id' => 'filament-tiptap-editor-vimeo-modal', 'video' => $video, 'fieldId' => $this->fieldId]);
    }

    public function render()
    {
        return view('filament-tiptap-editor::components.vimeo-modal');
    }
}
