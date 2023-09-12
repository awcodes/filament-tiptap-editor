<?php

namespace FilamentTiptapEditor\Concerns;

use Closure;

trait InteractsWithMenus
{
    protected array | Closure | null $floatingMenuTools = null;

    protected ?bool $shouldShowBubbleMenus = null;

    protected ?bool $shouldShowFloatingMenus = null;

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
}
