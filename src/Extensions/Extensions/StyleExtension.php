<?php

namespace FilamentTiptapEditor\Extensions\Extensions;

use Tiptap\Core\Extension;

class StyleExtension extends Extension
{
    public static $name = 'styleExtension';

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
                    'code',
                    'codeBlock',
                ],
                'attributes' => [
                    'style' => [
                        'default' => null,
                        'parseHTML' => function ($DOMNode) {
                            return $DOMNode->hasAttribute('style') ? $DOMNode->getAttribute('style') : null;
                        },
                        'renderHTML' => function ($attributes) {
                            if (! property_exists($attributes, 'style')) {
                                return null;
                            }

                            return [
                                'style' => $attributes->style,
                            ];
                        },
                    ],
                ],
            ],
        ];
    }
}
