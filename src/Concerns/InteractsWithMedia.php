<?php

namespace FilamentTiptapEditor\Concerns;

use Closure;

trait InteractsWithMedia
{
    protected ?array $acceptedFileTypes = null;

    protected string | Closure | null $directory = null;

    protected string | Closure | null $disk = null;

    protected ?int $maxFileSize = null;

    protected bool | Closure | null $shouldPreserveFileNames = null;

    protected string | Closure | null $visibility = null;

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

    public function disk(string | Closure $disk): static
    {
        $this->disk = $disk;

        return $this;
    }

    public function maxFileSize(int $maxFileSize): static
    {
        $this->maxFileSize = $maxFileSize;

        return $this;
    }

    public function preserveFileNames(bool | Closure $shouldPreserveFileNames): static
    {
        $this->shouldPreserveFileNames = $shouldPreserveFileNames;

        return $this;
    }

    public function visibility(string | Closure $visibility): static
    {
        $this->visibility = $visibility;

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

    public function getMaxFileSize(): int
    {
        return $this->maxFileSize ?? config('filament-tiptap-editor.max_file_size');
    }

    public function getVisibility(): string
    {
        return $this->visibility ? $this->evaluate($this->visibility) : config('filament-tiptap-editor.visibility');
    }

    public function shouldPreserveFileNames(): bool
    {
        return $this->shouldPreserveFileNames ? $this->evaluate($this->shouldPreserveFileNames) : config('filament-tiptap-editor.preserve_file_names');
    }
}
