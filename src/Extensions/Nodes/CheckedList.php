<?php

namespace FilamentTiptapEditor\Extensions\Nodes;

use Tiptap\Core\Node;
use Tiptap\Utils\HTML;

class CheckedList extends Node
{
    public static $name = 'checkedList';

    public static $priority = 200;

    public function addOptions(): array
    {
        return [
            'HTMLAttributes' => [
                'class' => 'checked-list',
            ],
        ];
    }

    public function parseHTML(): array
    {
        return [
            [
                'tag' => 'ul',
                'getAttrs' => function ($DOMNode) {
                    return str_contains($DOMNode->getAttribute('class'), 'checked-list');
                },
            ],
        ];
    }

    public function renderHTML($node, $HTMLAttributes = []): array
    {
        return ['ul', HTML::mergeAttributes($this->options['HTMLAttributes'], $HTMLAttributes), 0];
    }
}
