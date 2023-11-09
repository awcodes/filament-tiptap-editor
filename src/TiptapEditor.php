<?php

namespace FilamentTiptapEditor;

use Closure;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Concerns\HasExtraInputAttributes;
use Filament\Forms\Components\Field;
use Filament\Support\Concerns\HasExtraAlpineAttributes;
use FilamentTiptapEditor\Actions\GridBuilderAction;
use FilamentTiptapEditor\Actions\OEmbedAction;
use FilamentTiptapEditor\Actions\SourceAction;
use FilamentTiptapEditor\Concerns\CanStoreOutput;
use FilamentTiptapEditor\Concerns\InteractsWithMedia;
use FilamentTiptapEditor\Concerns\InteractsWithMenus;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Livewire\Component;

class TiptapEditor extends Field
{
    use CanStoreOutput;
    use HasExtraAlpineAttributes;
    use HasExtraInputAttributes;
    use InteractsWithMedia;
    use InteractsWithMenus;

    protected array $extensions = [];

    protected string | Closure | null $maxContentWidth = null;

    protected string $profile = 'default';

    protected ?bool $shouldDisableStylesheet = null;

    protected ?array $tools = [];

    protected ?array $blocks = [];

    protected string $view = 'filament-tiptap-editor::tiptap-editor';

    protected function setUp(): void
    {
        parent::setUp();

        $this->tools = config('filament-tiptap-editor.profiles.default');
        $this->extensions = config('filament-tiptap-editor.extensions') ?? [];

        $this->afterStateHydrated(function (TiptapEditor $component, string | array | null $state) {
            if (! $state) {
                $component->state('<p></p>');
                return;
            }

            if ($this->getBlocks() && $this->expectsJSON()) {
                $state = $this->renderBlockPreviews($state);
            } elseif ($this->expectsHTML()) {
                $state = $this->getHTML();
            }

            $component->state($state);
        });

        $this->afterStateUpdated(function (TiptapEditor $component, Component $livewire) {
            $livewire->validateOnly($component->getStatePath());
        });

        $this->dehydrateStateUsing(function (TiptapEditor $component, string | array | null $state) {
            if ($state && $this->expectsJSON()) {
                return json_decode($component->getJSON(), true);
            }

            if ($state && $this->expectsText()) {
                return $component->getText();
            }

            return $state;
        });

        $this->registerListeners([
            'tiptap::setGridBuilderContent' => [
                function (TiptapEditor $component, string $statePath): void {
                    if ($component->isDisabled() || $statePath !== $component->getStatePath()) {
                        return;
                    }

                    $component
                        ->getLivewire()
                        ->mountFormComponentAction($statePath, 'filament_tiptap_grid');
                },
            ],
            'tiptap::setSourceContent' => [
                function (TiptapEditor $component, string $statePath, string $html): void {
                    if ($component->isDisabled() || $statePath !== $component->getStatePath()) {
                        return;
                    }

                    $component
                        ->getLivewire()
                        ->mountFormComponentAction($statePath, 'filament_tiptap_source', ['html' => $html]);
                },
            ],
            'tiptap::setOEmbedContent' => [
                function (TiptapEditor $component, string $statePath): void {
                    if ($component->isDisabled() || $statePath !== $component->getStatePath()) {
                        return;
                    }

                    $component
                        ->getLivewire()
                        ->mountFormComponentAction($statePath, 'filament_tiptap_oembed');
                },
            ],
            'tiptap::setLinkContent' => [
                function (TiptapEditor $component, string $statePath, array $arguments): void {
                    if ($component->isDisabled() || $statePath !== $component->getStatePath()) {
                        return;
                    }

                    $component
                        ->getLivewire()
                        ->mountFormComponentAction($statePath, 'filament_tiptap_link', $arguments);
                },
            ],
            'tiptap::setMediaContent' => [
                function (TiptapEditor $component, string $statePath, array $arguments): void {
                    if ($component->isDisabled() || $statePath !== $component->getStatePath()) {
                        return;
                    }

                    $component
                        ->getLivewire()
                        ->mountFormComponentAction($statePath, 'filament_tiptap_media', $arguments);
                },
            ],
            'tiptap::updateBlock' => [
                function (TiptapEditor $component, string $statePath, array $arguments): void {
                    if ($component->isDisabled() || $statePath !== $component->getStatePath()) {
                        return;
                    }

                    $component
                        ->getLivewire()
                        ->mountFormComponentAction($statePath, 'updateBlock', $arguments);
                },
            ]
        ]);

        $this->registerActions(array_merge(
            [
                SourceAction::make(),
                OEmbedAction::make(),
                GridBuilderAction::make(),
            ],
            [
                config('filament-tiptap-editor.link_action')::make(),
            ],
            Str::of(config('filament-tiptap-editor.media_action'))->contains('\\')
                ? [config('filament-tiptap-editor.media_action')::make()]
                : [],
            [
                function(TiptapEditor $component) {
                    return Action::make('insertBlock')
                        ->form(function(Component $livewire) use ($component): array {
                            $arguments = Arr::first($livewire->mountedFormComponentActionsArguments);

                            return $component
                                ->getBlocks()[$arguments['type']]
                                ->getFormSchema();
                        })
                        ->modalHeading(fn (): string => trans('filament-tiptap-editor::editor.blocks.insert'))
                        ->modalWidth(function(array $arguments) use ($component): string {
                            return isset($arguments['type'])
                                ? $component->getBlocks()[$arguments['type']]->getModalWidth()
                                : 'sm';
                        })
                        ->slideOver(function(array $arguments) use ($component): string {
                            return isset($arguments['type'])
                                ? $component->getBlocks()[$arguments['type']]->isSlideOver()
                                : false;
                        })
                        ->action(function (Component $livewire, array $arguments, $data) use ($component): void {
                            $block = $component->getBlocks()[$arguments['type']];

                            $preview = view(view: $block->preview, data: $data)->render();

                            $livewire->dispatch(
                                event: 'insert-block',
                                type: $arguments['type'],
                                data: $data,
                                preview: $preview,
                                label: $block->getLabel(),
                            );
                        });
                },
                function(TiptapEditor $component) {
                    return Action::make('updateBlock')
                        ->fillForm(function (array $arguments) {
                            return $arguments['data'];
                        })
                        ->modalHeading(fn () => trans('filament-tiptap-editor::editor.blocks.update'))
                        ->modalWidth(function(array $arguments) use ($component): string {
                            return isset($arguments['type'])
                                ? $component->getBlocks()[$arguments['type']]->getModalWidth()
                                : 'sm';
                        })
                        ->slideOver(function(array $arguments) use ($component): string {
                            return isset($arguments['type'])
                                ? $component->getBlocks()[$arguments['type']]->isSlideOver()
                                : false;
                        })
                        ->form(function(Component $livewire) use ($component): array {
                            $arguments = Arr::first($livewire->mountedFormComponentActionsArguments);

                            return $component
                                ->getBlocks()[$arguments['type']]
                                ->getFormSchema();
                        })
                        ->action(function (Component $livewire, array $arguments, $data) use ($component): void {
                            $block = $component->getBlocks()[$arguments['type']];

                            $preview = view(view: $block->preview, data: $data)->render();

                            $livewire->dispatch(
                                event: 'update-block',
                                type: $arguments['type'],
                                data: $data,
                                preview: $preview,
                                label: $block->getLabel(),
                            );
                        });
                }
            ]
        ));
    }

