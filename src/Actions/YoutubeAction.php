<?php

namespace FilamentTiptapEditor\Actions;

use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\TextInput;
use FilamentTiptapEditor\TiptapEditor;

class YoutubeAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'filament_tiptap_youtube';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->modalHeading(__('filament-tiptap-editor::youtube-modal.heading'));

        $this->modalWidth('md');

        $this->form([
            TextInput::make('url')
                ->label(__('filament-tiptap-editor::youtube-modal.labels.url'))
                ->required(),
            Checkbox::make('responsive')
                ->default(true)
                ->label(__('filament-tiptap-editor::youtube-modal.labels.responsive'))
                ->helperText(__('filament-tiptap-editor::youtube-modal.labels.responsive_helper'))
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
                    ->label(__('filament-tiptap-editor::youtube-modal.labels.width')),
                TextInput::make('height')
                    ->default('9')
                    ->label(__('filament-tiptap-editor::youtube-modal.labels.height')),
            ])->columns(['md' => 2]),
        ]);

        $this->action(function (TiptapEditor $component, $data) {
            $component->getLivewire()->dispatchBrowserEvent('insert-video', [
                'statePath' => $component->getStatePath(),
                'video' => $data,
            ]);
        });
    }
}
