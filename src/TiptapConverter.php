<?php

namespace FilamentTiptapEditor;

use FilamentTiptapEditor\Extensions\Extensions;
use FilamentTiptapEditor\Extensions\Marks;
use FilamentTiptapEditor\Extensions\Nodes;
use Tiptap\Editor;
use Tiptap\Extensions\StarterKit;
use Tiptap\Marks\Highlight;
use Tiptap\Marks\Subscript;
use Tiptap\Marks\Superscript;
use Tiptap\Marks\TextStyle;
use Tiptap\Marks\Underline;
use Tiptap\Nodes\CodeBlockHighlight;
use Tiptap\Nodes\Table;
use Tiptap\Nodes\TableCell;
use Tiptap\Nodes\TableHeader;
use Tiptap\Nodes\TableRow;

class TiptapConverter
{
    protected Editor $editor;

    protected ?array $blocks = null;

    public function getEditor(): Editor
    {
        return $this->editor ??= new Editor([
            'extensions' => $this->getExtensions(),
        ]);
    }

    public function blocks(array $blocks): static
    {
        $this->blocks = $blocks;

        return $this;
    }

    public function getExtensions(): array
    {
        $customExtensions = collect(config('filament-tiptap-editor.extensions', []))
            ->transform(function ($ext) {
                return new $ext['parser'];
            })->toArray();

        return [
            new StarterKit([
                'paragraph' => false,
            ]),
            new TextStyle(),
            new Extensions\TextAlign([
                'types' => ['heading', 'paragraph'],
            ]),
            new Extensions\Color(),
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
            new Nodes\GridBuilder(),
            new Nodes\GridBuilderColumn(),
            new Nodes\MergeTag(),
            new Nodes\Vimeo(),
            new Nodes\YouTube(),
            new Nodes\Video(),
            new Nodes\TiptapBlock(['blocks' => $this->blocks]),
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
            ...$customExtensions,
        ];
    }

    public function asHTML(string | array $content): string
    {
        return $this->getEditor()->setContent($content)->getHTML();
    }

    public function asJSON(string | array $content, bool $decoded = false): string | array
    {
        $content = $this->getEditor()->setContent($content)->getJSON();

        return $decoded ? json_decode($content, true) : $content;
    }

    public function asText(string | array $content): string
    {
        return $this->getEditor()->setContent($content)->getText();
    }
}
