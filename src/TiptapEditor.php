<?php

namespace FilamentTiptapEditor;

use Closure;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Concerns\HasExtraInputAttributes;
use Filament\Forms\Components\Concerns\HasPlaceholder;
use Filament\Forms\Components\Field;
use Filament\Support\Concerns\HasExtraAlpineAttributes;
use FilamentTiptapEditor\Actions\GridBuilderAction;
use FilamentTiptapEditor\Actions\OEmbedAction;
use FilamentTiptapEditor\Actions\SourceAction;
use FilamentTiptapEditor\Concerns\CanStoreOutput;
use FilamentTiptapEditor\Concerns\HasCustomActions;
use FilamentTiptapEditor\Concerns\InteractsWithMedia;
use FilamentTiptapEditor\Concerns\InteractsWithMenus;
use Illuminate\Support\Js;
use Illuminate\Support\Str;
use Livewire\Component;

class TiptapEditor extends Field
{
    use CanStoreOutput;
    use HasCustomActions;
    use HasExtraAlpineAttributes;
    use HasExtraInputAttributes;
    use HasPlaceholder;
    use InteractsWithMedia;
    use InteractsWithMenus;

    protected array $extensions = [];

    protected string | Closure | null $maxContentWidth = null;

    protected string $profile = 'default';

    protected ?bool $shouldDisableStylesheet = null;

    protected ?array $tools = [];

    protected ?array $blocks = [];

    protected array | Closure $mergeTags = [];

    protected string $view = 'filament-tiptap-editor::tiptap-editor';

    protected bool $shouldCollapseBlocksPanel = false;

    protected bool $shouldShowMergeTagsInBlocksPanel = true;

    protected array $gridLayouts = [
        'two-columns',
        'three-columns',
        'four-columns',
        'five-columns',
        'fixed-two-columns',
        'fixed-three-columns',
        'fixed-four-columns',
        'fixed-five-columns',
        'asymmetric-left-thirds',
        'asymmetric-right-thirds',
        'asymmetric-left-fourths',
        'asymmetric-right-fourths',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->tools = config('filament-tiptap-editor.profiles.default');
        $this->extensions = config('filament-tiptap-editor.extensions') ?? [];

        $this->afterStateHydrated(function (TiptapEditor $component, string | array | null $state): void {

            if (! $state) {
                return;
            }

            if (! is_array($state)) {
                $state = tiptap_converter()->asJSON($state, decoded: true);
            }

            $state = $this->renderBlockPreviews($state, $component);

            $component->state($state);
        });

        $this->afterStateUpdated(function (TiptapEditor $component, Component $livewire): void {
            $livewire->validateOnly($component->getStatePath());
        });

        $this->dehydrateStateUsing(function (TiptapEditor $component, string | array | null $state): string | array | null {

            if (! $state) {
                return null;
            }

            if ($this->expectsJSON()) {
                if (! is_array($state)) {
                    $state = tiptap_converter()->asJSON($state, decoded: true);
                }

                return $this->decodeBlocksBeforeSave($state);
            }

            if ($this->expectsText()) {
                return tiptap_converter()->asText($state);
            }

            return tiptap_converter()->asHTML($state);
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
            fn (): Action => $this->getInsertBlockAction(),
            fn (): Action => $this->getUpdateBlockAction(),
        ]);
    }

    public function getCustomListener(string $name, TiptapEditor $component, string $statePath, array $arguments = []): void
    {
        if ($this->verifyListener($component, $statePath)) {
            return;
        }

        $component
            ->getLivewire()
            ->mountFormComponentAction($statePath, $name, $arguments);
    }

    public function renderBlockPreviews(array $document, TiptapEditor $component): array
    {
        $content = $document['content'];

        foreach ($content as $k => $block) {
            if ($block['type'] === 'tiptapBlock') {
                $instance = $this->getBlock($block['attrs']['type']);
                $orderedAttrs = [
                    'preview' => $instance->getPreview($block['attrs']['data']),
                    'statePath' => $component->getStatePath(),
                    'type' => $block['attrs']['type'],
                    'label' => $instance->getLabel(),
                    'data' => Js::from($block['attrs']['data'])->toHtml(),
                ];
                $content[$k]['attrs'] = $orderedAttrs;
            } elseif (array_key_exists('content', $block)) {
                $content[$k] = $this->renderBlockPreviews($block, $component);
            }
        }

        $document['content'] = $content;

        return $document;
    }