    public function renderBlockPreviews(array $document): array
    {
        $content = $document['content'];

        foreach ($content as $k => $block) {
            if ($block['type'] === 'tiptapBlock') {
                $instance = $this->getBlocks()[$block['attrs']['type']];
                $preview = view($instance->preview, $block['attrs']['data'])->render();
                $content[$k]['attrs']['preview'] = $preview;
                $content[$k]['attrs']['label'] = $instance->getLabel();
            } elseif (array_key_exists('content', $block)) {
                $content[$k] = $this->renderBlockPreviews($block);
            }
        }

        $document['content'] = $content;

        return $document;
    }

    public function maxContentWidth(string | Closure $width): static
    {
        $this->maxContentWidth = $width;

        return $this;
    }

    public function profile(string $profile): static
    {
        $this->profile = $profile;
        $this->tools = config('filament-tiptap-editor.profiles.' . $profile);

        return $this;
    }

    public function blocks(array $blocks): static
    {
        $this->blocks = $blocks;

        return $this;
    }

    public function tools(array $tools): static
    {
        $this->tools = $tools;

        return $this;
    }

    public function getMaxContentWidth(): string
    {
        return $this->maxContentWidth
            ? $this->evaluate($this->maxContentWidth)
            : config('filament-tiptap-editor.max_content_width');
    }

    public function disableStylesheet(): static
    {
        $this->shouldDisableStylesheet = true;

        return $this;
    }

    public function shouldDisableStylesheet(): bool
    {
        return $this->shouldDisableStylesheet ?? config('filament-tiptap-editor.disable_stylesheet');
    }

    public function getBlocks(): array
    {
        return collect($this->blocks)->mapWithKeys(function ($block, $key) {
            $b = app($block);
            return [$b->getIdentifier() => $b];
        })->toArray();
    }

    public function getTools(): array
    {
        $extensions = collect($this->extensions);

        foreach ($this->tools as $k => $tool) {
            if ($ext = $extensions->where('id', $tool)->first()) {
                $this->tools[$k] = $ext;
            }
        }

        return $this->tools;
    }

    public function getExtensionScripts(): array
    {
        return collect(config('filament-tiptap-editor.extensions') ?? [])
            ->transform(function ($ext) {
                return $ext['source'];
            })->toArray();
    }
}
