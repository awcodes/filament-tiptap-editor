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

class LinkAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'filament_tiptap_link';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->mountUsing(function (TiptapEditor $component, ComponentContainer $form) {
            $form->fill([
                'href' => $component->getLivewire()->linkProps['href'],
                'hreflang' => $component->getLivewire()->linkProps['hreflang'],
                'target' => $component->getLivewire()->linkProps['target'],
                'rel' => $component->getLivewire()->linkProps['rel'],
                'as_button' => $component->getLivewire()->linkProps['as_button'],
                'button_theme' => $component->getLivewire()->linkProps['button_theme'],
            ]);
        });

        $this->modalWidth('md');

        $this->modalHeading(function(TiptapEditor $component) {
            $context = blank($component->getLivewire()->linkProps['href']) ? 'insert' : 'update';
            return __('filament-tiptap-editor::link-modal.heading.' . $context);
        });

        $this->form([
            Grid::make(['md' => 2])
                ->schema([
                    TextInput::make('href')
                        ->label(__('filament-tiptap-editor::link-modal.labels.url'))
                        ->columnSpan('full')
                        ->required(),
                    Select::make('target')
                         ->label(__('filament-tiptap-editor::link-modal.labels.target.title'))
                         ->options([
                            '' => __('filament-tiptap-editor::link-modal.labels.target.default'),
                            '_blank' => __('filament-tiptap-editor::link-modal.labels.target.new_window'),
                            '_parent' => __('filament-tiptap-editor::link-modal.labels.target.parent'),
                            '_top' => __('filament-tiptap-editor::link-modal.labels.target.top')
                        ]),
                    TextInput::make('hreflang')
                        ->label(__('filament-tiptap-editor::link-modal.labels.language')),
                    CheckboxList::make('rel')
                        ->label(__('filament-tiptap-editor::link-modal.labels.rel.title'))
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
                        ->label(__('filament-tiptap-editor::link-modal.labels.button_theme.title'))
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
        ]);

        $this->action(function(TiptapEditor $component, $data) {
            $component->getLivewire()->dispatchBrowserEvent('insert-link', [
                'statePath' => $component->getStatePath(),
                'href' => $data['href'],
                'hreflang' => $data['hreflang'],
                'target' => $data['target'],
                'rel' => $data['rel'],
                'as_button' => $data['as_button'],
                'button_theme' => $data['as_button'] ? $data['button_theme'] : '',
            ]);

            $component->state($component->getState());
        });

        $this->extraModalActions(function (TiptapEditor $component) {
            return [
                \Filament\Forms\Components\Actions\Modal\Actions\Action::make('remove_link')
                    ->color('danger')
                    ->label(__('filament-tiptap-editor::link-modal.buttons.remove'))
                    ->extraAttributes([
                        'x-on:click' => '$dispatch(\'unset-link\', {statePath: \'' . $component->getStatePath() . '\'}); close()',
                        'style' => 'margin-left: auto;'
                    ])
            ];
        });
    }
}
