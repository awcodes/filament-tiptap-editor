<?php

namespace FilamentTiptapEditor;

use FilamentTiptapEditor\Extensions\Extensions;
use FilamentTiptapEditor\Extensions\Marks;
use FilamentTiptapEditor\Extensions\Nodes;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
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

    protected bool $tableOfContents = false;

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
                'listItem' => false,
            ]),
            new TextStyle(),
            new Extensions\TextAlign([
                'types' => ['heading', 'paragraph'],
            ]),
            new Extensions\ClassExtension(),
            new Extensions\IdExtension(),
            new Extensions\Color(),
            new CodeBlockHighlight(),
            new Nodes\ListItem(),
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

    public function asHTML(string | array $content, bool $toc = false): string
    {
        $editor = $this->getEditor()->setContent($content);

        if ($toc) {
            $this->parseHeadings($editor);
        }

        return $editor->getHTML();
    }

    public function asJSON(string | array $content, bool $decoded = false, bool $toc = false): string | array
    {
        $editor = $this->getEditor()->setContent($content);

        if ($toc) {
            $this->parseHeadings($editor);
        }

        return $decoded ? json_decode($editor->getJSON(), true) : $editor->getJSON();
    }

    public function asText(string | array $content): string
    {
        return $this->getEditor()->setContent($content)->getText();
    }

    public function asTOC(string | array $content): string
    {
        if (is_string($content)) {
            $content = $this->asJSON($content, decoded: true);
        }

        $headings = $this->parseTocHeadings($content['content']);

        return $this->generateNestedTOC($headings, $headings[0]['level']);
    }

    public function parseHeadings(Editor $editor): Editor
    {
        $editor->descendants(function (&$node) {
            if ($node->type !== 'heading') {
                return;
            }

            if (! property_exists($node->attrs, 'id')) {
                $node->attrs->id = str(collect($node->content)->map(function ($node) {
                    return $node->text;
                })->implode(' '))->kebab()->toString();
            }

            array_unshift($node->content, (object) [
                "type" => "text",
                "text" => "#",
                "marks" => [
                    [
                        "type" => "link",
                        "attrs" => [
                            "href" => "#" . $node->attrs->id,
                        ]
                    ]
                ]
            ]);
        });

        return $editor;
    }

    public function generateNestedTOC($heading, $parentLevel = 0): string
    {
        $result = '<ul>';

        foreach ($heading as $item) {
            if ($item['level'] == $parentLevel) {
                $result .= '<li>';
                $result .= '<a href="#' . $item['id'] . '">' . $item['text'] . '</a>';

                $result .= $this->generateNestedTOC($heading, $item['level'] + 1);

                $result .= '</li>';
            }
        }

        $result .= '</ul>';

        return Str::of($result)->replace('<ul></ul>', '')->toString();
    }

    public function parseTocHeadings(array $content): array
    {
        $headings = [];

        foreach ($content as $node) {
            if ($node['type'] === 'heading') {
                $text = collect($node['content'])->map(function ($node) {
                    return $node['text'];
                })->implode(' ');

                if (! isset($node['attrs']['id'])) {
                    $node['attrs']['id'] = str($text)->kebab()->toString();
                }

                $headings[] = [
                    'level' => $node['attrs']['level'],
                    'id' => $node['attrs']['id'],
                    'text' => $text,
                ];
            } elseif (array_key_exists('content', $content)) {
                $this->parseTocHeadings($content);
            }
        }

        return $headings;
    }
}
