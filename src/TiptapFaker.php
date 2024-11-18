<?php

namespace FilamentTiptapEditor;

use Faker\Factory;
use Faker\Generator;
use FilamentTiptapEditor\Facades\TiptapConverter;
use Illuminate\Support\Str;

class TiptapFaker
{
    protected Generator $faker;

    protected string $output = '';

    public static function make(): static
    {
        $static = new static;
        $static->faker = Factory::create();

        return $static;
    }

    public function heading(int | string | null $level = 2): static
    {
        $this->output .= '<h' . $level . '>' . Str::title($this->faker->words(rand(3, 8), true)) . '</h' . $level . '>';

        return $this;
    }

    public function headingWithLink(int | string | null $level = 2): static
    {
        $heading = $this->faker->words(rand(2, 3), true) . '<a href="#">' . $this->faker->words(rand(2, 3), true) . '</a>' . $this->faker->words(rand(2, 3), true);
        $this->output .= '<h' . $level . '>' . Str::title($heading) . '</h' . $level . '>';

        return $this;
    }

    public function emptyParagraph(): static
    {
        $this->output .= '<p></p>';

        return $this;
    }

    public function paragraphs(int $count = 1, bool $withRandomLinks = false): static
    {
        if ($withRandomLinks) {
            $this->output .= '<p>' . collect($this->faker->paragraphs($count))->map(function ($paragraph) {
                return $paragraph . ' <a href="' . $this->faker->url() . '">' . $this->faker->words(rand(3, 8), true) . '</a>';
            })->implode('</p><p>') . '</p>';

            return $this;
        } else {
            $this->output .= '<p>' . collect($this->faker->paragraphs($count))->implode('</p><p>') . '</p>';
        }

        return $this;
    }

    public function unorderedList(int $count = 1): static
    {
        $this->output .= '<ul><li>' . collect($this->faker->words($count))->implode('</li><li>') . '</li></ul>';

        return $this;
    }

    public function orderedList(int $count = 1): static
    {
        $this->output .= '<ol><li>' . collect($this->faker->words($count))->implode('</li><li>') . '</li></ol>';

        return $this;
    }

    public function checkedList(int $count = 1): static
    {
        $this->output .= '<ul class="checked-list"><li>' . collect($this->faker->words($count))->implode('</li><li>') . '</li></ul>';

        return $this;
    }

    public function image(?int $width = 640, ?int $height = 480): static
    {
        $this->output .= '<p><img src="' . $this->faker->imageUrl($width, $height) . '" alt="' . $this->faker->sentence . '" title="' . $this->faker->sentence . '" width="' . $width . '" height="' . $height . '" /></p>';

        return $this;
    }

    public function link(): static
    {
        $this->output .= '<a href="' . $this->faker->url() . '">' . $this->faker->words(rand(3, 8), true) . '</a>';

        return $this;
    }

    public function video(?string $provider = 'youtube', ?int $width = 1600, ?int $height = 900, bool $responsive = true): static
    {
        $style = $responsive ? 'aspect-ratio:1600/900; width: 100%; height: auto;' : '';
        $responsive = $responsive ? 'responsive' : '';

        if ($provider === 'vimeo') {
            $this->output .= '<div data-vimeo-video="true" class="' . $responsive . '"><iframe src="https://vimeo.com/146782320" width="' . $width . '" height="' . $height . '" allowfullscreen="true" allow="autoplay; fullscreen; picture-in-picture" style="' . $style . '"></iframe></div>';
        } else {
            $this->output .= '<div data-youtube-video="true" class="' . $responsive . '"><iframe src="https://www.youtube.com/watch?v=4ugMYpzLA0c" width="' . $width . '" height="' . $height . '" allowfullscreen="true" allow="autoplay; fullscreen; picture-in-picture" style="' . $style . '"></iframe></div>';
        }

        return $this;
    }

    public function details(): static
    {
        $this->output .= '<details><summary>' . $this->faker->sentence() . '</summary><div data-type="details-content"><p>' . $this->faker->paragraph() . '</p></div></details>';

        return $this;
    }

    public function code(?string $className = 'hljs'): static
    {
        $this->output .= "<pre class=\"{$className}\"><code>export default function testComponent({\n\n\tstate,\n\n}) {\n\n\treturn {\n\n\t\tstate,\n\n\t\tinit: function () {\n\n\t\t\t// Initialise the Alpine component here, if you need to.\n\n\t\t},\n\n\t}\n\n}</code></pre>";

        return $this;
    }

    public function blockquote(): static
    {
        $this->output .= '<blockquote>' . $this->faker->sentence() . '</blockquote>';

        return $this;
    }

    public function hr(): static
    {
        $this->output .= '<hr>';

        return $this;
    }

    public function br(): static
    {
        $this->output .= '<br>';

        return $this;
    }

    public function table(?int $cols = null): static
    {
        $cols = $cols ?? rand(3, 8);

        $this->output .= '<table><thead><tr><th>' . collect($this->faker->words($cols))->implode('</th><th>') . '</th></tr></thead><tbody><tr><td>' . collect($this->faker->words($cols))->implode('</td><td>') . '</td></tr><tr><td>' . collect($this->faker->words($cols))->implode('</td><td>') . '</td></tr></tbody></table>';

        return $this;
    }

    public function grid(array $cols = [1, 1, 1]): static
    {
        $this->output .= '<div class="filament-tiptap-grid-builder" data-type="responsive" data-cols="' . count($cols) . '" style="grid-template-columns: repeat(3, 1fr);" data-stack-at="md">';

        foreach ($cols as $col) {
            $this->output .= '<div class="filament-tiptap-grid-builder__column" data-col-span="' . $col . '" style="grid-column: span 1;"><h2>' . Str::title($this->faker->words(rand(3, 8), true)) . '</h2><p>' . $this->faker->paragraph() . '</p></div>';
        }

        $this->output .= '</div>';

        return $this;
    }

    public function sink(): static
    {
        $this
            ->emptyParagraph()
            ->heading()
            ->paragraphs(2, true)
            ->unorderedList(3)
            ->orderedList(3)
            ->checkedList(3)
            ->grid()
            ->image()
            ->video()
            ->details()
            ->code()
            ->blockquote()
            ->hr()
            ->table();

        return $this;
    }

    public function asHTML(): string
    {
        return $this->output;
    }

    public function asJSON(bool $decoded = true): string | array
    {
        return TiptapConverter::asJSON($this->output, decoded: $decoded);
    }
}
