<?php

namespace FilamentTiptapEditor\Extensions\Marks;

use Tiptap\Marks\Link as BaseLink;

class Link extends BaseLink
{
    public function addOptions(): array
    {
        return [
            'openOnClick' => true,
            'linkOnPaste' => true,
            'autoLink' => true,
            'protocols' => [],
            'HTMLAttributes' => [],
            'validate' => 'undefined',
        ];
    }

    public function addAttributes(): array
    {
        return [
            'href' => [
                'default' => null,
            ],
            'target' => [
                'default' => $this->options['HTMLAttributes']['target'] ?? null,
            ],
            'hreflang' => [
                'default' => null,
            ],
            'rel' => [
                'default' => null,
            ],
            'class' => [
                'default' => null,
            ],
            'as_button' => [
                'default' => null,
            ],
            'button_theme' => [
                'default' => '',
            ],
        ];
    }
}