<?php

namespace FilamentTiptapEditor\Blocks;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use FilamentTiptapEditor\TiptapBlock;
use Illuminate\Support\Str;

class VideoBlock extends TiptapBlock
{
    public string $preview = 'filament-tiptap-editor::components.blocks.previews.video';

    public string $width = 'lg';

    public function getFormSchema(): array
    {
        return [
            TextInput::make('url')
                ->label(trans('filament-tiptap-editor::oembed-modal.labels.url'))
                ->live(onBlur: true)
                ->required()
                ->afterStateUpdated(function ($state, callable $set) {
                    $set('url', $this->convertUrl($state));
                }),
            CheckboxList::make('native_options')
                ->hiddenLabel()
                ->gridDirection('row')
                ->columns(3)
                ->visible(function (callable $get) {
                    return ! (str_contains($get('url'), 'vimeo') || str_contains($get('url'), 'youtube') || str_contains($get('url'), 'youtu.be'));
                })
                ->options([
                    'autoplay' => trans('filament-tiptap-editor::oembed-modal.labels.autoplay'),
                    'loop' => trans('filament-tiptap-editor::oembed-modal.labels.loop'),
                    'controls' => trans('filament-tiptap-editor::oembed-modal.labels.controls'),
                ]),
            CheckboxList::make('vimeo_options')
                ->hiddenLabel()
                ->gridDirection('row')
                ->columns(3)
                ->visible(function (callable $get) {
                    return str_contains($get('url'), 'vimeo');
                })
                ->options([
                    'autoplay' => trans('filament-tiptap-editor::oembed-modal.labels.autoplay'),
                    'loop' => trans('filament-tiptap-editor::oembed-modal.labels.loop'),
                    'show_title' => trans('filament-tiptap-editor::oembed-modal.labels.title'),
                    'byline' => trans('filament-tiptap-editor::oembed-modal.labels.byline'),
                    'portrait' => trans('filament-tiptap-editor::oembed-modal.labels.portrait'),
                ]),
            Group::make([
                CheckboxList::make('youtube_options')
                    ->hiddenLabel()
                    ->gridDirection('row')
                    ->columns(3)
                    ->options([
                        'controls' => trans('filament-tiptap-editor::oembed-modal.labels.controls'),
                        'nocookie' => trans('filament-tiptap-editor::oembed-modal.labels.nocookie'),
                    ]),
                TimePicker::make('start_at')
                    ->label(trans('filament-tiptap-editor::oembed-modal.labels.start_at'))
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
                ->label(trans('filament-tiptap-editor::oembed-modal.labels.responsive'))
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
                    ->label(trans('filament-tiptap-editor::oembed-modal.labels.width'))
                    ->default('16'),
                TextInput::make('height')
                    ->reactive()
                    ->required()
                    ->label(trans('filament-tiptap-editor::oembed-modal.labels.height'))
                    ->default('9'),
            ])->columns(['md' => 2]),
        ];
    }

    public function convertUrl(string $url, array $options = []): string
    {
        if (Str::of($url)->contains('/video/')) {
            return $url;
        }

        preg_match('/\.com\/([0-9]+)/', $url, $matches);

        if (! $matches || ! $matches[1]) {
            return '';
        }

        $query = http_build_query([
            'autoplay' => $options['autoplay'] ?? false,
            'loop' => $options['loop'] ?? false,
            'title' => $options['show_title'] ?? false,
            'byline' => $options['byline'] ?? false,
            'portrait' => $options['portrait'] ?? false,
        ]);

        return "https://player.vimeo.com/video/{$matches[1]}?{$query}";
    }
}
