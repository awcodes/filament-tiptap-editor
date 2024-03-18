<?php

namespace FilamentTiptapEditor\Extensions\Nodes;

use Tiptap\Core\Node;
use Tiptap\Utils\HTML;

class ListItem extends Node
{
    public static $name = 'listItem';

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
                'tag' => 'li',
            ],
        ];
    }

    public function renderHTML($node, $HTMLAttributes = []): array
    {
        return ['li', HTML::mergeAttributes($this->options['HTMLAttributes'], $HTMLAttributes), 0];
    }

    public static function wrapper($DOMNode)
    {
        if ($DOMNode->childNodes->length >= 1 && $DOMNode->childNodes[0]->nodeName === 'p') {
            return;
        }

        return [
            'type' => 'paragraph',
        ];
    }
}
