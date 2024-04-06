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

    protected bool $tableOfContents = false;

    protected array $mergeTagsMap = [];

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
            new Extensions\StyleExtension(),
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

    public function mergeTagsMap(array $mergeTagsMap): static
    {
        $this->mergeTagsMap = $mergeTagsMap;

        return $this;
    }

    public function asHTML(string | array $content, bool $toc = false, int $maxDepth = 3): string
    {
        $editor = $this->getEditor()->setContent($content);

        if ($toc) {
            $this->parseHeadings($editor, $maxDepth);
        }

        if (filled($this->mergeTagsMap)) {
            $this->parseMergeTags($editor);
        }

        return $editor->getHTML();
    }

    public function asJSON(string | array $content, bool $decoded = false, bool $toc = false, int $maxDepth = 3): string | array
    {
        $editor = $this->getEditor()->setContent($content);

        if ($toc) {
            $this->parseHeadings($editor, $maxDepth);
        }

        if (filled($this->mergeTagsMap)) {
            $this->parseMergeTags($editor);
        }

        return $decoded ? json_decode($editor->getJSON(), true) : $editor->getJSON();
    }

    public function asText(string | array $content): string
    {
        $editor = $this->getEditor()->setContent($content);

        if (filled($this->mergeTagsMap)) {
            $this->parseMergeTags($editor);
        }

        return $editor->getText();
    }

    public function asTOC(string | array $content, int $maxDepth = 3): string
    {
        if (is_string($content)) {
            $content = $this->asJSON($content, decoded: true);
        }

        $headings = $this->parseTocHeadings($content['content'], $maxDepth);

        return $this->generateNestedTOC($headings, $headings[0]['level']);
    }

    public function parseHeadings(Editor $editor, int $maxDepth = 3): Editor
    {
        $editor->descendants(function (&$node) use ($maxDepth) {
            if ($node->type !== 'heading') {
                return;
            }

            if ($node->attrs->level > $maxDepth) {
                return;
            }

            if (! property_exists($node->attrs, 'id') || $node->attrs->id === null) {
                $node->attrs->id = str(collect($node->content)->map(function ($node) {
                    return $node?->text ?? null;
                })->implode(' '))->kebab()->toString();
            }

            array_unshift($node->content, (object) [
                'type' => 'text',
                'text' => '#',
                'marks' => [
                    [
                        'type' => 'link',
                        'attrs' => [
                            'href' => '#' . $node->attrs->id,
                        ],
                    ],
                ],
            ]);
        });

        return $editor;
    }

    public function parseTocHeadings(array $content, int $maxDepth = 3): array
    {
        $headings = [];

        foreach ($content as $node) {
            if ($node['type'] === 'heading') {
                if ($node['attrs']['level'] <= $maxDepth) {
                    $text = collect($node['content'])->map(function ($node) {
                        return $node['text'] ?? null;
                    })->implode(' ');

                    if (! isset($node['attrs']['id'])) {
                        $node['attrs']['id'] = str($text)->kebab()->toString();
                    }

                    $headings[] = [
                        'level' => $node['attrs']['level'],
                        'id' => $node['attrs']['id'],
                        'text' => $text,
                    ];
                }
            } elseif (array_key_exists('content', $content)) {
                $this->parseTocHeadings($content, $maxDepth);
            }
        }

        return $headings;
    }

    public function parseMergeTags(Editor $editor): Editor
    {
        $editor->descendants(function (&$node) {
            if ($node->type !== 'mergeTag') {
                return;
            }

            if (filled($this->mergeTagsMap)) {
                $node->content = [
                    (object) [
                        'type' => 'text',
                        'text' => $this->mergeTagsMap[$node->attrs->id] ?? null,
                    ],
                ];
            }
        });

        return $editor;
    }

    public function generateNestedTOC(array $headings, int $parentLevel = 0): string
    {
        $result = '<ul>';
        $prev = $parentLevel;

        foreach ($headings as $item) {
            $prev <= $item['level'] ?: $result .= str_repeat('</ul>', $prev - $item['level']);
            $prev >= $item['level'] ?: $result .= '<ul>';

            $result .= '<li><a href="#' . $item['id'] . '">' . $item['text'] . '</a></li>';

            $prev = $item['level'];
        }

        $result .= '</ul>';

        return $result;
    }
}
