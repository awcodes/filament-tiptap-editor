<?php

namespace FilamentTiptapEditor\Actions;

use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Textarea;
use FilamentTiptapEditor\TiptapEditor;

class SourceAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'filament_tiptap_source';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->modalHeading(__('filament-tiptap-editor::source-modal.heading'))
            ->fillForm(fn ($arguments) => ['source' => $arguments['html']])
            ->form([
                TextArea::make('source')
                    ->label(__('filament-tiptap-editor::source-modal.labels.source'))
                    ->rows(10),
            ])
            ->action(function (TiptapEditor $component, $data) {
                $component->getLivewire()->dispatch(
                    'insert-source',
                    statePath: $component->getStatePath(),
                    source: $data['source'],
                );

                $component->state($data['source']);
            });
    }
}
