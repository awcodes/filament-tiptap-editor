<?php

namespace FilamentTiptapEditor;

use Filament\Forms\Components\Component;
use Filament\Support\Concerns\EvaluatesClosures;
use Illuminate\Support\Str;
use Throwable;

abstract class TiptapBlock
{
    use EvaluatesClosures;

    public string $preview = 'filament-tiptap-editor::tiptap-block-preview';

    public string $rendered = 'filament-tiptap-editor::tiptap-block-preview';

    public ?string $identifier = null;

    public ?string $label = null;

    public string $width = 'sm';

    public bool $slideOver = false;

    public ?string $icon = null;

    public function getIdentifier(): string
    {
        return $this->identifier ?? Str::camel(class_basename($this));
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

    public function getFormSchema(): array
    {
        return [];
    }

    /**
     * @throws Throwable
     */
    public function getPreview(?array $data = null, ?Component $component = null): string
    {
        $data = $data ?? [];

        return view($this->preview, [
            ...$data,
            'component' => $component,
        ])->render();
    }

    /**
     * @throws Throwable
     */
    public function getRendered(?array $data = null): string
    {
        $data = $data ?? [];

        return view($this->rendered, $data)->render();
    }

    public function getIcon(): ?string
    {
        return $this->evaluate($this->icon);
    }
}
