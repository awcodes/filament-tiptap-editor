<?php

namespace FilamentTiptapEditor\Extensions\Nodes;

use Tiptap\Nodes\Image as BaseImage;

class Image extends BaseImage
{
    public function addAttributes(): array
    {
        return [
            'src' => [
                'default' => null,
            ],
            'alt' => [
                'default' => null,
            ],
            'title' => [
                'default' => null,
            ],
            'width' => [
                'default' => null,
            ],
            'height' => [
                'default' => null,
            ],
            'lazy' => [
                'default' => false,
                'parseHTML' => function ($DOMNode) {
                    return $DOMNode->hasAttribute('loading') && $DOMNode->getAttribute('loading') === 'lazy';
                },
                'renderHTML' => function ($attributes) {
                    return $attributes->lazy
                        ? ['loading' => 'lazy']
                        : null;
                },
            ],
        ];
    }
}
