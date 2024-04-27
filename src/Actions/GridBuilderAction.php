<?php

namespace FilamentTiptapEditor\Actions;

use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ViewField;
use FilamentTiptapEditor\TiptapEditor;

class GridBuilderAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'filament_tiptap_grid';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->modalHeading(trans('filament-tiptap-editor::grid-modal.heading'));

        $this->modalWidth('md');

        $this->form([
            Grid::make(2)
                ->schema([
                    ViewField::make('grid_preview')
                        ->view('filament-tiptap-editor::components.grid-modal-preview')
                        ->columnSpanFull(),
                    TextInput::make('columns')
                        ->label(trans('filament-tiptap-editor::grid-modal.labels.columns'))
                        ->required()
                        ->default(2)
                        ->reactive()
                        ->minValue(2)
                        ->maxValue(12)
                        ->numeric()
                        ->step(1),
                    Select::make('stack_at')
                        ->label(trans('filament-tiptap-editor::grid-modal.labels.stack_at'))
                        ->reactive()
                        ->selectablePlaceholder(false)
                        ->options([
                            'none' => trans('filament-tiptap-editor::grid-modal.labels.dont_stack'),
                            'sm' => 'sm',
                            'md' => 'md',
                            'lg' => 'lg',
                        ])
                        ->default('md'),
                    Toggle::make('asymmetric')
                        ->label(trans('filament-tiptap-editor::grid-modal.labels.asymmetric'))
                        ->default(false)
                        ->reactive()
                        ->columnSpanFull(),
                    TextInput::make('asymmetric_left')
                        ->label(trans('filament-tiptap-editor::grid-modal.labels.asymmetric_left'))
                        ->required()
                        ->reactive()
                        ->minValue(1)
                        ->maxValue(12)
                        ->numeric()
                        ->visible(fn (callable $get) => $get('asymmetric')),
                    TextInput::make('asymmetric_right')
                        ->label(trans('filament-tiptap-editor::grid-modal.labels.asymmetric_right'))
                        ->required()
                        ->reactive()
                        ->minValue(1)
                        ->maxValue(12)
                        ->numeric()
                        ->visible(fn (callable $get) => $get('asymmetric')),
                ]),
        ]);

        $this->modalFooterActions(function ($action) {
            return [
                $action->getModalSubmitAction()
                    ->label(trans('filament-tiptap-editor::grid-modal.labels.submit')),
                $action->getModalCancelAction(),
            ];
        });

        $this->action(function (TiptapEditor $component, $data) {
            $component->getLivewire()->dispatch(
                event: 'insertFromAction',
                type: 'grid',
                statePath: $component->getStatePath(),
                data: $data,
            );
        });
    }
}
