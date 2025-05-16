<?php

namespace FilamentTiptapEditor\Tests\Database\Factories;

use FilamentTiptapEditor\Tests\Models\Page;
use FilamentTiptapEditor\TiptapFaker;
use Illuminate\Database\Eloquent\Factories\Factory;

class PageFactory extends Factory
{
    protected $model = Page::class;

    public function definition(): array
    {
        $content = TiptapFaker::make()
            ->heading()
            ->paragraphs(withRandomLinks: true);

        return [
            'title' => $this->faker->sentence(),
            'html_content' => $content->asHTML(),
            'json_content' => $content->asJSON(),
            'text_content' => tiptap_converter()->asText($content->asHTML()),
        ];
    }
}
