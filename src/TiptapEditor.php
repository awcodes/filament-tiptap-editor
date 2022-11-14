<?php

namespace FilamentTiptapEditor;

use Closure;
use Filament\Forms\Components\Field;
use Filament\Support\Concerns\HasExtraAlpineAttributes;
use Filament\Forms\Components\Concerns\CanBeLengthConstrained;
use Filament\Forms\Components\Concerns\HasExtraInputAttributes;
use Filament\Forms\Components\Contracts\CanBeLengthConstrained as CanBeLengthConstrainedContract;
use FilamentTiptapEditor\Enums\TiptapOutput;
use FilamentTiptapEditor\Exceptions\InvalidOutputFormatException;

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

    protected null | string | TiptapOutput $output = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->profile = implode(',', config('filament-tiptap-editor.profiles.default'));
        $this->output(config('filament-tiptap-editor.output'));
        $this->validateOutputFormat();

        $this->beforeStateDehydrated(function(TiptapEditor $component, string $state) {
            if ($this->output === TiptapOutput::Json || $this->output === self::OUTPUT_JSON) {
                $component->state(json_decode($state));
            }
        });
    }

    public function profile(?string $profile): static
    {
        $this->profile = implode(',', config('filament-tiptap-editor.profiles.' . $profile));

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

    public function output(TiptapOutput | string $output): static
    {
        $this->output = $output;
        $this->validateOutputFormat();

        return $this;
    }

    public function getTools(): string
    {
        return !$this->tools ? $this->profile : implode(',', $this->tools);
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
        return ($this->output instanceof TiptapOutput)
            ? $this->output->value
            : $this->output;
    }

    protected function validateOutputFormat(): void 
    {
        $availableFormats = [
            self::OUTPUT_HTML,
            self::OUTPUT_JSON,
            self::OUTPUT_TEXT,
        ];

        if ($this->output instanceof TiptapOutput) {
            return;
        }

        if (!in_array($this->output, $availableFormats)) {
            throw new InvalidOutputFormatException;
        }
    }
}
