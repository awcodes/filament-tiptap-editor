<?php

namespace FilamentTiptapEditor\Extensions\Extensions;

use Tiptap\Core\Extension;
use Tiptap\Utils\InlineStyle;

class Color extends Extension
{
    public static $name = 'color';

    public function addOptions(): array
    {
        return [
            'types' => ['textStyle'],
        ];
    }

    public function addGlobalAttributes(): array
    {
        return [
            [
                'types' => $this->options['types'],
                'attributes' => [
                    'color' => [
                        'default' => null,
                        'parseHTML' => function ($DOMNode) {
                            return InlineStyle::getAttribute($DOMNode, 'color') ?? false;
                        },
                        'renderHTML' => function ($attributes) {
                            if (
                                (property_exists($attributes, 'style') && str_contains($attributes->style, 'color')) ||
                                (! property_exists($attributes, 'color') || ! $attributes->color)
                            ) {
                                return null;
                            }

                            return [
                                'style' => "color: {$attributes->color}",
                            ];
                        },
                    ],
                ],
            ],
        ];
    }
}
