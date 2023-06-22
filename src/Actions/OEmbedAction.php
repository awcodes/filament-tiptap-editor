<?php

namespace FilamentTiptapEditor\Actions;

use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use FilamentTiptapEditor\TiptapEditor;
use Illuminate\Support\Str;

class OEmbedAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'filament_tiptap_oembed';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->modalHeading(__('filament-tiptap-editor::oembed-modal.heading'));

        $this->modalWidth('md');

        $this->form([
            Group::make([
                Hidden::make('embed_url'),
                Hidden::make('embed_type')
                    ->default('youtube'),
                TextInput::make('url')
                    ->label('URL')
                    ->reactive()
                    ->lazy()
                    ->afterStateUpdated(function (callable $set, callable $get, $state) {
                        if ($state) {
                            $embed_type = Str::of($state)->contains('vimeo') ? 'vimeo' : 'youtube';
                            if ($embed_type == 'vimeo') {
                                $embed_url = static::getVimeoUrl($state);
                            } else {
                                $embed_url = static::getYoutubeUrl($state);
                            }
                            $set('embed_url', $embed_url);
                            $set('embed_type', $embed_type);
                        }
                    })
                    ->required()
                    ->columnSpan('full'),
                Checkbox::make('responsive')
                    ->default(true)
                    ->reactive()
                    ->label('Responsive')
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
                        ->label('Width')
                        ->default('16'),
                    TextInput::make('height')
                        ->reactive()
                        ->required()
                        ->label('Height')
                        ->default('9'),
                ])->columns(['md' => 2]),
                Grid::make(['md' => 3])
                    ->schema([
                        Group::make([
                            Checkbox::make('autoplay')
                                ->default(false)
                                ->label('Autoplay')
                                ->reactive(),
                            Checkbox::make('loop')
                                ->default(false)
                                ->label('Loop')
                                ->reactive(),
                        ]),
                        Group::make([
                            Checkbox::make('show_title')
                                ->default(false)
                                ->label('Title')
                                ->reactive(),
                            Checkbox::make('byline')
                                ->default(false)
                                ->label('Byline')
                                ->reactive(),
                        ]),
                        Group::make([
                            Checkbox::make('portrait')
                                ->default(false)
                                ->label('Portrait')
                                ->reactive(),
                        ]),
                    ]),
            ]),
        ]);

        $this->action(function(TiptapEditor $component, $data) {
            $component->getLivewire()->dispatchBrowserEvent('insert-video', [
                'statePath' => $component->getStatePath(),
                'video' => $data,
            ]);
        });
    }

    public static function getVimeoUrl(string $url): string
    {
        if (Str::of($url)->contains('/video/')) {
            return $url;
        }

        preg_match('/\.com\/([0-9]+)/', $url, $matches);

        if (! $matches || ! $matches[1]) {
            return '';
        }

        return "https://player.vimeo.com/video/{$matches[1]}";
    }

    public static function getYoutubeUrl(string $url): string
    {
        if (Str::of($url)->contains('/embed/')) {
            return $url;
        }

        preg_match('/v=([-\w]+)/', $url, $matches);

        if (! $matches || ! $matches[1]) {
            return '';
        }

        return "https://www.youtube.com/embed/{$matches[1]}";
    }
}