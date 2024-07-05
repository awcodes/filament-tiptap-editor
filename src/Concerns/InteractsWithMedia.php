<?php

namespace FilamentTiptapEditor\Concerns;

use Closure;

trait InteractsWithMedia
{
    protected ?array $acceptedFileTypes = null;

    protected string | Closure | null $directory = null;

    protected string | Closure | null $disk = null;

    /**
     * @deprecated Use `$maxSize` instead
     */
    protected ?int $maxFileSize = null;

    protected string | Closure | null $imageCropAspectRatio = null;

    protected string | Closure | null $imageResizeMode = null;

    protected string | Closure | null $imageResizeTargetHeight = null;

    protected string | Closure | null $imageResizeTargetWidth = null;

    protected int | Closure | null $maxSize = null;

    protected int | Closure | null $minSize = null;

    protected bool | Closure | null $shouldPreserveFileNames = null;

    protected string | Closure | null $visibility = null;

    protected ?Closure $saveUploadedFileUsing = null;

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

    public function imageCropAspectRatio(string | Closure | null $ratio): static
    {
        $this->imageCropAspectRatio = $ratio;

        return $this;
    }

    public function imageResizeMode(string | Closure | null $mode): static
    {
        $this->imageResizeMode = $mode;

        return $this;
    }

    public function imageResizeTargetHeight(string | Closure | null $height): static
    {
        $this->imageResizeTargetHeight = $height;

        return $this;
    }

    public function imageResizeTargetWidth(string | Closure | null $width): static
    {
        $this->imageResizeTargetWidth = $width;

        return $this;
    }

    /**
     * @deprecated Use `maxSize()` instead
     */
    public function maxFileSize(int $maxFileSize): static
    {
        $this->maxFileSize = $maxFileSize;

        return $this;
    }

    public function maxSize(int | Closure $size): static
    {
        $this->maxSize = $size;

        return $this;
    }

    public function minSize(int | Closure $size): static
    {
        $this->minSize = $size;

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

    public function saveUploadedFileUsing(?Closure $callback): static
    {
        $this->saveUploadedFileUsing = $callback;

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

    public function getImageCropAspectRatio(): ?string
    {
        return $this->evaluate($this->imageCropAspectRatio) ?? config('filament-tiptap-editor.image_crop_aspect_ratio');
    }

    public function getImageResizeMode(): ?string
    {
        return $this->evaluate($this->imageResizeMode) ?? config('filament-tiptap-editor.image_resize_mode');
    }

    public function getImageResizeTargetHeight(): ?string
    {
        return $this->evaluate($this->imageResizeTargetHeight) ?? config('filament-tiptap-editor.image_resize_target_height');
    }

    public function getImageResizeTargetWidth(): ?string
    {
        return $this->evaluate($this->imageResizeTargetWidth) ?? config('filament-tiptap-editor.image_resize_target_width');
    }

    /**
     * @deprecated Use `getMaxSize()` instead
     */
    public function getMaxFileSize(): int
    {
        return $this->maxFileSize ?? config('filament-tiptap-editor.max_file_size');
    }

    public function getMaxSize(): int
    {
        return $this->evaluate($this->maxSize) ?? config('filament-tiptap-editor.max_file_size');
    }

    public function getMinSize(): int
    {
        return $this->evaluate($this->minSize) ?? config('filament-tiptap-editor.min_file_size');
    }

    public function getVisibility(): string
    {
        return $this->visibility ? $this->evaluate($this->visibility) : config('filament-tiptap-editor.visibility');
    }

    public function getSaveUploadedFileUsing(): ?Closure
    {
        return $this->saveUploadedFileUsing;
    }

    public function shouldPreserveFileNames(): bool
    {
        return $this->shouldPreserveFileNames ? $this->evaluate($this->shouldPreserveFileNames) : config('filament-tiptap-editor.preserve_file_names');
    }
}
