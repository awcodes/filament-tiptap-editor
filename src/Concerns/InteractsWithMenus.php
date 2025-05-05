<?php

namespace FilamentTiptapEditor\Concerns;

use Closure;
use FilamentTiptapEditor\Enums\TippyPlacement;

trait InteractsWithMenus
{
    protected array | Closure | null $bubbleMenuTools = null;

    protected array | Closure | null $floatingMenuTools = null;

    protected bool | Closure | null $shouldShowBubbleMenus = null;

    protected bool | Closure | null $shouldShowFloatingMenus = null;

    protected bool | Closure | null $shouldShowToolbarMenus = null;

    protected string | TippyPlacement | Closure $tippyPlacement = TippyPlacement::Auto;

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

    public function disableToolbarMenus(bool | Closure | null $condition = true): static
    {
        $this->shouldShowToolbarMenus = $condition;

        return $this;
    }

    public function bubbleMenuTools(array | Closure $tools): static
    {
        $this->bubbleMenuTools = $tools;

        return $this;
    }

    public function getBubbleMenuTools(): array
    {
        if ($this->bubbleMenuTools) {
            return $this->evaluate($this->bubbleMenuTools);
        } elseif ($this->profile !== 'none') {
            return config('filament-tiptap-editor.bubble_menu_tools');
        }

        return [];
    }

    public function tippyPlacement(string | TippyPlacement | Closure $placement): static
    {
        $this->tippyPlacement = $placement;

        return $this;
    }

    public function getTippyPlacement(): string | TippyPlacement
    {
        return $this->evaluate($this->tippyPlacement);
    }

    public function floatingMenuTools(array | Closure $tools): static
    {
        $this->floatingMenuTools = $tools;

        return $this;
    }

    public function getFloatingMenuTools(): array
    {
        if ($this->floatingMenuTools) {
            return $this->evaluate($this->floatingMenuTools);
        } elseif ($this->profile !== 'none') {
            return config('filament-tiptap-editor.floating_menu_tools');
        }

        return [];
    }

    public function isFloatingMenusDisabled(): bool
    {
        return $this->evaluate($this->shouldShowFloatingMenus) ?? config('filament-tiptap-editor.disable_floating_menus');
    }

    public function isBubbleMenusDisabled(): bool
    {
        return $this->evaluate($this->shouldShowBubbleMenus) ?? config('filament-tiptap-editor.disable_bubble_menus');
    }

    public function isToolbarMenusDisabled(): bool
    {
        return $this->evaluate($this->shouldShowToolbarMenus) ?? config('filament-tiptap-editor.disable_toolbar_menus');
    }
}
