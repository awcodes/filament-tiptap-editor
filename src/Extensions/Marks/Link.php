<?php

namespace FilamentTiptapEditor\Extensions\Marks;

use Tiptap\Marks\Link as BaseLink;

class Link extends BaseLink
{
    public function addOptions(): array
    {
        return [
            'openOnClick' => true,
            'linkOnPaste' => true,
            'autoLink' => true,
            'protocols' => [],
            'HTMLAttributes' => [],
            'validate' => 'undefined',
        ];
    }

    public function addAttributes(): array
    {
        return [
            'href' => [
                'default' => null,
            ],
            'id' => [
                'default' => null,
            ],
            'target' => [
                'default' => $this->options['HTMLAttributes']['target'] ?? null,
            ],
            'hreflang' => [
                'default' => null,
            ],
            'rel' => [
                'default' => null,
            ],
            'referrerpolicy' => [
                'default' => null,
            ],
            'class' => [
                'default' => null,
            ],
            'as_button' => [
                'default' => null,
                'parseHTML' => function ($DOMNode) {
                    if ($DOMNode->getAttribute('as_button') === 'true') {
                        return true;
                    }

                    return $DOMNode->getAttribute('data-as-button') ?: null;
                },
                'renderHTML' => function ($attributes) {
                    if (! property_exists($attributes, 'as_button')) {
                        return null;
                    }

                    return [
                        'data-as-button' => $attributes->as_button ?? null,
                    ];
                },
            ],
            'button_theme' => [
                'default' => null,
                'parseHTML' => function ($DOMNode) {
                    if ($theme = $DOMNode->getAttribute('data-as-button-theme')) {
                        return $theme;
                    }

                    if ($theme = $DOMNode->getAttribute('button_theme')) {
                        return $theme;
                    }

                    return null;
                },
                'renderHTML' => function ($attributes) {
                    if (! property_exists($attributes, 'button_theme') || strlen($attributes->button_theme ?? '') === 0) {
                        return null;
                    }

                    return [
                        'data-as-button-theme' => $attributes->button_theme,
                    ];
                },
            ],
        ];
    }
}
