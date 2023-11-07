<?php

namespace FilamentTiptapEditor\Extensions\Nodes;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Js;
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
            'settings' => [
                'default' => null,
                'parseHTML' => function ($DOMNode) {
                    return $DOMNode->getAttribute('data-settings') ?: null;
                },
                'renderHTML' => function ($attributes) {
                    return [
                        'data-settings' => $attributes->settings ?? null,
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
//        View::make($attributes->settings['type'], ['data' => $attributes->settings['data']])
        return [
            'tiptap-block',
            HTML::mergeAttributes($HTMLAttributes),
            0,
        ];
    }
}