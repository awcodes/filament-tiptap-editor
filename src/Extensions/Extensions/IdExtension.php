<?php

namespace FilamentTiptapEditor\Extensions\Extensions;

use Tiptap\Core\Extension;
use Tiptap\Utils\InlineStyle;

class IdExtension extends Extension
{
    public static $name = 'idExtension';

    public function addGlobalAttributes(): array
    {
        return [
            [
                'types' => [
                    'heading',
                    'link',
                ],
                'attributes' => [
                    'id' => [
                        'default' => null,
                        'parseHTML' => function ($DOMNode) {
                            return InlineStyle::getAttribute($DOMNode, 'id');
                        },
                        'renderHTML' => function ($attributes) {
                            if (! property_exists($attributes, 'id')) {
                                return null;
                            }

                            return [
                                'id' => $attributes->id,
                            ];
                        },
                    ],
                ],
            ],
        ];
    }
}
