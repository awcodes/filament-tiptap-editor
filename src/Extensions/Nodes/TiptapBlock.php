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
                        'data-type' => $attributes->type,
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
                        'data-data' => json_encode($attributes->data, true),
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
            ],
        ];
    }

    public function getBlocks(): array
    {
        $customBlocks = $this->options['blocks'] ?? null;

        if (blank($customBlocks)) {
            return TiptapEditor::make('get_blocks')->getBlocks();
        }

        return collect($customBlocks)
            ->mapWithKeys(function (string $block): array {
                $blockInstance = app($block);

                return [$blockInstance->getIdentifier() => $blockInstance];
            })
            ->all();
    }

    public function renderHTML($node, $HTMLAttributes = []): array
    {
        $blocks = $this->getBlocks();

        $view = '';

        $block = $blocks[$node->attrs->type] ?? null;
        if ($block) {
            $view = $block->getRendered(json_decode(json_encode($node->attrs->data), associative: true));
        }

        return [
            'tiptap-block',
            HTML::mergeAttributes($HTMLAttributes),
            'content' => $view,
        ];
    }
}
