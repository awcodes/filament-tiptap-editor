<?php

namespace FilamentTiptapEditor\Actions;

use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\BaseFileUpload;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use FilamentTiptapEditor\TiptapEditor;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Livewire\TemporaryUploadedFile;

class MediaAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'filament_tiptap_media';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->mountUsing(function (TiptapEditor $component, ComponentContainer $form) {
            $source = $component->getLivewire()->mediaProps['src'] !== ''
                ? $component->getDirectory() . Str::of($component->getLivewire()->mediaProps['src'])
                    ->after($component->getDirectory())
                : null;

            $form->fill([
                'src' => $source,
                'alt' => $component->getLivewire()->mediaProps['alt'],
                'title' => $component->getLivewire()->mediaProps['title'],
                'width' => $component->getLivewire()->mediaProps['width'],
                'height' => $component->getLivewire()->mediaProps['height'],
            ]);
        });

        $this->modalHeading(function(TiptapEditor $component) {
            $context = blank($component->getLivewire()->mediaProps['src']) ? 'insert' : 'update';
            return __('filament-tiptap-editor::media-modal.heading.' . $context);
        });

        $this->modalWidth('md');

        $this->form(function(TiptapEditor $component) {

            return [
                FileUpload::make('src')
                    ->label(__('filament-tiptap-editor::media-modal.labels.file'))
                    ->disk($component->getDisk())
                    ->directory($component->getDirectory())
                    ->visibility(config('filament-tiptap-editor.visibility'))
                    ->preserveFilenames(config('filament-tiptap-editor.preserve_file_names'))
                    ->acceptedFileTypes($component->getAcceptedFileTypes())
                    ->maxFiles(1)
                    ->maxSize($component->getMaxFileSize())
                    ->imageCropAspectRatio(config('filament-tiptap-editor.image_crop_aspect_ratio'))
                    ->imageResizeTargetWidth(config('filament-tiptap-editor.image_resize_target_width'))
                    ->imageResizeTargetHeight(config('filament-tiptap-editor.image_resize_target_height'))
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function (TemporaryUploadedFile $state, callable $set) {
                        if (Str::contains($state->getMimeType(), 'image')) {
                            $set('type', 'image');
                        } else {
                            $set('type', 'document');
                        }
                    })
                    ->saveUploadedFileUsing(function (BaseFileUpload $component, TemporaryUploadedFile $file, callable $set) {
                        $filename = $component->shouldPreserveFilenames() ? pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) : Str::uuid();

                        if (Storage::disk($component->getDiskName())->exists(ltrim($component->getDirectory() . '/' . $filename . '.' . $file->getClientOriginalExtension(), '/'))) {
                            $filename = $filename . '-' . time();
                        }

                        if (
                            Str::contains($file->getMimeType(), 'image')
                            && ! Str::contains($file->getMimeType(), 'svg')
                        ) {
                            if (config('filesystems.disks.s3.driver') === 's3') {
                                $image = Image::make($file->readStream());
                            } else {
                                $image = Image::make($file->getRealPath());
                            }

                            $set('width', $image->getWidth());
                            $set('height', $image->getHeight());
                        }

                        if ($component->getVisibility() === 'public') {
                            $upload = $file->storePubliclyAs($component->getDirectory(), $filename . '.' . $file->getClientOriginalExtension(), $component->getDiskName());
                            $link   = Storage::disk($component->getDiskName())->url($upload);
                        }
                        else {
                            $upload = $file->storeAs($component->getDirectory(), $filename . '.' . $file->getClientOriginalExtension(), $component->getDiskName());
                            $link   = Storage::disk($component->getDiskName())->temporaryUrl($upload, now()->addMinutes(5));
                        }
                        return $link;
                    }),
                TextInput::make('link_text')
                    ->label(__('filament-tiptap-editor::media-modal.labels.link_text'))
                    ->required()
                    ->visible(fn (callable $get) => $get('type') == 'document'),
                TextInput::make('alt')
                    ->label(__('filament-tiptap-editor::media-modal.labels.alt'))
                    ->hidden(fn (callable $get) => $get('type') == 'document')
                    ->helperText(new HtmlString('<span class="text-xs"><a href="https://www.w3.org/WAI/tutorials/images/decision-tree" target="_blank" rel="noopener" class="underline text-primary-500 hover:text-primary-600 focus:text-primary-600">' . __('filament-tiptap-editor::media-modal.labels.alt_helper_text') . '</a></span>')),
                TextInput::make('title')
                    ->label(__('filament-tiptap-editor::media-modal.labels.title')),
                Hidden::make('width'),
                Hidden::make('height'),
                Hidden::make('type')
                    ->default('document'),
            ];
        });

        $this->action(function(TiptapEditor $component, $data) {
            $source = str_starts_with($data['src'], 'http')
                ? $data['src']
                : config('app.url') . Storage::url($data['src']);

            $component->getLivewire()->dispatchBrowserEvent('insert-media', [
                'statePath' => $component->getStatePath(),
                'media' => [
                    'src' => $source,
                    'alt' => $data['alt'] ?? null,
                    'title' => $data['title'],
                    'width' => $data['width'],
                    'height' => $data['height'],
                    'link_text' => $data['link_text'] ?? null,
                ],
            ]);
        });
    }
}