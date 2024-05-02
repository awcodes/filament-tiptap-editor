<?php

namespace FilamentTiptapEditor\Actions;

use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use FilamentTiptapEditor\TiptapEditor;
use Illuminate\Support\HtmlString;

class LinkAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'filament_tiptap_link';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->modalWidth('lg')
            ->arguments([
                'href' => '',
                'id' => '',
                'hreflang' => '',
                'target' => '',
                'rel' => '',
                'referrerpolicy' => '',
                'as_button' => false,
                'button_theme' => '',
            ])->mountUsing(function (ComponentContainer $form, array $arguments) {
                $form->fill($arguments);
            })->modalHeading(function (array $arguments) {
                $context = blank($arguments['href']) ? 'insert' : 'update';

                return trans('filament-tiptap-editor::link-modal.heading.' . $context);
            })->form([
                Grid::make(['md' => 3])
                    ->schema([
                        TextInput::make('href')
                            ->label(trans('filament-tiptap-editor::link-modal.labels.url'))
                            ->columnSpan('full')
                            ->requiredWithout('id')
                            ->validationAttribute('URL'),
                        TextInput::make('id'),
                        Select::make('target')
                            ->selectablePlaceholder(false)
                            ->options([
                                '' => trans('filament-tiptap-editor::link-modal.labels.target.default'),
                                '_blank' => trans('filament-tiptap-editor::link-modal.labels.target.new_window'),
                                '_parent' => trans('filament-tiptap-editor::link-modal.labels.target.parent'),
                                '_top' => trans('filament-tiptap-editor::link-modal.labels.target.top'),
                            ]),
                        TextInput::make('hreflang')
                            ->label(trans('filament-tiptap-editor::link-modal.labels.language')),
                        TextInput::make('rel')
                            ->columnSpan('full'),
                        TextInput::make('referrerpolicy')
                            ->label(trans('filament-tiptap-editor::link-modal.labels.referrer_policy'))
                            ->columnSpan('full'),
                        Toggle::make('as_button')
                            ->label(trans('filament-tiptap-editor::link-modal.labels.as_button'))
                            ->reactive()
                            ->hidden(config('filament-tiptap-editor.disable_link_as_button'))
                            ->dehydratedWhenHidden(),
                        Radio::make('button_theme')
                            ->columnSpan('full')
                            ->columns(2)
                            ->visible(fn ($get) => $get('as_button'))
                            ->options([
                                'primary' => trans('filament-tiptap-editor::link-modal.labels.button_theme.primary'),
                                'secondary' => trans('filament-tiptap-editor::link-modal.labels.button_theme.secondary'),
                                'tertiary' => trans('filament-tiptap-editor::link-modal.labels.button_theme.tertiary'),
                                'accent' => trans('filament-tiptap-editor::link-modal.labels.button_theme.accent'),
                            ]),
                    ]),
            ])->action(function (TiptapEditor $component, $data) {
                $component->getLivewire()->dispatch(
                    event: 'insertFromAction',
                    type: 'link',
                    statePath: $component->getStatePath(),
                    href: $data['href'],
                    id: $data['id'],
                    hreflang: $data['hreflang'],
                    target: $data['target'],
                    rel: $data['rel'],
                    referrerpolicy: $data['referrerpolicy'],
                    as_button: $data['as_button'],
                    button_theme: $data['as_button'] ? $data['button_theme'] : '',
                );

                $component->state($component->getState());
            })->extraModalFooterActions(function (Action $action): array {
                if ($action->getArguments()['href'] !== '') {
                    return [
                        $action->makeModalSubmitAction('remove_link', [])
                            ->color('danger')
                            ->extraAttributes(function () use ($action) {
                                return [
                                    'x-on:click' => new HtmlString("\$dispatch('unset-link', {'statePath': '{$action->getComponent()->getStatePath()}'}); close()"),
                                    'style' => 'margin-inline-start: auto;',
                                ];
                            }),
                    ];
                }

                return [];
            });
    }
}
