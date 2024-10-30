<?php

namespace FilamentTiptapEditor\Actions;

use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\ComponentContainer;
use FilamentTiptapEditor\TiptapEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Actions\Action;

class IframeAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'filament_tiptap_iframe';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->modalWidth('lg')
            ->arguments([
                'src' => '',
                'frameborder' => '',
                'scrolling' => '',
                'allowfullscreen' => '',
                'allow' => '',
                'width' => '',
                'height' => '',
                'loading' => '',
            ])->mountUsing(function (ComponentContainer $form, array $arguments) {
                $form->fill($arguments);
            })->modalHeading(function (array $arguments) {
                $context = blank($arguments['src']) ? 'insert' : 'update';

                return trans('filament-tiptap-editor::iframe-modal.' . $context);
            })->form([
                Grid::make(['md' => 3])
                    ->schema([
                        TextInput::make('src')
                            ->label(trans('filament-tiptap-editor::iframe-modal.fields.src'))
                            ->columnSpanFull()
                            ->url(),
                        Toggle::make('frameborder')
                            ->label(trans('filament-tiptap-editor::iframe-modal.fields.frameborder'))
                            ->columnSpanFull(),
                        Select::make('scrolling')
                            ->options([
                                'auto' => 'Auto',
                                'yes' => 'Yes',
                                'no' => 'No',
                            ])
                            ->columnSpanFull(),
                        Toggle::make('allowfullscreen')
                            ->label(trans('filament-tiptap-editor::iframe-modal.fields.allowfullscreen'))
                            ->columnSpanFull(),
                        TextInput::make('allow')
                            ->columnSpanFull(),
                        TextInput::make('width')
                            ->label(trans('filament-tiptap-editor::iframe-modal.fields.width')),
                        TextInput::make('height')
                            ->label(trans('filament-tiptap-editor::iframe-modal.fields.height')),
                        Select::make('loading')
                            ->options([
                                '' => 'Auto',
                                'eager' => 'Eager',
                                'lazy' => 'Lazy',
                            ]),
                        TextInput::make('style')
                            ->columnSpanFull(),
                    ])
                        ->columns(2),
            ])->action(function (TiptapEditor $component, $data) {
                $data['allowfullscreen'] = $data['allowfullscreen'] ? 1 : 0;
                $component->getLivewire()->dispatch(
                    event: 'insertFromAction',
                    type: 'iframe',
                    statePath: $component->getStatePath(),
                    iframe: $data,
                );

                $component->state($component->getState());
            });
    }
}
