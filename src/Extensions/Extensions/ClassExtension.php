<?php

namespace FilamentTiptapEditor\Extensions\Extensions;

use Tiptap\Core\Extension;

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
                    'code',
                    'codeBlock',
                ],
                'attributes' => [
                    'class' => [
                        'default' => null,
                        'parseHTML' => function ($DOMNode) {
                            return $DOMNode->hasAttribute('class') ? $DOMNode->getAttribute('class') : null;
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
