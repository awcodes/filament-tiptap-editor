<?php

namespace FilamentTiptapEditor\Concerns;

use Filament\Actions\Action;
use Closure;

trait HasCustomActions
{
    public ?string $linkAction = null;

    public ?string $mediaAction = null;

    public ?string $editMediaAction = null;

    public ?string $gridBuilderAction = null;

    public ?string $oembedAction = null;

    public function linkAction(string | Closure $action): static
    {
        $this->linkAction = $action;

        return $this;
    }

    public function mediaAction(string | Closure $action): static
    {
        $this->mediaAction = $action;

        return $this;
    }

    public function oembedAction(string | Closure $action): static
    {
        $this->oembedAction = $action;

        return $this;
    }

    public function getLinkAction(): Action
    {
        $action = $this->evaluate($this->linkAction) ?? config('filament-tiptap-editor.link_action');

        return $action::make();
    }

    public function getMediaAction(): Action
    {
        $action = $this->evaluate($this->mediaAction) ?? config('filament-tiptap-editor.media_action');

        return $action::make();
    }

    public function editMediaAction(string | Closure $action): static
    {
        $this->editMediaAction = $action;

        return $this;
    }

    public function getEditMediaAction(): Action
    {
        $action = $this->evaluate($this->editMediaAction) ?? config('filament-tiptap-editor.edit_media_action');

        return $action::make();
    }

    public function gridBuilderAction(string | Closure $action): static
    {
        $this->gridBuilderAction = $action;

        return $this;
    }

    public function getGridBuilderAction(): Action
    {
        $action = $this->evaluate($this->gridBuilderAction) ?? config('filament-tiptap-editor.grid_builder_action');

        return $action::make();
    }

    public function getOEmbedAction(): Action
    {
        $action = $this->evaluate($this->oembedAction) ?? config('filament-tiptap-editor.oembed_action');

        return $action::make();
    }
}
