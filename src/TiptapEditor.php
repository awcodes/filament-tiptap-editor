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
use FilamentTiptapEditor\Concerns\HasCustomActions;
use FilamentTiptapEditor\Concerns\InteractsWithMedia;
use FilamentTiptapEditor\Concerns\InteractsWithMenus;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Livewire\Component;

class TiptapEditor extends Field
{
    use CanStoreOutput;
    use HasCustomActions;
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
                return $component->getJSON(decoded: true);
            }

            if ($state && $this->expectsText()) {
                return $component->getText();
            }

            return $state;
        });

        $this->registerListeners([
            'tiptap::setGridBuilderContent' => [
                fn (
                    TiptapEditor $component,
                    string $statePath,
                    array $arguments
                ) => $this->getCustomListener('filament_tiptap_grid', $component, $statePath, $arguments),
            ],
            'tiptap::setSourceContent' => [
                fn (
                    TiptapEditor $component,
                    string $statePath,
                    array $arguments
                ) => $this->getCustomListener('filament_tiptap_source', $component, $statePath, $arguments),
            ],
            'tiptap::setOEmbedContent' => [
                fn (
                    TiptapEditor $component,
                    string $statePath,
                    array $arguments
                ) => $this->getCustomListener('filament_tiptap_oembed', $component, $statePath, $arguments),
            ],
            'tiptap::setLinkContent' => [
                fn (
                    TiptapEditor $component,
                    string $statePath,
                    array $arguments
                ) => $this->getCustomListener('filament_tiptap_link', $component, $statePath, $arguments),
            ],
            'tiptap::setMediaContent' => [
                fn (
                    TiptapEditor $component,
                    string $statePath,
                    array $arguments
                ) => $this->getCustomListener('filament_tiptap_media', $component, $statePath, $arguments),
            ],
            'tiptap::updateBlock' => [
                fn (
                    TiptapEditor $component,
                    string $statePath,
                    array $arguments
                ) => $this->getCustomListener('updateBlock', $component, $statePath, $arguments),
            ],
        ]);

        $this->registerActions([
            SourceAction::make(),
            OEmbedAction::make(),
            GridBuilderAction::make(),
            fn (): Action => $this->getLinkAction(),
            fn (): Action => $this->getMediaAction(),
            fn (): Action => $this->getInsertStaticBlockAction(),
            fn (): Action => $this->getInsertBlockAction(),
            fn (): Action => $this->getUpdateBlockAction(),
        ]);
    }

    public function getCustomListener(string $name, TiptapEditor $component, string $statePath, array $arguments = []): void {
        if ($this->verifyListener($component, $statePath)) {
            return;
        };

        $component
            ->getLivewire()
            ->mountFormComponentAction($statePath, $name, $arguments);
    }

    public function renderBlockPreviews(array $document): array
    {
        $content = $document['content'];

        foreach ($content as $k => $block) {
            if ($block['type'] === 'tiptapBlock') {
                $instance = $this->getBlock($block['attrs']['type']);
                $content[$k]['attrs']['preview'] = $instance->getPreview($block['attrs']['data']);
                $content[$k]['attrs']['label'] = $instance->getLabel();
            } elseif (array_key_exists('content', $block)) {
                $content[$k] = $this->renderBlockPreviews($block);
            }
        }

        $document['content'] = $content;

        return $document;
    }

    public function getInsertStaticBlockAction(): Action
    {
        return Action::make('insertStaticBlock')
            ->action(function (TiptapEditor $component, Component $livewire, array $arguments): void {
                $block = $component->getBlock($arguments['type']);

                $livewire->dispatch(
                    event: 'insert-block',
                    statePath: $component->getStatePath(),
                    type: $arguments['type'],
                    data: [],
                    preview: $block->getPreview(),
                    label: $block->getLabel(),
                );
            });
    }

    public function getInsertBlockAction(): Action
    {
        return Action::make('insertBlock')
            ->form(function(TiptapEditor $component, Component $livewire): array {
                $arguments = Arr::first($livewire->mountedFormComponentActionsArguments);

                return $component
                    ->getBlock($arguments['type'])
                    ->getFormSchema();
            })
            ->modalHeading(fn (): string => trans('filament-tiptap-editor::editor.blocks.insert'))
            ->modalWidth(function(TiptapEditor $component, array $arguments): string {
                return isset($arguments['type'])
                    ? $component->getBlock($arguments['type'])->getModalWidth()
                    : 'sm';
            })
            ->slideOver(function(TiptapEditor $component, array $arguments): string {
                return isset($arguments['type']) && $component->getBlock($arguments['type'])->isSlideOver();
            })
            ->action(function (TiptapEditor $component, Component $livewire, array $arguments, $data): void {
                $block = $component->getBlock($arguments['type']);

                $livewire->dispatch(
                    event: 'insert-block',
                    statePath: $component->getStatePath(),
                    type: $arguments['type'],
                    data: $data,
                    preview: $block->getPreview($data),
                    label: $block->getLabel(),
                );
            });
    }

    public function getUpdateBlockAction(): Action
    {
        return Action::make('updateBlock')
            ->fillForm(fn (array $arguments) => $arguments['data'])
            ->modalHeading(fn () => trans('filament-tiptap-editor::editor.blocks.update'))
            ->modalWidth(function(TiptapEditor $component, array $arguments): string {
                return isset($arguments['type'])
                    ? $component->getBlock($arguments['type'])->getModalWidth()
                    : 'sm';
            })
            ->slideOver(function(TiptapEditor $component, array $arguments): string {
                return isset($arguments['type']) && $component->getBlock($arguments['type'])->isSlideOver();
            })
            ->form(function(TiptapEditor $component, Component $livewire): array {
                $arguments = Arr::first($livewire->mountedFormComponentActionsArguments);

                return $component
                    ->getBlock($arguments['type'])
                    ->getFormSchema();
            })
            ->action(function (TiptapEditor $component, Component $livewire, array $arguments, $data): void {
                $block = $component->getBlock($arguments['type']);

                $livewire->dispatch(
                    event: 'update-block',
                    statePath: $component->getStatePath(),
                    type: $arguments['type'],
                    data: $data,
                    preview: $block->getPreview($data),
                    label: $block->getLabel(),
                );
            });
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

    public function getBlock(string $identifier): TiptapBlock
    {
        return $this->getBlocks()[$identifier];
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

    public function verifyListener(TiptapEditor $component, string $statePath): bool
    {
        return $component->isDisabled() || $statePath !== $component->getStatePath();
    }
}
