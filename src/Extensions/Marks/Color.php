<?php

namespace FilamentTiptapEditor\Extensions\Marks;

use Tiptap\Core\Mark;
use Tiptap\Utils\HTML;

class Color extends Mark
{
    public static $name = 'color';

    public function addOptions(): array
    {
        return [
            'HTMLAttributes' => [],
        ];
    }

    public function parseHTML(): array
    {
        return [
            [
                'style' => 'color',
            ],
        ];
    }

    public function renderHTML($mark, $HTMLAttributes = []): array
    {
        return [
            'span',
            HTML::mergeAttributes($this->options['HTMLAttributes'], $HTMLAttributes),
            0,
        ];
    }
}
