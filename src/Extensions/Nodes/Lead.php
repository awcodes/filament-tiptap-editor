<?php

namespace FilamentTiptapEditor\Extensions\Nodes;

use Tiptap\Core\Node;
use Tiptap\Utils\HTML;

class Lead extends Node
{
    public static $name = 'lead';

    public function addOptions(): array
    {
        return [
            'HTMLAttributes' => [
                'class' => 'lead',
            ],
        ];
    }

    public function parseHTML(): array
    {
        return [
            [
                'tag' => 'div',
                'getAttrs' => function ($DOMNode) {
                    return str_contains($DOMNode->getAttribute('class'), 'lead');
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
