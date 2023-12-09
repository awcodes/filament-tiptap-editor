<?php

namespace FilamentTiptapEditor\Extensions\Nodes;

use Tiptap\Core\Node;
use Tiptap\Utils\HTML;

class GridBuilderColumn extends Node
{
    public static $name = 'gridBuilderColumn';

    public function addOptions(): array
    {
        return [
            'HTMLAttributes' => [
                'class' => 'filament-tiptap-grid-builder__column',
            ],
        ];
    }

    public function addAttributes(): array
    {
        return [
            'data-col-span' => [
                'default' => '1',
                'parseHTML' => function ($DOMNode) {
                    return $DOMNode->getAttribute('data-col-span');
                },
                'renderHTML' => function ($attributes) {
                    $attributes = (array) $attributes;

                    return [
                        'data-col-span' => $attributes['data-col-span'],
                        'style' => 'grid-column: span ' . $attributes['data-col-span'] . ';',
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
                    return str_contains($DOMNode->getAttribute('class'), 'filament-tiptap-grid-builder__column');
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
