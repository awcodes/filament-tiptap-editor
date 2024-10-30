<?php

namespace FilamentTiptapEditor\Extensions\Nodes;

use Tiptap\Core\Node;

class Iframe extends Node
{
    public static $name = 'iframe';

    public function addOptions()
    {
        return [
            'HTMLAttributes' => [],
        ];
    }

    public function parseHTML()
    {
        return [
            [
                'tag' => 'iframe',
            ],
        ];
    }

    public function addAttributes()
    {
        return [
            'id' => [
                'parseHTML' => fn ($DOMNode) => $DOMNode->getAttribute('id') ?: null,
                'renderHTML' => fn ($attributes) => ($attributes->id ?? null) ? ['id' => $attributes->id] : null,
            ],
            'src' => [
                'parseHTML' => fn ($DOMNode) => $DOMNode->getAttribute('src') ?: null,
                'renderHTML' => fn ($attributes) => ($attributes->src ?? null) ? ['src' => $attributes->src] : null,
            ],
            'frameborder' => [
                'parseHTML' => fn ($DOMNode) => (int) $DOMNode->getAttribute('frameborder') ?: null,
                'renderHTML' => fn ($attributes) => ($attributes->frameborder ?? null) ? ['frameborder' => $attributes->frameborder] : ['frameborder' => 0],
            ],
            'scrolling' => [
                'parseHTML' => fn ($DOMNode) => $DOMNode->getAttribute('scrolling') ?: null,
                'renderHTML' => fn ($attributes) => ($attributes->scrolling ?? null) ? ['scrolling' => $attributes->scrolling] : null,
            ],
            'allowfullscreen' => [
                'parseHTML' => fn ($DOMNode) => $DOMNode->getAttribute('allowfullscreen') ?: null,
                'renderHTML' => fn ($attributes) => ($attributes->allowfullscreen ?? null) ? ['allowfullscreen' => $attributes->allowfullscreen] : null,
            ],
            'width' => [
                'parseHTML' => fn ($DOMNode) => $DOMNode->getAttribute('width') ?: null,
                'renderHTML' => fn ($attributes) => ($attributes->width ?? null) ? ['width' => $attributes->width] : ['width' => '100%'],
            ],
            'height' => [
                'parseHTML' => fn ($DOMNode) => $DOMNode->getAttribute('height') ?: null,
                'renderHTML' => fn ($attributes) => ($attributes->height ?? null) ? ['height' => $attributes->height] : null,
            ],
            'style' => [
                'parseHTML' => fn ($DOMNode) => $DOMNode->getAttribute('style') ?: null,
                'renderHTML' => fn ($attributes) => ($attributes->style ?? null) ? ['style' => $attributes->style] : null,
            ],
        ];
    }
}