    public function decodeBlocksBeforeSave(array $document): array
    {
        $content = $document['content'];

        foreach ($content as $k => $block) {
            if ($block['type'] === 'tiptapBlock') {
                if (is_string($block['attrs']['data'])) {
                    $data = Str::of(json_decode('"' . $block['attrs']['data'] . '"'))
                        ->after('JSON.parse(\'')
                        ->beforeLast('\')')
                        ->toString();

                    $content[$k]['attrs']['data'] = json_decode($data, true);
                }
                unset($content[$k]['attrs']['statePath']);
                unset($content[$k]['attrs']['preview']);
                unset($content[$k]['attrs']['label']);
            } elseif (array_key_exists('content', $block)) {
                $content[$k] = $this->decodeBlocksBeforeSave($block);
            }
        }

        $document['content'] = $content;

        return $document;
    }

    public function getInsertBlockAction(): Action
    {
        return Action::make('insertBlock')
            ->form(function (TiptapEditor $component, Component $livewire, array $arguments): ?array {
                $block = $component->getBlock($arguments['type']);

                if (empty($block->getFormSchema())) {
                    return null;
                }

                return $block->getFormSchema();
            })
            ->modalHeading(function (TiptapEditor $component, Component $livewire, array $arguments): ?string {
                if (isset($arguments['type'])) {
                    $block = $component->getBlock($arguments['type']);

                    if (empty($block->getFormSchema())) {
                        return null;
                    }

                    return trans('filament-tiptap-editor::editor.blocks.insert');
                }

                return trans('filament-tiptap-editor::editor.blocks.insert');
            })
            ->modalWidth(function (TiptapEditor $component, Component $livewire, array $arguments): ?string {
                if (isset($arguments['type'])) {
                    $block = $component->getBlock($arguments['type']);

                    if (empty($block->getFormSchema())) {
                        return null;
                    }

                    return $block->getModalWidth();
                }

                return 'sm';
            })
            ->slideOver(function (TiptapEditor $component, Component $livewire, array $arguments): bool {
                if (isset($arguments['type'])) {
                    $block = $component->getBlock($arguments['type']);

                    if (empty($block->getFormSchema())) {
                        return false;
                    }

                    return $block->isSlideOver();
                }

                return false;
            })
            ->action(function (TiptapEditor $component, Component $livewire, array $arguments, $data): void {
                $block = $component->getBlock($arguments['type']);

                $livewire->dispatch(
                    event: 'insertBlockFromAction',
                    statePath: $component->getStatePath(),
                    type: $arguments['type'],
                    data: Js::from($data)->toHtml(),
                    preview: $block->getPreview($data),
                    label: $block->getLabel(),
                    coordinates: $arguments['coordinates'] ?? [],
                );
            });
    }

    public function getUpdateBlockAction(): Action
    {
        return Action::make('updateBlock')
            ->fillForm(fn (array $arguments) => $arguments['data'])
            ->modalHeading(fn () => trans('filament-tiptap-editor::editor.blocks.update'))
            ->modalWidth(function (TiptapEditor $component, Component $livewire, array $arguments): string {
                return isset($arguments['type'])
                    ? $component->getBlock($arguments['type'])->getModalWidth()
                    : 'sm';
            })
            ->slideOver(function (TiptapEditor $component, Component $livewire, array $arguments): string {
                return isset($arguments['type']) && $component->getBlock($arguments['type'])->isSlideOver();
            })
            ->form(function (TiptapEditor $component, Component $livewire, array $arguments): array {
                return $component
                    ->getBlock($arguments['type'])
                    ->getFormSchema();
            })
            ->action(function (TiptapEditor $component, Component $livewire, array $arguments, $data): void {
                $block = $component->getBlock($arguments['type']);

                $livewire->dispatch(
                    event: 'updateBlockFromAction',
                    statePath: $component->getStatePath(),
                    type: $arguments['type'],
                    data: Js::from($data)->toHtml(),
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

    public function shouldSupportBlocks(): bool
    {
        return filled($this->getBlocks()) && $this->expectsJSON() && in_array('blocks', $this->getTools());
    }

    public function collapseBlocksPanel(bool $condition = true): static
    {
        $this->shouldCollapseBlocksPanel = $condition;

        return $this;
    }

    public function shouldCollapseBlocksPanel(): bool
    {
        return $this->shouldCollapseBlocksPanel;
    }

    public function mergeTags(array | Closure $mergeTags): static
    {
        $this->mergeTags = $mergeTags;

        return $this;
    }

    public function getMergeTags(): ?array
    {
        return $this->evaluate($this->mergeTags) ?? [];
    }

    public function showMergeTagsInBlocksPanel(bool $condition = true): static
    {
        $this->shouldShowMergeTagsInBlocksPanel = $condition;

        return $this;
    }

    public function shouldShowMergeTagsInBlocksPanel(): bool
    {
        return $this->shouldShowMergeTagsInBlocksPanel;
    }

    public function gridLayouts(array $layouts): static
    {
        $this->gridLayouts = $layouts;

        return $this;
    }

    public function getGridLayouts(): array
    {
        return $this->gridLayouts;
    }
}
