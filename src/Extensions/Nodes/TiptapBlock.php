<?php

namespace FilamentTiptapEditor\Extensions\Nodes;

use FilamentTiptapEditor\TiptapEditor;
use Tiptap\Core\Node;
use Tiptap\Utils\HTML;

class TiptapBlock extends Node
{
    public static $name = 'tiptapBlock';

    public function addAttributes(): array
    {
        return [
            'preview' => [
                'default' => null,
                'rendered' => false,
            ],
            'type' => [
                'default' => null,
                'parseHTML' => function ($DOMNode) {
                    return $DOMNode->getAttribute('data-type');
                },
                'renderHTML' => function ($attributes) {
                    return [
                        'data-type' => $attributes->type
                    ];
                },
            ],
            'data' => [
                'default' => null,
                'parseHTML' => function ($DOMNode) {
                    return json_decode($DOMNode->getAttribute('data-data'), true);
                },
                'renderHTML' => function ($attributes) {
                    return [
                        'data-data' => json_encode($attributes->data, true)
                    ];
                },
            ],
        ];
    }

    public function parseHTML(): array
    {
        return [
            [
                'tag' => 'tiptap-block',
            ]
        ];
    }

    public function renderHTML($node, $HTMLAttributes = []): array
    {
        $blocks = TiptapEditor::make('get_blocks')->getBlocks();
        $view = view($blocks[$node->attrs->type]->rendered, (array) $node->attrs->data)->render();

        return [
            'tiptap-block',
            HTML::mergeAttributes($HTMLAttributes),
            'content' => $view,
        ];
    }
}