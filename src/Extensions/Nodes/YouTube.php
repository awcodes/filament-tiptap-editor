<?php

namespace FilamentTiptapEditor\Extensions\Nodes;

use Tiptap\Core\Node;
use Tiptap\Utils\HTML;

class YouTube extends Node
{
    public static $name = 'youtube';

    public function addOptions(): array
    {
        return [
            'inline' => false,
            'HTMLAttributes' => [],
            'allowFullscreen' => true,
            'width' => 640,
            'height' => 480,
        ];
    }

    public function addAttributes(): array
    {
        return [
            'style' => [
                'default' => null,
                'parseHTML' => function ($DOMNode) {
                    return $DOMNode->getAttribute("style");
                },
            ],
            'src' => [
                'default' => null,
            ],
            'start' => [
                'default' => 0,
            ],
            'width' => [
                'default' => $this->options['width'],
                'parseHTML' => function ($DOMNode) {
                    return $DOMNode->getAttribute("width");
                },
            ],
            'height' => [
                'default' => $this->options['height'],
                'parseHTML' => function ($DOMNode) {
                    return $DOMNode->getAttribute("height");
                },
            ],
            'responsive' => [
                'default' => true,
            ],
            'aspectWidth' => [
                'default' => 16,
                'parseHTML' => function ($DOMNode) {
                    return $DOMNode->getAttribute("aspect-width");
                },
            ],
            'aspectHeight' => [
                'default' => 9,
                'parseHTML' => function ($DOMNode) {
                    return $DOMNode->getAttribute("aspect-height");
                },
            ],
        ];
    }

    public function parseHTML(): array
    {
        return [
            [
                'tag' => 'div[data-youtube-video] iframe',
            ]
        ];
    }

    public function renderHTML($node, $HTMLAttributes = []): array
    {
        return [
            'div',
            [
                'data-vimeo-video' => true,
                'class' => $node->attrs->responsive ? 'responsive' : null
            ],
            [
                'iframe',
                HTML::mergeAttributes($this->options['HTMLAttributes'], [
                    'src' => $this->buildSrc($node),
                    'width' => $this->options['width'],
                    'height' => $this->options['height'],
                    'allowfullscreen' => true,
                    'allow' => 'autoplay; fullscreen; picture-in-picture',
                    'style' => $node->attrs->responsive
                        ? "aspect-ratio:{$node->attrs->aspectWidth}/{$node->attrs->aspectHeight}; width: 100%; height: auto;"
                        : null,
                    'aspectWidth' => $node->attrs->responsive
                        ? $node->attrs->aspectWidth
                        : $node->attrs->width,
                    'aspectHeight' => $node->attrs->responsive
                        ? $node->attrs->aspectHeight
                        : $node->attrs->height,
                ]),
                0
            ]
        ];
    }

    public function buildSrc($node): string
    {
        $videoId = basename($node->attrs->src);

        $query = [
            'start' => $node->attrs->start,
            'controls' => $node->attrs?->controls ?? null,
        ];

        return "https://www.youtube.com/embed/{$videoId}?" . http_build_query($query);
    }

}