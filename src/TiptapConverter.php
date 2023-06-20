<?php

namespace FilamentTiptapEditor;

use Tiptap\Editor;
use Tiptap\Extensions\StarterKit;
use Tiptap\Marks\Highlight;
use Tiptap\Marks\Subscript;
use Tiptap\Marks\Superscript;
use Tiptap\Marks\Underline;
use Tiptap\Nodes\CodeBlockHighlight;
use Tiptap\Nodes\Table;
use Tiptap\Nodes\TableCell;
use Tiptap\Nodes\TableHeader;
use Tiptap\Nodes\TableRow;
use FilamentTiptapEditor\Extensions\Nodes;
use FilamentTiptapEditor\Extensions\Marks;
use FilamentTiptapEditor\Extensions\Extensions;

class TiptapConverter
{
    protected Editor $editor;

    final public function __construct()
    {
        $this->editor = new Editor([
            'extensions' => $this->getExtensions(),
        ]);
    }

    public function getExtensions(): array
    {
        return [
            new StarterKit([
                'paragraph' => false,
            ]),
            new Extensions\TextAlign([
                'types' => ['heading', 'paragraph'],
            ]),
            new CodeBlockHighlight(),
            new Nodes\Paragraph(),
            new Nodes\Lead(),
            new Nodes\Image(),
            new Nodes\CheckedList(),
            new Nodes\Details(),
            new Nodes\DetailsSummary(),
            new Nodes\DetailsContent(),
            new Nodes\Grid(),
            new Nodes\GridColumn(),
            new Nodes\Vimeo(),
            new Nodes\YouTube(),
            new Nodes\Hurdle(),
            new Table(),
            new TableHeader(),
            new TableRow(),
            new TableCell(),
            new Highlight(),
            new Underline(),
            new Superscript(),
            new Subscript(),
            new Marks\Link(),
            new Marks\Small(),
        ];
    }

    public function asHTML(string|array $content): string
    {
        return $this->editor->setContent($content)->getHTML();
    }

    public function asJSON(string|array $content): string
    {
        return $this->editor->setContent($content)->getJSON();
    }

    public function asText(string|array $content): string
    {
        return $this->editor->setContent($content)->getText();
    }
}