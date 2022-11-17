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

        $this->mountUsing(function (TiptapEditor $component, ComponentContainer $form) {
            return $form->fill([
                'source' => $component->getState()
            ]);
        });

        $this->modalHeading(__('filament-tiptap-editor::source-modal.heading'));

        $this->form([
            TextArea::make('source')
                ->label(__('filament-tiptap-editor::source-modal.labels.source'))
                ->rows(10),
        ]);

        $this->action(function(TiptapEditor $component, $data) {
            $component->getLivewire()->dispatchBrowserEvent('insert-source', [
                'statePath' => $component->getStatePath(),
                'source' => $data['source'],
            ]);

            $component->state($data['source']);
        });
    }
}