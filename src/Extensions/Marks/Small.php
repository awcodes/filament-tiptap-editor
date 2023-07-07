<?php

namespace FilamentTiptapEditor\Extensions\Marks;

use Tiptap\Core\Mark;
use Tiptap\Utils\HTML;

class Small extends Mark
{
    public static $name = 'small';

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
                'tag' => 'small',
            ],
        ];
    }

    public function renderHTML($mark, $HTMLAttributes = []): array
    {
        return [
            'small',
            HTML::mergeAttributes($this->options['HTMLAttributes'], $HTMLAttributes),
            0,
        ];
    }
}
