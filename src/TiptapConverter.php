<?php

namespace FilamentTiptapEditor;

use Tiptap\Editor;
use Tiptap\Extensions\StarterKit;
use Tiptap\Marks;
use Tiptap\Nodes;

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
            new Marks\TextStyle,
            new Extensions\Extensions\TextAlign([
                'types' => ['heading', 'paragraph'],
            ]),
            new Extensions\Extensions\ClassExtension,
            new Extensions\Extensions\IdExtension,
            new Extensions\Extensions\StyleExtension,
            new Extensions\Extensions\Color,
            new Nodes\CodeBlockHighlight,
            new Extensions\Nodes\ListItem,
            new Extensions\Nodes\Mention,
            new Extensions\Nodes\Lead,
            new Extensions\Nodes\Image,
            new Extensions\Nodes\CheckedList,
            new Extensions\Nodes\Details,
            new Extensions\Nodes\DetailsSummary,
            new Extensions\Nodes\DetailsContent,
            new Extensions\Nodes\Grid,
            new Extensions\Nodes\GridColumn,
            new Extensions\Nodes\GridBuilder,
            new Extensions\Nodes\GridBuilderColumn,
            new Extensions\Nodes\MergeTag,
            new Extensions\Nodes\Vimeo,
            new Extensions\Nodes\YouTube,
            new Extensions\Nodes\Video,
            new Extensions\Nodes\TiptapBlock(['blocks' => $this->blocks]),
            new Extensions\Nodes\Hurdle,
            new Nodes\Table,
            new Nodes\TableHeader,
            new Nodes\TableRow,
            new Nodes\TableCell,
            new Marks\Highlight,
            new Marks\Underline,
            new Marks\Superscript,
            new Marks\Subscript,
            new Extensions\Marks\Link,
            new Extensions\Marks\Small,
            ...$customExtensions,
        ];
    }

    public function asHTML(string|array $content, bool $toc = false, int $maxDepth = 3): string
    {
        $editor = $this->getEditor()->setContent($content);

        if ($toc) {
            $this->parseHeadings($editor, $maxDepth);
        }

        return $editor->getHTML();
    }

    public function asJSON(string|array $content, bool $decoded = false, bool $toc = false, int $maxDepth = 3): string|array
    {
        $editor = $this->getEditor()->setContent($content);

        if ($toc) {
            $this->parseHeadings($editor, $maxDepth);
        }

        return $decoded ? json_decode($editor->getJSON(), true) : $editor->getJSON();
    }

    public function asText(string|array $content): string
    {
        return $this->getEditor()->setContent($content)->getText();
    }

    public function asTOC(string|array $content, int $maxDepth = 3): string
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

            if (!property_exists($node->attrs, 'id') || $node->attrs->id === null) {
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

                    if (!isset($node['attrs']['id'])) {
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
