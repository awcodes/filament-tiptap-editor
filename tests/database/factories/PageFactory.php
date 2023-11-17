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
        $content = HtmlFaker::make()
            ->heading()
            ->paragraphs(withRandomLinks: true)
            ->generate();

        return [
            'title' => $this->faker->sentence(),
            'html_content' => $content,
            'json_content' => tiptap_converter()->asJSON($content, decoded: true),
            'text_content' => tiptap_converter()->asText($content),
        ];
    }
}