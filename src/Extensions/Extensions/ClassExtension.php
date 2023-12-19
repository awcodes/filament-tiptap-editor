<?php

namespace FilamentTiptapEditor\Extensions\Extensions;

use Tiptap\Core\Extension;
use Tiptap\Utils\InlineStyle;

class ClassExtension extends Extension
{
    public static $name = 'classExtension';

    public function addGlobalAttributes(): array
    {
        return [
            [
                'types' => [
                    'heading',
                    'paragraph',
                    'link',
                    'image',
                    'listItem',
                    'bulletList',
                    'orderedList',
                    'table',
                    'tableHeader',
                    'tableRow',
                    'tableCell',
                    'textStyle',
                ],
                'attributes' => [
                    'class' => [
                        'default' => null,
                        'parseHTML' => function ($DOMNode) {
                            return InlineStyle::getAttribute($DOMNode, 'class') ?? false;
                        },
                        'renderHTML' => function ($attributes) {
                            if (! property_exists($attributes, 'class')) {
                                return null;
                            }

                            return [
                                'class' => $attributes->class,
                            ];
                        },
                    ],
                ],
            ],
        ];
    }
}
