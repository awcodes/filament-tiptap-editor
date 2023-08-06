<?php

namespace FilamentTiptapEditor\Actions;

use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\CheckboxList;
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
            ->modalWidth('md')
            ->arguments([
                'href' => '',
                'hreflang' => '',
                'target' => '',
                'rel' => [],
                'as_button' => false,
                'button_theme' => '',
            ])->mountUsing(function (ComponentContainer $form, array $arguments) {
                $form->fill($arguments);
            })->modalHeading(function(array $arguments) {
                $context = blank($arguments['href']) ? 'insert' : 'update';
                return __('filament-tiptap-editor::link-modal.heading.' . $context);
            })->form([
                Grid::make(['md' => 2])
                    ->schema([
                        TextInput::make('href')
                            ->label(__('filament-tiptap-editor::link-modal.labels.url'))
                            ->columnSpan('full')
                            ->required(),
                        Select::make('target')->options([
                            '' => __('filament-tiptap-editor::link-modal.labels.target.default'),
                            '_blank' => __('filament-tiptap-editor::link-modal.labels.target.new_window'),
                            '_parent' => __('filament-tiptap-editor::link-modal.labels.target.parent'),
                            '_top' => __('filament-tiptap-editor::link-modal.labels.target.top')
                        ]),
                        TextInput::make('hreflang')
                            ->label(__('filament-tiptap-editor::link-modal.labels.language')),
                        CheckboxList::make('rel')
                            ->columnSpan('full')
                            ->columns(3)
                            ->options([
                                'nofollow' => __('filament-tiptap-editor::link-modal.labels.rel.nofollow'),
                                'noopener' => __('filament-tiptap-editor::link-modal.labels.rel.noopener'),
                                'noreferrer' => __('filament-tiptap-editor::link-modal.labels.rel.noreferrer'),
                            ]),
                        Toggle::make('as_button')
                            ->label(__('filament-tiptap-editor::link-modal.labels.as_button'))
                            ->reactive(),
                        Radio::make('button_theme')
                            ->columnSpan('full')
                            ->columns(2)
                            ->visible(fn ($get) => $get('as_button'))
                            ->options([
                                'primary' => __('filament-tiptap-editor::link-modal.labels.button_theme.primary'),
                                'secondary' => __('filament-tiptap-editor::link-modal.labels.button_theme.secondary'),
                                'tertiary' => __('filament-tiptap-editor::link-modal.labels.button_theme.tertiary'),
                                'accent' => __('filament-tiptap-editor::link-modal.labels.button_theme.accent'),
                            ]),
                    ])
            ])->action(function(TiptapEditor $component, $data) {
                $component->getLivewire()->dispatch(
                    'insert-link',
                    statePath: $component->getStatePath(),
                    href: $data['href'],
                    hreflang: $data['hreflang'],
                    target: $data['target'],
                    rel: $data['rel'],
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
                                    'style' => 'margin-inline-start: auto;'
                                ];
                            })
                    ];
                }

                return [];
            });
    }
}