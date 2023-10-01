<?php

namespace FilamentTiptapEditor\Tests\Database\Factories;

use Awcodes\HtmlFaker\HtmlFaker;
use FilamentTiptapEditor\Tests\Models\Page;
use Illuminate\Database\Eloquent\Factories\Factory;

class PageFactory extends Factory
{
    protected $model = Page::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'html_content' => HtmlFaker::make()
                ->heading()
                ->paragraphs(withRandomLinks: true)
                ->generate(),
            'json_content' => tiptap_converter()->asJSON(
                HtmlFaker::make()
                    ->heading()
                    ->paragraphs(withRandomLinks: true)
                    ->generate()
            ),
            'text_content' => tiptap_converter()->asText(
                HtmlFaker::make()
                    ->heading()
                    ->paragraphs(withRandomLinks: true)
                    ->generate()
            ),
        ];
    }

    public function json(): Factory
    {
        $content = HtmlFaker::make()
            ->heading()
            ->paragraphs(withRandomLinks: true)
            ->generate();

        return $this->state(fn () => [
            'content' => tiptap_converter()->asJSON($content)
        ]);
    }
}