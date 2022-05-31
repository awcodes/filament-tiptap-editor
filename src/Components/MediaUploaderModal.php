<?php

namespace FilamentTiptapEditor\Components;

use Closure;
use Livewire\Component;
use Illuminate\Support\Str;
use Filament\Facades\Filament;
use Livewire\TemporaryUploadedFile;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Intervention\Image\Facades\Image;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\BaseFileUpload;
use Filament\Forms\Concerns\InteractsWithForms;

class MediaUploaderModal extends Component implements HasForms
{
    use InteractsWithForms;

    public $data;
    public $fieldId = null;
    public $type = 'image';

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
            FileUpload::make('src')
                ->label('File')
                ->disk(config('filament-tiptap-editor.disk'))
                ->directory(config('filament-tiptap-editor.directory'))
                ->visibility(config('filament-tiptap-editor.visibility'))
                ->preserveFilenames(config('filament-tiptap-editor.preserve_file_names'))
                ->acceptedFileTypes(config('filament-tiptap-editor.accepted_file_types'))
                ->maxFiles(1)
                ->maxSize(config('filament-tiptap-editor.max_file_size'))
                ->imageCropAspectRatio(config('filament-tiptap-editor.image_crop_aspect_ratio'))
                ->imageResizeTargetWidth(config('filament-tiptap-editor.image_resize_target_width'))
                ->imageResizeTargetHeight(config('filament-tiptap-editor.image_resize_target_height'))
                ->required()
                ->reactive()
                ->saveUploadedFileUsing(function (BaseFileUpload $component, TemporaryUploadedFile $file, Closure $set) {

                    $filename = $component->shouldPreserveFilenames() ? pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) : Str::uuid();

                    $storeMethod = $component->getVisibility() === 'public' ? 'storePubliclyAs' : 'storeAs';

                    if (Storage::disk($component->getDiskName())->exists(ltrim($component->getDirectory() . '/' . $filename  .  '.' . $file->getClientOriginalExtension(), '/'))) {
                        $filename = $filename . '-' . time();
                    }

                    if (Str::contains($file->getMimeType(), 'image')) {
                        $image = Image::make($file->getRealPath());
                        $set('width', $image->getWidth());
                        $set('height', $height = $image->getHeight());
                    }

                    $upload = $file->{$storeMethod}($component->getDirectory(), $filename  .  '.' . $file->getClientOriginalExtension(), $component->getDiskName());

                    return Storage::disk($component->getDiskName())->url($upload);
                }),
            TextInput::make('link_text')
                ->required()
                ->visible(fn ($livewire) => $livewire->type == 'document'),
            TextInput::make('alt')
                ->label('Description')
                ->hidden(fn ($livewire) => $livewire->type == 'document')
                ->helperText('<span class="text-xs"><a href="https://www.w3.org/WAI/tutorials/images/decision-tree" target="_blank" rel="noopener" class="underline text-primary-500 hover:text-primary-600 focus:text-primary-600">Learn how to describe the purpose of the image</a>. Leave empty if the image is purely decorative.</span>'),
            TextInput::make('title'),
            Hidden::make('width'),
            Hidden::make('height'),
        ];
    }

    public function determineType($type): void
    {
        if (!Str::of($type)->contains('image')) {
            $this->type = 'document';
        }
    }

    public function resetForm(): void
    {
        $this->resetErrorBag();
        $this->type = 'image';
        $this->data = null;
        $this->form->fill();
    }

    public function cancelInsert()
    {
        $this->resetForm();
        $this->dispatchBrowserEvent('close-modal', ['id' => 'filament-tiptap-editor-media-uploader-modal']);
    }

    public function create(): void
    {
        $media = $this->form->getState();
        $this->resetForm();
        $this->dispatchBrowserEvent('close-modal', ['id' => 'filament-tiptap-editor-media-uploader-modal']);
        $this->dispatchBrowserEvent('insert-media', ['id' => 'filament-tiptap-editor-media-uploader-modal', 'media' => $media, 'fieldId' => $this->fieldId]);
    }

    public function render()
    {
        return view('filament-tiptap-editor::components.media-uploader-modal');
    }
}
