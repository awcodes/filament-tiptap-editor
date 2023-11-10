<?php

namespace FilamentTiptapEditor\Actions;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use FilamentTiptapEditor\TiptapEditor;

class OEmbedAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'filament_tiptap_oembed';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->modalWidth('lg')
            ->modalHeading(__('filament-tiptap-editor::oembed-modal.heading'))
            ->form([
                TextInput::make('url')
                    ->label(__('filament-tiptap-editor::oembed-modal.labels.url'))
                    ->reactive()
                    ->required(),
                CheckboxList::make('native_options')
                    ->hiddenLabel()
                    ->gridDirection('row')
                    ->columns(3)
                    ->visible(function (callable $get) {
                        return ! (str_contains($get('url'), 'vimeo') || str_contains($get('url'), 'youtube') || str_contains($get('url'), 'youtu.be'));
                    })
                    ->options([
                        'autoplay' => __('filament-tiptap-editor::oembed-modal.labels.autoplay'),
                        'loop' => __('filament-tiptap-editor::oembed-modal.labels.loop'),
                        'controls' => __('filament-tiptap-editor::oembed-modal.labels.controls'),
                    ]),
                CheckboxList::make('vimeo_options')
                    ->hiddenLabel()
                    ->gridDirection('row')
                    ->columns(3)
                    ->visible(function (callable $get) {
                        return str_contains($get('url'), 'vimeo');
                    })
                    ->options([
                        'autoplay' => __('filament-tiptap-editor::oembed-modal.labels.autoplay'),
                        'loop' => __('filament-tiptap-editor::oembed-modal.labels.loop'),
                        'show_title' => __('filament-tiptap-editor::oembed-modal.labels.title'),
                        'byline' => __('filament-tiptap-editor::oembed-modal.labels.byline'),
                        'portrait' => __('filament-tiptap-editor::oembed-modal.labels.portrait'),
                    ]),
                Group::make([
                    CheckboxList::make('youtube_options')
                        ->hiddenLabel()
                        ->gridDirection('row')
                        ->columns(3)
                        ->options([
                            'controls' => __('filament-tiptap-editor::oembed-modal.labels.controls'),
                            'nocookie' => __('filament-tiptap-editor::oembed-modal.labels.nocookie'),
                        ]),
                    TimePicker::make('start_at')
                        ->label(__('filament-tiptap-editor::oembed-modal.labels.start_at'))
                        ->reactive()
                        ->date(false)
                        ->afterStateHydrated(function (TimePicker $component, $state): void {
                            if (! $state) {
                                return;
                            }

                            $state = CarbonInterval::seconds($state)->cascade();
                            $component->state(Carbon::parse($state->h . ':' . $state->i . ':' . $state->s)->format('Y-m-d H:i:s'));
                        })
                        ->dehydrateStateUsing(function ($state): int {
                            if (! $state) {
                                return 0;
                            }

                            return Carbon::parse($state)->diffInSeconds('00:00:00');
                        }),
                ])->visible(function (callable $get) {
                    return str_contains($get('url'), 'youtube') || str_contains($get('url'), 'youtu.be');
                }),
                Checkbox::make('responsive')
                    ->default(true)
                    ->reactive()
                    ->label(__('filament-tiptap-editor::oembed-modal.labels.responsive'))
                    ->afterStateUpdated(function (callable $set, $state) {
                        if ($state) {
                            $set('width', '16');
                            $set('height', '9');
                        } else {
                            $set('width', '640');
                            $set('height', '480');
                        }
                    })
                    ->columnSpan('full'),
                Group::make([
                    TextInput::make('width')
                        ->reactive()
                        ->required()
                        ->label(__('filament-tiptap-editor::oembed-modal.labels.width'))
                        ->default('16'),
                    TextInput::make('height')
                        ->reactive()
                        ->required()
                        ->label(__('filament-tiptap-editor::oembed-modal.labels.height'))
                        ->default('9'),
                ])->columns(['md' => 2]),
            ])
            ->action(function (TiptapEditor $component, $data) {
                $component->getLivewire()->dispatch(
                    'insert-content',
                    type: 'video',
                    statePath: $component->getStatePath(),
                    video: $data,
                );
            });
    }
}
