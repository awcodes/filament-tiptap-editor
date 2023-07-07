<?php

namespace FilamentTiptapEditor\Actions;

use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\TextInput;
use FilamentTiptapEditor\TiptapEditor;

class VimeoAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'filament_tiptap_vimeo';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->modalHeading(__('filament-tiptap-editor::vimeo-modal.heading'));

        $this->modalWidth('md');

        $this->form([
            TextInput::make('url')
                ->label(__('filament-tiptap-editor::vimeo-modal.labels.url'))
                ->required(),
            Checkbox::make('responsive')
                ->default(true)
                ->label(__('filament-tiptap-editor::vimeo-modal.labels.responsive'))
                ->helperText(__('filament-tiptap-editor::vimeo-modal.labels.responsive_helper'))
                ->reactive()
                ->afterStateUpdated(function (callable $set, $state) {
                    if ($state) {
                        $set('width', '16');
                        $set('height', '9');
                    } else {
                        $set('width', '640');
                        $set('height', '480');
                    }
                }),
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
                        Checkbox::make('autoplay')
                            ->label(__('filament-tiptap-editor::vimeo-modal.labels.autoplay'))
                            ->default(false),
                        Checkbox::make('loop')
                            ->label(__('filament-tiptap-editor::vimeo-modal.labels.loop'))
                            ->default(false),
                    ]),
                    Group::make([
                        Checkbox::make('show_title')
                            ->label(__('filament-tiptap-editor::vimeo-modal.labels.title'))
                            ->default(false),
                        Checkbox::make('byline')
                            ->label(__('filament-tiptap-editor::vimeo-modal.labels.byline'))
                            ->default(false),
                    ]),
                    Group::make([
                        Checkbox::make('portrait')
                            ->label(__('filament-tiptap-editor::vimeo-modal.labels.portrait'))
                            ->default(false),
                    ]),
                ]),
        ]);

        $this->action(function (TiptapEditor $component, $data) {
            $component->getLivewire()->dispatchBrowserEvent('insert-video', [
                'statePath' => $component->getStatePath(),
                'video' => $data,
            ]);
        });
    }
}
