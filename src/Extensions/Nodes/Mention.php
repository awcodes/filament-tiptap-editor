<?php

declare(strict_types=1);

namespace FilamentTiptapEditor\Extensions\Nodes;

use Tiptap\Core\Node;

class Mention extends Node
{
    public static $name = 'mention';

    public function renderText($node)
    {
        return $node->attrs->label;
    }

    public function renderHTML($node, $HTMLAttributes = []): array
    {
        $dataAttributes = [
            'data-mention-id' => $node->attrs->id,
        ];

        if ($node->attrs->data) {
            $dataAttributes['data-mention-data'] = json_encode($node->attrs->data);
        }

        if (property_exists($node->attrs, 'href') && $node->attrs->href) {
            return [
                'a',
                [
                    'href' => $node->attrs->href,
                    'target' => $node->attrs->target,
                    ...$dataAttributes,
                ],
            ];
        }

        return [
            'span',
            [
                'data-mention-id' => $node->attrs->id,
                ...$dataAttributes,
            ],
            0,
        ];
    }
}
