<?php

namespace FilamentTiptapEditor;

use Closure;
use Filament\Forms\Components\Concerns\CanBeLengthConstrained;
use Filament\Forms\Components\Concerns\HasExtraInputAttributes;
use Filament\Forms\Components\Contracts\CanBeLengthConstrained as CanBeLengthConstrainedContract;
use Filament\Forms\Components\Field;
use Filament\Support\Concerns\HasExtraAlpineAttributes;
use FilamentTiptapEditor\Actions\OEmbedAction;
use FilamentTiptapEditor\Actions\SourceAction;
use FilamentTiptapEditor\Exceptions\InvalidOutputFormatException;
use Illuminate\Support\Str;

class TiptapEditor extends Field implements CanBeLengthConstrainedContract
{
    use CanBeLengthConstrained;
    use HasExtraInputAttributes;
    use HasExtraAlpineAttributes;

    public const OUTPUT_HTML = 'html';
    public const OUTPUT_JSON = 'json';
    public const OUTPUT_TEXT = 'text';

    protected string $view = 'filament-tiptap-editor::tiptap-editor';

    protected ?Closure $saveUploadedFileUsing = null;

    public string $profile = 'default';

    protected ?array $tools = [];

    protected ?string $disk = null;

    protected string | Closure | null $directory = null;

    protected ?array $acceptedFileTypes = null;

    protected ?int $maxFileSize = 2042;

    protected null | string $output = null;

    protected array $extensions = [];

    /**
     * @throws InvalidOutputFormatException
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tools = config('filament-tiptap-editor.profiles.default');
        $this->output(config('filament-tiptap-editor.output'));
        $this->validateOutputFormat();

        $this->extensions = config('filament-tiptap-editor.extensions') ?? [];

        $this->dehydrateStateUsing(function(TiptapEditor $component, string | array | null $state) {
            if ($state && $this->output === self::OUTPUT_JSON) {
                return is_array($state) ? $state : json_decode($state);
            }

            return $state;
        });

        $this->registerListeners([
            'tiptap::setSourceContent' => [
                function (TiptapEditor $component, string $statePath): void {
                    if ($component->isDisabled() || $statePath !== $component->getStatePath()) {
                        return;
                    }

                    $component->getLivewire()->mountFormComponentAction($statePath, 'filament_tiptap_source');
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
                function (TiptapEditor $component, string $statePath): void {
                    if ($component->isDisabled() || $statePath !== $component->getStatePath()) {
                        return;
                    }

                    $component->getLivewire()->mountFormComponentAction($statePath, 'filament_tiptap_media');
                },
            ],
        ]);

        $this->registerActions(array_merge(
            [
                SourceAction::make(),
                OEmbedAction::make(),
            ],
            [
                config('filament-tiptap-editor.link_action')::make(),
            ],
            Str::of(config('filament-tiptap-editor.media_action'))->contains('\\')
                ? [config('filament-tiptap-editor.media_action')::make()]
                : [],
        ));
    }

    public function profile(?string $profile): static
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

    public function disk(?string $disk): static
    {
        $this->disk = $disk;

        return $this;
    }

    public function directory(string | Closure | null $directory): static
    {
        $this->directory = $directory;

        return $this;
    }

    public function acceptedFileTypes(?array $acceptedFileTypes): static
    {
        $this->acceptedFileTypes = $acceptedFileTypes;

        return $this;
    }

    public function maxFileSize(?int $maxFileSize): static
    {
        $this->maxFileSize = $maxFileSize;

        return $this;
    }

    /**
     * @throws InvalidOutputFormatException
     */
    public function output(string $output): static
    {
        $this->output = $output;
        $this->validateOutputFormat();

        return $this;
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

    public function getDisk(): string
    {
        return $this->disk ?? config('filament-tiptap-editor.disk');
    }

    public function getDirectory(): string
    {
        return $this->directory ? $this->evaluate($this->directory) : config('filament-tiptap-editor.directory');
    }

    public function getAcceptedFileTypes(): array
    {
        return $this->acceptedFileTypes ?? config('filament-tiptap-editor.accepted_file_types');
    }

    public function getMaxFileSize(): int
    {
        return $this->maxFileSize ?? config('filament-tiptap-editor.max_file_size');
    }

    public function getOutput(): string
    {
        return $this->output;
    }

    /**
     * @throws InvalidOutputFormatException
     */
    protected function validateOutputFormat(): void
    {
        $availableFormats = [
            self::OUTPUT_HTML,
            self::OUTPUT_JSON,
            self::OUTPUT_TEXT,
        ];

        if (!in_array($this->output, $availableFormats)) {
            throw new InvalidOutputFormatException;
        }
    }

    public function getHTML(): string
    {
        return (new Editor)
            ->setContent($this->getState())
            ->getHtml();
    }
}
