<?php

namespace FilamentTipTapEditor\Components;

use Livewire\Component;
use Illuminate\Support\Str;
use Filament\Facades\Filament;
use Livewire\TemporaryUploadedFile;
use Filament\Forms\Components\Group;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\BaseFileUpload;
use Filament\Forms\Concerns\InteractsWithForms;

class MediaUploader extends Component implements HasForms
{
    use InteractsWithForms;

    public $data;
    public $fieldId = null;

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
            FileUpload::make('src')
                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml', 'application/pdf'])
                ->label('File')
                ->required()
                ->maxFiles(1)
                ->saveUploadedFileUsing(function (BaseFileUpload $component, TemporaryUploadedFile $file) {

                    $filename = $component->shouldPreserveFilenames() ? pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) : Str::uuid();

                    $storeMethod = $component->getVisibility() === 'public' ? 'storePubliclyAs' : 'storeAs';

                    if (Storage::disk($component->getDiskName())->exists(ltrim($component->getDirectory() . '/' . $filename  .  '.' . $file->getClientOriginalExtension(), '/'))) {
                        $filename = $filename . '-' . time();
                    }

                    $upload = $file->{$storeMethod}($component->getDirectory(), $filename  .  '.' . $file->getClientOriginalExtension(), $component->getDiskName());

                    return Storage::disk($component->getDiskName())->url($upload);
                }),
            TextInput::make('alt')
                ->helperText('<span class="text-xs"><a href="https://www.w3.org/WAI/tutorials/images/decision-tree" target="_blank" rel="noopener" class="underline text-primary-500 hover:text-primary-600 focus:text-primary-600">Learn how to describe the purpose of the image</a>. Leave empty if the image is purely decorative.</span>'),
        ];
    }

    public function resetForm(): void
    {
        $this->resetErrorBag();
        $this->form->fill();
    }

    public function create(): void
    {
        $media = $this->form->getState();
        $this->form->fill();
        $this->dispatchBrowserEvent('close-modal', ['id' => 'filament-tiptap-editor-media-uploader']);
        $this->dispatchBrowserEvent('insert-media', ['id' => 'filament-tiptap-editor-media-uploader', 'media' => $media, 'fieldId' => $this->fieldId]);
    }

    public function render()
    {
        return view('filament-tiptap-editor::components.media-uploader');
    }
}
