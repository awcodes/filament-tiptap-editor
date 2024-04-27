<?php

namespace FilamentTiptapEditor\Extensions\Nodes;

use Tiptap\Core\Node;
use Tiptap\Utils\HTML;

class DetailsContent extends Node
{
    public static $name = 'detailsContent';

    public static $priority = 50;

    public function addOptions(): array
    {
        return [
            'HTMLAttributes' => [
                'data-type' => 'details-content',
            ],
        ];
    }

    public function parseHTML(): array
    {
        return [
            [
                'tag' => 'div[data-type]',
                'getAttrs' => function ($value) {
                    return (bool) $value == 'details-content';
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
