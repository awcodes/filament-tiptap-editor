<?php

namespace FilamentTiptapEditor\Extensions\Nodes;

use Tiptap\Core\Node;
use Tiptap\Utils\HTML;

class Video extends Node
{
    public static $name = 'video';

    public function addOptions(): array
    {
        return [
            'inline' => false,
            'HTMLAttributes' => [
                'autoplay' => null,
                'controls' => null,
                'loop' => null,
            ],
            'allowFullscreen' => true,
            'width' => 640,
            'height' => 480,
        ];
    }

    public function addAttributes(): array
    {
        return [
            'responsive' => [
                'default' => true,
                'parseHTML' => function ($DOMNode) {
                    return str_contains($DOMNode->getAttribute('class'), 'responsive') ?? false;
                },
            ],
            'style' => [
                'default' => null,
                'parseHTML' => function ($DOMNode) {
                    return $DOMNode->firstChild->getAttribute('style');
                },
            ],
            'src' => [
                'default' => null,
                'parseHTML' => function ($DOMNode) {
                    return $DOMNode->firstChild->getAttribute('src');
                },
            ],
            'width' => [
                'default' => $this->options['width'],
                'parseHTML' => function ($DOMNode) {
                    return $DOMNode->firstChild->getAttribute('width');
                },
            ],
            'height' => [
                'default' => $this->options['height'],
                'parseHTML' => function ($DOMNode) {
                    return $DOMNode->firstChild->getAttribute('height');
                },
            ],
            'autoplay' => [
                'default' => null,
                'parseHTML' => function ($DOMNode) {
                    return $DOMNode->firstChild->getAttribute('autoplay');
                },
            ],
            'loop' => [
                'default' => null,
                'parseHTML' => function ($DOMNode) {
                    return $DOMNode->firstChild->getAttribute('loop');
                },
            ],
            'controls' => [
                'default' => null,
                'parseHTML' => function ($DOMNode) {
                    return $DOMNode->firstChild->getAttribute('controls');
                },
            ],
            'data-aspect-width' => [
                'default' => null,
                'parseHTML' => function ($DOMNode) {
                    return $DOMNode->firstChild->getAttribute('data-aspect-width');
                },
            ],
            'data-aspect-height' => [
                'default' => null,
                'parseHTML' => function ($DOMNode) {
                    return $DOMNode->firstChild->getAttribute('data-aspect-height');
                },
            ],
        ];
    }

    public function parseHTML(): array
    {
        return [
            [
                'tag' => 'div[data-native-video]',
            ],
        ];
    }

    public function renderHTML($node, $HTMLAttributes = []): array
    {
        return [
            'div',
            [
                'data-native-video' => true,
                'class' => $node->attrs->responsive ? 'responsive' : null,
            ],
            [
                'video',
                HTML::mergeAttributes($this->options['HTMLAttributes'], [
                    'src' => $node->attrs->src,
                    'width' => $node->attrs->width,
                    'height' => $node->attrs->height,
                    'autoplay' => $node->attrs->autoplay ? 'true' : null,
                    'loop' => $node->attrs->loop ? 'true' : null,
                    'controls' => $node->attrs->controls ? 'true' : null,
                    'style' => $node->attrs->style ?? null,
                ]),
            ],
        ];
    }
}
