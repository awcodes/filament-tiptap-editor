<?php

namespace FilamentTiptapEditor\Traits;

use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\BaseFileUpload;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use FilamentTiptapEditor\TiptapEditor;
use FilamentTiptapEditor\TipTapMedia;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

trait HasMediaActionFormSchema
{
    /**
     * Get the form schema for the media action.
     *
     * @param TiptapEditor $component The editor component.
     * @param ComponentContainer $form The form container.
     * @return array The form schema.
     */
    protected function getFormSchema(TiptapEditor $component, ComponentContainer $form): array
    {
        return [
            Grid::make(['md' => 1])
                ->schema(array_merge($this->getFileUploadFieldSchema($component), $this->getDefaultTipTapFormSchema()))
        ];
    }

    /**
     * Get the default form schema for TipTap media modal.
     *
     * @return array The default form schema.
     */
    public function getDefaultTipTapFormSchema(): array
    {
        return [
            TextInput::make('link_text')
                ->label(trans('filament-tiptap-editor::media-modal.labels.link_text'))
                ->required()
                ->visible(fn (callable $get) => $get('type') == 'document'),
            TextInput::make('alt')
                ->label(trans('filament-tiptap-editor::media-modal.labels.alt'))
                ->hidden(fn (callable $get) => $get('type') == 'document')
                ->hintAction(
                    Action::make('alt_hint_action')
                        ->label('?')
                        ->color('primary')
                        ->url('https://www.w3.org/WAI/tutorials/images/decision-tree', true)
                ),
            TextInput::make('title')
                ->label(trans('filament-tiptap-editor::media-modal.labels.title')),
            Checkbox::make('lazy')
                ->label(trans('filament-tiptap-editor::media-modal.labels.lazy'))
                ->default(false),
            Group::make([
                TextInput::make('width'),
                TextInput::make('height'),
            ])->columns(),
            Hidden::make('type')->default('document'),
        ];
    }

    /**
     * Get the file upload field schema for media action.
     *
     * @param TiptapEditor $component The editor component.
     * @return array The file upload field schema.
     */
    public function getFileUploadFieldSchema(TiptapEditor $component): array
    {
        return [
            FileUpload::make('src')
                ->label(trans('filament-tiptap-editor::media-modal.labels.file'))
                ->disk($component->getDisk())
                ->visibility(config('filament-tiptap-editor.visibility'))
                ->preserveFilenames(config('filament-tiptap-editor.preserve_file_names'))
                ->acceptedFileTypes($component->getAcceptedFileTypes())
                ->maxFiles(1)
                ->maxSize($component->getMaxFileSize())
                ->imageResizeMode(config('filament-tiptap-editor.image_resize_mode'))
                ->imageCropAspectRatio(config('filament-tiptap-editor.image_crop_aspect_ratio'))
                ->imageResizeTargetWidth(config('filament-tiptap-editor.image_resize_target_width'))
                ->imageResizeTargetHeight(config('filament-tiptap-editor.image_resize_target_height'))
                ->required()
                ->live()
                ->imageEditor()
                ->afterStateUpdated(function (TemporaryUploadedFile $state, callable $set) {
                    $set('type', Str::contains($state->getMimeType(), 'image') ? 'image' : 'document');
                    if ($dimensions = $state->dimensions()) {
                        $set('width', $dimensions[0]);
                        $set('height', $dimensions[1]);
                    }
                })
                ->saveUploadedFileUsing(static function (BaseFileUpload $component, TemporaryUploadedFile $file, ?Model $record) {
                    return is_null($record) ? self::OnCreate($component, $file, $record) : self::OnUpdate($component, $file, $record);
                })
        ];
    }

    /**
     * Handle file update for the media action.
     *
     * @param BaseFileUpload $component The file upload component.
     * @param TemporaryUploadedFile $file The uploaded file.
     * @param Model|null $record The model instance.
     * @return mixed The URL of the updated media.
     */
    protected static function OnUpdate(BaseFileUpload $component, TemporaryUploadedFile $file, ?Model $record)
    {
        $filename = $component->shouldPreserveFilenames() ? pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) : Str::uuid();
        $extension = $file->getClientOriginalExtension();
        $filename = $filename . '-' . time() . '.' . $extension;

        $mediaInstance = $record->addMedia($file)
            ->usingFileName($filename)
            ->toMediaCollection(TipTapMedia::mediaCollection($record));

        return $mediaInstance->getUrl();
    }

    /**
     * Handle file creation for the media action.
     *
     * @param BaseFileUpload $component The file upload component.
     * @param TemporaryUploadedFile $file The uploaded file.
     * @param Model|null $record The model instance.
     * @return mixed The URL of the newly uploaded file.
     */
    protected static function OnCreate(BaseFileUpload $component, TemporaryUploadedFile $file, ?Model $record)
    {
        $filename = $component->shouldPreserveFilenames() ? pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) : Str::uuid();
        $storeMethod = $component->getVisibility() === 'public' ? 'storePubliclyAs' : 'storeAs';
        $extension = $file->getClientOriginalExtension();

        if (Storage::disk($component->getDiskName())->exists(ltrim($component->getDirectory() . '/' . $filename . '.' . $extension, '/'))) {
            $filename = $filename . '-' . time();
        }

        $upload = $file->{$storeMethod}($component->getDirectory(), $filename . '.' . $extension, $component->getDiskName());

        return Storage::disk($component->getDiskName())->url($upload);
    }
}
