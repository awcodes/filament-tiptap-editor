<?php

namespace FilamentTiptapEditor;

use Closure;
use Filament\Forms\Components\Concerns\CanBeLengthConstrained;
use Filament\Forms\Components\Concerns\HasExtraInputAttributes;
use Filament\Forms\Components\Contracts\CanBeLengthConstrained as FilamentCanBeLengthConstrained;
use Filament\Forms\Components\Field;
use Filament\Support\Concerns\HasExtraAlpineAttributes;
use FilamentTiptapEditor\Actions\GridBuilderAction;
use FilamentTiptapEditor\Actions\OEmbedAction;
use FilamentTiptapEditor\Actions\SourceAction;
use FilamentTiptapEditor\Enums\TiptapOutput;
use Illuminate\Support\Str;

class TiptapEditor extends Field implements FilamentCanBeLengthConstrained
{
    use CanBeLengthConstrained;
    use HasExtraInputAttributes;
    use HasExtraAlpineAttributes;

    protected array | null $acceptedFileTypes = null;

    protected string | Closure | null $directory = null;

    protected string | Closure | null $disk = null;

    protected array $extensions = [];

    protected array | Closure | null $floatingMenuTools = null;

    protected string | Closure | null $maxContentWidth = null;

    protected int | null $maxFileSize = null;

    protected TiptapOutput | null $output = null;

    protected string $profile = 'default';

    protected Closure | null $saveUploadedFileUsing = null;

    protected bool | null $shouldShowBubbleMenus = null;

    protected bool | null $shouldShowFloatingMenus = null;

    protected ?array $tools = [];

    protected string $view = 'filament-tiptap-editor::tiptap-editor';

    protected function setUp(): void
    {
        parent::setUp();

        $this->tools = config('filament-tiptap-editor.profiles.default');
        $this->output(config('filament-tiptap-editor.output'));

        $this->extensions = config('filament-tiptap-editor.extensions') ?? [];

        $this->afterStateHydrated(function(TiptapEditor $component, string | array | null $state) {
           if (! $state) {
               $component->state('<p></p>');
           }

           /**
            * @TODO
            * Force html for state temporarily for possible LW3 bug.
            * Revisit this later. Seems like LW is hijacking Tiptap's
            * internal state and trying to diff it with Alpine.
            */
           $component->state($component->getHTML());
        });

        $this->afterStateUpdated(function(TiptapEditor $component, $livewire, string | array | null $state) {
            $tempState = $state;

            if ($state && $this->expectsJSON()) {
                $component->state($component->getHTML());
            }

            $livewire->validateOnly($component->getStatePath());

            if ($state && $this->expectsJSON()) {
                $component->state($tempState);
            }
        });

        $this->dehydrateStateUsing(function(TiptapEditor $component, string | array | null $state) {
            if ($state && $this->expectsJSON()) {
                return is_array($state) ? $state : json_decode($state);
            }

            return $state;
        });

        $this->registerListeners([
            'tiptap::setGridBuilderContent' => [
                function (TiptapEditor $component, string $statePath): void {
                    if ($component->isDisabled() || $statePath !== $component->getStatePath()) {
                        return;
                    }

                    $component->getLivewire()->mountFormComponentAction($statePath, 'filament_tiptap_grid');
                },
            ],
            'tiptap::setSourceContent' => [
                function (TiptapEditor $component, string $statePath, string $html): void {
                    if ($component->isDisabled() || $statePath !== $component->getStatePath()) {
                        return;
                    }

                    $component->getLivewire()->mountFormComponentAction($statePath, 'filament_tiptap_source', ['html' => $html]);
                },
            ],
            'tiptap::setVimeoContent' => [
                function (TiptapEditor $component, string $statePath): void {
                    if ($component->isDisabled() || $statePath !== $component->getStatePath()) {
                        return;
                    }

                    $component->getLivewire()->mountFormComponentAction($statePath, 'filament_tiptap_vimeo');
                },
            ],
            'tiptap::setYoutubeContent' => [
                function (TiptapEditor $component, string $statePath): void {
                    if ($component->isDisabled() || $statePath !== $component->getStatePath()) {
                        return;
                    }

                    $component->getLivewire()->mountFormComponentAction($statePath, 'filament_tiptap_youtube');
                },
            ],
            'tiptap::setOEmbedContent' => [
                function (TiptapEditor $component, string $statePath): void {
                    if ($component->isDisabled() || $statePath !== $component->getStatePath()) {
                        return;
                    }

                    $component->getLivewire()->mountFormComponentAction($statePath, 'filament_tiptap_oembed');
                },
            ],
            'tiptap::setLinkContent' => [
                function (TiptapEditor $component, string $statePath, array $linkProps): void {
                    if ($component->isDisabled() || $statePath !== $component->getStatePath()) {
                        return;
                    }

                    $livewire = $component->getLivewire();
                    data_set($livewire, 'linkProps', $linkProps);
                    $livewire->mountFormComponentAction($statePath, 'filament_tiptap_link');
                },
            ],
            'tiptap::setMediaContent' => [
                function (TiptapEditor $component, string $statePath, array $mediaProps): void {
                    if ($component->isDisabled() || $statePath !== $component->getStatePath()) {
                        return;
                    }

                    $livewire = $component->getLivewire();
                    data_set($livewire, 'mediaProps', $mediaProps);
                    $livewire->mountFormComponentAction($statePath, 'filament_tiptap_media');
                },
            ],
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
        ));
    }

