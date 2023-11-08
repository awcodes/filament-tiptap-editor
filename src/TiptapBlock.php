<?php

namespace FilamentTiptapEditor;

use Illuminate\Support\Str;

abstract class TiptapBlock
{
    public string $preview = 'filament-tiptap-editor::tiptap-block-preview';

    public string $rendered = 'filament-tiptap-editor::tiptap-block-preview';

    public ?string $identifier = null;

    public ?string $label = null;

    public string $width = 'sm';

    public bool $slideOver = false;

    public function getIdentifier(): string
    {
        return $this->identifier ?? Str::kebab(class_basename($this));
    }

    public function getLabel(): string
    {
        return $this->label ?? Str::of(class_basename($this))
            ->kebab()
            ->replace('-', ' ')
            ->title();
    }

    public function getModalWidth(): string
    {
        return $this->width ?? 'sm';
    }

    public function isSlideOver(): bool
    {
        return $this->slideOver ?? false;
    }
}