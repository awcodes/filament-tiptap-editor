<?php

namespace FilamentTiptapEditor\Extensions\Nodes;

use Tiptap\Core\Node;
use Tiptap\Utils\HTML;

class Vimeo extends Node
{
    public static $name = 'vimeo';

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
            'autoplay' => [
                'default' => 0,
            ],
            'loop' => [
                'default' => 0,
            ],
            'title' => [
                'default' => 0,
            ],
            'byline' => [
                'default' => 0,
            ],
            'portrait' => [
                'default' => 0,
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
                'tag' => 'div[data-vimeo-video] iframe',
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
            'autoplay' => $node->attrs->autoplay,
            'loop' => $node->attrs->loop,
            'title' => $node->attrs->title,
            'byline' => $node->attrs->byline,
            'portrait' => $node->attrs->portrait,
        ];

        return "https://player.vimeo.com/video/{$videoId}?" . http_build_query($query);
    }

}