    public function acceptedFileTypes(array $acceptedFileTypes): static
    {
        $this->acceptedFileTypes = $acceptedFileTypes;

        return $this;
    }

    public function directory(string | Closure $directory): static
    {
        $this->directory = $directory;

        return $this;
    }

    public function disableBubbleMenus(bool | Closure | null $condition = true): static
    {
        $this->shouldShowBubbleMenus = $condition;

        return $this;
    }

    public function disableFloatingMenus(bool | Closure | null $condition = true): static
    {
        $this->shouldShowFloatingMenus = $condition;

        return $this;
    }

    public function disk(string | Closure $disk): static
    {
        $this->disk = $disk;

        return $this;
    }

    public function floatingMenuTools(array | Closure $tools): static
    {
        $this->floatingMenuTools = $tools;

        return $this;
    }

    public function maxContentWidth(string | Closure $width): static
    {
        $this->maxContentWidth = $width;

        return $this;
    }

    public function maxFileSize(int $maxFileSize): static
    {
        $this->maxFileSize = $maxFileSize;

        return $this;
    }

    public function output(TiptapOutput $output): static
    {
        $this->output = $output;

        return $this;
    }

    public function profile(string $profile): static
    {
        $this->profile = $profile;
        $this->tools = config('filament-tiptap-editor.profiles.' . $profile);

        return $this;
    }

    public function tools(array $tools): static
    {
        $this->tools = $tools;

        return $this;
    }

    public function getAcceptedFileTypes(): array
    {
        return $this->acceptedFileTypes ?? config('filament-tiptap-editor.accepted_file_types');
    }

    public function getDirectory(): string
    {
        return $this->directory ? $this->evaluate($this->directory) : config('filament-tiptap-editor.directory');
    }

    public function getDisk(): string
    {
        return $this->disk ? $this->evaluate($this->disk) : config('filament-tiptap-editor.disk');
    }

    public function getFloatingMenuTools(): array
    {
        return $this->evaluate($this->floatingMenuTools) ?? config('filament-tiptap-editor.floating_menu_tools');
    }

    public function getMaxContentWidth(): string
    {
        return $this->maxContentWidth
            ? $this->evaluate($this->maxContentWidth)
            : config('filament-tiptap-editor.max_content_width');
    }

    public function getMaxFileSize(): int
    {
        return $this->maxFileSize ?? config('filament-tiptap-editor.max_file_size');
    }

    public function getOutput(): TiptapOutput
    {
        return $this->output;
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

    public function getHTML(): string
    {
        return \FilamentTiptapEditor\Facades\TiptapConverter::asHTML($this->getState());
    }

    public function getText(): string
    {
        return \FilamentTiptapEditor\Facades\TiptapConverter::asText($this->getState());
    }

    public function getJSON(): string
    {
        return \FilamentTiptapEditor\Facades\TiptapConverter::asJSON($this->getState());
    }

    public function isFloatingMenusDisabled(): bool
    {
        return $this->evaluate($this->shouldShowFloatingMenus) ?? config('filament-tiptap-editor.disable_floating_menus');
    }

    public function isBubbleMenusDisabled(): bool
    {
        return $this->evaluate($this->shouldShowBubbleMenus) ?? config('filament-tiptap-editor.disable_bubble_menus');
    }

    public function expectsHTML(): bool
    {
        return $this->output === TiptapOutput::Html;
    }

    public function expectsJSON(): bool
    {
        return $this->output === TiptapOutput::Json;
    }
}
