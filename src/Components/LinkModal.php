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
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;

class LinkModal extends Component implements HasForms
{
    use InteractsWithForms;

    public $data;
    public ?string $fieldId = null;
    public ?string $href = null;
    public ?string $hreflang = null;
    public ?string $target = '';
    public ?array $rel = [];
    public ?bool $asButton = false;
    public ?string $buttonTheme = '';

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
            Grid::make(['md' => 2])
                ->schema([
                    TextInput::make('href')
                        ->label(__('filament-tiptap-editor::link-modal.labels.url'))
                        ->columnSpan('full')
                        ->required(),
                    Select::make('target')->options([
                        '' => __('filament-tiptap-editor::link-modal.labels.target.default'),
                        '_blank' => __('filament-tiptap-editor::link-modal.labels.target.new_window'),
                        '_parent' => __('filament-tiptap-editor::link-modal.labels.target.parent'),
                        '_top' => __('filament-tiptap-editor::link-modal.labels.target.top')
                    ]),
                    TextInput::make('hreflang')
                        ->label(__('filament-tiptap-editor::link-modal.labels.language')),
                    CheckboxList::make('rel')
                        ->columnSpan('full')
                        ->columns(3)
                        ->options([
                            'nofollow' => __('filament-tiptap-editor::link-modal.labels.rel.nofollow'),
                            'noopener' => __('filament-tiptap-editor::link-modal.labels.rel.noopener'),
                            'noreferrer' => __('filament-tiptap-editor::link-modal.labels.rel.noreferrer'),
                        ]),
                    Toggle::make('as_button')
                        ->label(__('filament-tiptap-editor::link-modal.labels.as_button'))
                        ->reactive(),
                    Radio::make('button_theme')
                        ->columnSpan('full')
                        ->columns(2)
                        ->visible(fn ($get) => $get('as_button'))
                        ->options([
                            'primary' => __('filament-tiptap-editor::link-modal.labels.button_theme.primary'),
                            'secondary' => __('filament-tiptap-editor::link-modal.labels.button_theme.secondary'),
                            'tertiary' => __('filament-tiptap-editor::link-modal.labels.button_theme.tertiary'),
                            'accent' => __('filament-tiptap-editor::link-modal.labels.button_theme.accent'),
                        ]),
                ])
        ];
    }

    public function setState(?string $href = null, ?string $target = null, ?string $hreflang = null, ?string $rel = null, ?bool $asButton = false, ?string $buttonTheme = '')
    {
        $this->href = $href;
        $this->hreflang = $hreflang;
        $this->target = $target ? $target : '';
        $this->rel = $rel ? Str::of($rel)->trim()->explode(' ')->toArray() : [];
        $this->asButton = $asButton;
        $this->buttonTheme = $buttonTheme;

        $this->form->fill([
            'href' => $this->href,
            'hreflang' => $this->hreflang,
            'target' => $this->target,
            'rel' => $this->rel,
            'as_button' => $this->asButton,
            'button_theme' => $this->buttonTheme,
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
