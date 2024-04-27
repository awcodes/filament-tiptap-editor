<?php

namespace FilamentTiptapEditor\Extensions\Nodes;

use Tiptap\Core\Node;
use Tiptap\Utils\HTML;

class MergeTag extends Node
{
    public static $name = 'mergeTag';

    public function addOptions(): array
    {
        return [
            'inline' => true,
        ];
    }

    public function addAttributes(): array
    {
        return [
            'id' => [
                'default' => null,
                'parseHTML' => function ($DOMNode) {
                    return $DOMNode->getAttribute('data-id');
                },
                'renderHTML' => function ($attributes) {
                    if (! $attributes->id) {
                        return [];
                    }

                    return [
                        'data-id' => $attributes->id,
                    ];
                },
            ],
        ];
    }

    public function parseHTML(): array
    {
        return [
            [
                'tag' => "span[data-type='" . static::$name . "']",
            ],
        ];
    }

    public function renderHTML($node, $HTMLAttributes = []): array
    {
        return [
            'span',
            HTML::mergeAttributes(['data-type' => static::$name], $HTMLAttributes),
            0,
        ];
    }
}
