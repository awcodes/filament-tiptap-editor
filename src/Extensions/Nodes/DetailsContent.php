<?php

namespace FilamentTiptapEditor\Extensions\Nodes;

use Tiptap\Core\Node;
use Tiptap\Utils\HTML;

class DetailsContent extends Node
{
    public static $name = 'detailsContent';

    public function addOptions(): array
    {
        return [
            'HTMLAttributes' => [],
        ];
    }

    public function parseHTML(): array
    {
        return [
            [
                'tag' => 'div[data-type="details-content"]',
            ]
        ];
    }

    public function renderHTML($node, $HTMLAttributes = []): array
    {
        return [
            'div',
            HTML::mergeAttributes($this->options['HTMLAttributes'], $HTMLAttributes, ['data-type' => 'details-content']),
            0
        ];
    }
}