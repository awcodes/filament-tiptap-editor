<?php

namespace FilamentTiptapEditor\Extensions\Nodes;

use Tiptap\Core\Node;
use Tiptap\Utils\HTML;

class Grid extends Node
{
    public static $name = 'grid';

    public function addOptions(): array
    {
        return [
            'HTMLAttributes' => [
                'class' => 'filament-tiptap-grid',
            ],
        ];
    }

    public function addAttributes(): array
    {
        return [
            'type' => [
                'default' => 'responsive',
                'parseHTML' => function ($DOMNode) {
                    return $DOMNode->getAttribute('type');
                },
            ],
            'cols' => [
                'default' => '2',
                'parseHTML' => function ($DOMNode) {
                    return $DOMNode->getAttribute('cols');
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
                    return str_contains($DOMNode->getAttribute('class'), 'filament-tiptap-grid') &&
                        ! str_contains($DOMNode->getAttribute('class'), '-builder') &&
                        ! str_contains($DOMNode->getAttribute('class'), '__column');
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
