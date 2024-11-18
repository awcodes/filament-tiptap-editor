<?php

namespace FilamentTiptapEditor\Actions;

use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\BaseFileUpload;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use FilamentTiptapEditor\TiptapEditor;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class MediaAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'filament_tiptap_media';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->arguments([
                'src' => '',
                'alt' => '',
                'title' => '',
                'width' => '',
                'height' => '',
                'lazy' => null,
            ])
            ->modalWidth('md')
            ->mountUsing(function (TiptapEditor $component, ComponentContainer $form, array $arguments) {
                $source = $arguments['src'] !== ''
                    ? $component->getDirectory() . Str::of($arguments['src'])
                        ->after($component->getDirectory())
                    : null;

                $form->fill([
                    'src' => $source,
                    'alt' => $arguments['alt'] ?? '',
                    'title' => $arguments['title'] ?? '',
                    'width' => $arguments['width'] ?? '',
                    'height' => $arguments['height'] ?? '',
                    'lazy' => $arguments['lazy'] ?? false,
                ]);
            })->modalHeading(function (TiptapEditor $component, array $arguments) {
                $context = blank($arguments['src'] ?? null) ? 'insert' : 'update';

                return trans('filament-tiptap-editor::media-modal.heading.' . $context);
            })->form(function (TiptapEditor $component) {
                return [
                    FileUpload::make('src')
                        ->label(trans('filament-tiptap-editor::media-modal.labels.file'))
                        ->disk($component->getDisk())
                        ->directory($component->getDirectory())
                        ->visibility($component->getVisibility())
                        ->preserveFilenames($component->shouldPreserveFileNames())
                        ->acceptedFileTypes($component->getAcceptedFileTypes())
                        ->maxFiles(1)
                        ->maxSize($component->getMaxSize())
                        ->minSize($component->getMinSize())
                        ->imageResizeMode($component->getImageResizeMode())
                        ->imageCropAspectRatio($component->getImageCropAspectRatio())
                        ->imageResizeTargetWidth($component->getImageResizeTargetWidth())
                        ->imageResizeTargetHeight($component->getImageResizeTargetHeight())
                        ->required()
                        ->live()
                        ->afterStateUpdated(function (TemporaryUploadedFile $state, callable $set) {
                            if (Str::contains($state->getMimeType(), 'image')) {
                                $set('type', 'image');
                            } else {
                                $set('type', 'document');
                            }

                            if ($dimensions = $state->dimensions()) {
                                $set('width', $dimensions[0]);
                                $set('height', $dimensions[1]);
                            }
                        })
                        ->saveUploadedFileUsing($component->getSaveUploadedFileUsing() ?: function (BaseFileUpload $component, TemporaryUploadedFile $file, callable $set) {
                            $filename = $component->shouldPreserveFilenames() ? pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) : Str::uuid();
                            $storeMethod = $component->getVisibility() === 'public' ? 'storePubliclyAs' : 'storeAs';
                            $extension = $file->getClientOriginalExtension();

                            if (Storage::disk($component->getDiskName())->exists(ltrim($component->getDirectory() . '/' . $filename . '.' . $extension, '/'))) {
                                $filename = $filename . '-' . time();
                            }

                            $upload = $file->{$storeMethod}($component->getDirectory(), $filename . '.' . $extension, $component->getDiskName());

                            return Storage::disk($component->getDiskName())->url($upload);
                        }),
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
                        TextInput::make('width')
                            ->label(trans('filament-tiptap-editor::media-modal.labels.width')),
                        TextInput::make('height')
                            ->label(trans('filament-tiptap-editor::media-modal.labels.height')),
                    ])->columns(),
                    Hidden::make('type')
                        ->default('document'),
                ];
            })->action(function (TiptapEditor $component, $data) {
                if (config('filament-tiptap-editor.use_relative_paths')) {
                    $source = Str::of($data['src'])
                        ->replace(config('app.url'), '')
                        ->ltrim('/')
                        ->prepend('/');
                } else {
                    $source = str_starts_with($data['src'], 'http')
                        ? $data['src']
                        : Storage::disk(config('filament-tiptap-editor.disk'))->url($data['src']);
                }

                $component->getLivewire()->dispatch(
                    event: 'insertFromAction',
                    type: 'media',
                    statePath: $component->getStatePath(),
                    media: [
                        'src' => $source,
                        'alt' => $data['alt'] ?? null,
                        'title' => $data['title'],
                        'width' => $data['width'],
                        'height' => $data['height'],
                        'lazy' => $data['lazy'] ?? false,
                        'link_text' => $data['link_text'] ?? null,
                    ],
                );
            });
    }
}
