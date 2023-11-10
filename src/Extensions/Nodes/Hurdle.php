<?php

namespace FilamentTiptapEditor\Extensions\Nodes;

use Tiptap\Core\Node;
use Tiptap\Utils\HTML;

class Hurdle extends Node
{
    public static $name = 'hurdle';

    public function addOptions(): array
    {
        return [
            'colors' => [
                'gray_light',
                'gray',
                'gray_dark',
                'primary',
                'secondary',
                'tertiary',
                'accent',
            ],
            'HTMLAttributes' => [
                'class' => 'filament-tiptap-hurdle',
            ],
        ];
    }

    public function addAttributes(): array
    {
        return [
            'color' => [
                'default' => 'gray',
                'parseHTML' => function ($DOMNode) {
                    return $DOMNode->getAttribute('data-color') ?: null;
                },
                'renderHTML' => function ($attributes) {
                    return [
                        'data-color' => $attributes->color ?? null,
                    ];
                },
            ],
        ];
    }

    public function parseHTML(): array
    {
        return [
            [
                'tag' => 'div',
                'getAttrs' => function ($DOMNode) {
                    return str_contains($DOMNode->getAttribute('class'), 'filament-tiptap-hurdle');
                },
            ],
        ];
    }

    public function renderHTML($node, $HTMLAttributes = []): array
    {
        return [
            'div',
            HTML::mergeAttributes($this->options['HTMLAttributes'], $HTMLAttributes),
            0,
        ];
    }
}
