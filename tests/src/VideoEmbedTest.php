<?php

use FilamentTiptapEditor\Tests\Models\Page;
use FilamentTiptapEditor\Tests\Resources\PageResource\Pages\CreatePage;
use Livewire\Livewire;

dataset('providers', ['youtube', 'vimeo']);

dataset('unsafe embed urls', [
    'javascript' => 'javascript:alert(document.domain)//youtube.com/embed/x//vimeo.com/video/1',
    'uppercase javascript' => 'JavaScript:alert(1)//youtube.com/embed/x//vimeo.com/video/1',
    'whitespace in scheme' => "java\tscript:alert(1)//youtube.com/embed/x//vimeo.com/video/1",
    'leading whitespace' => " \njavascript:alert(1)//youtube.com/embed/x//vimeo.com/video/1",
    'data' => 'data:text/html,<script>alert(1)</script>//youtube.com/embed/x//vimeo.com/video/1',
    'vbscript' => 'vbscript:msgbox(1)//youtube.com/embed/x//vimeo.com/video/1',
]);

dataset('safe embed urls', [
    'youtube embed' => 'https://www.youtube.com/embed/dQw4w9WgXcQ?controls=1',
    'youtube nocookie' => 'https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ',
    'vimeo player' => 'https://player.vimeo.com/video/76979871?autoplay=0&loop=0',
    'http' => 'http://www.youtube.com/embed/dQw4w9WgXcQ',
    'scheme-relative' => '//www.youtube.com/embed/dQw4w9WgXcQ',
]);

function embedHtml(string $provider, string $src): string
{
    return '<div data-' . $provider . '-video="" class="responsive"><iframe src="' . e($src) . '" width="640" height="480"></iframe></div>';
}

function embedJson(string $provider, string $src): array
{
    return [
        'type' => 'doc',
        'content' => [
            ['type' => $provider, 'attrs' => ['src' => $src, 'width' => 640, 'height' => 480, 'responsive' => true]],
        ],
    ];
}

it('drops unsafe iframe sources when rendering HTML input', function (string $provider, string $src) {
    $html = tiptap_converter()->asHTML(embedHtml($provider, $src));

    expect($html)
        ->toContain('<iframe')
        ->not->toContain('src=')
        ->not->toContain('script:');
})->with('providers')->with('unsafe embed urls');

it('drops unsafe iframe sources when rendering JSON input', function (string $provider, string $src) {
    $html = tiptap_converter()->asHTML(embedJson($provider, $src));

    expect($html)
        ->toContain('<iframe')
        ->not->toContain('src=')
        ->not->toContain('script:');
})->with('providers')->with('unsafe embed urls');

it('drops unsafe iframe sources when converting HTML to JSON', function (string $provider, string $src) {
    $json = tiptap_converter()->asJSON(embedHtml($provider, $src), decoded: true);

    expect($json['content'][0]['attrs']['src'] ?? null)->toBeNull();
})->with('providers')->with('unsafe embed urls');

it('keeps safe iframe sources unchanged', function (string $provider, string $src) {
    expect(tiptap_converter()->asHTML(embedHtml($provider, $src)))->toContain('src="' . e($src) . '"')
        ->and(tiptap_converter()->asHTML(embedJson($provider, $src)))->toContain('src="' . e($src) . '"')
        ->and(tiptap_converter()->asJSON(embedHtml($provider, $src), decoded: true)['content'][0]['attrs']['src'])->toBe($src);
})->with('providers')->with('safe embed urls');

it('renders an embed without a source', function (string $provider) {
    $html = tiptap_converter()->asHTML([
        'type' => 'doc',
        'content' => [['type' => $provider, 'attrs' => ['width' => 640, 'height' => 480]]],
    ]);

    expect($html)->toContain('<iframe')->not->toContain('src=');
})->with('providers');

it('does not store an unsafe embed source when saving a record', function (string $provider) {
    Livewire::test(CreatePage::class)
        ->fillForm([
            'title' => 'Video',
            'html_content' => embedHtml($provider, 'javascript:alert(1)//youtube.com/embed/x//vimeo.com/video/1'),
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Page::query()->where('title', 'Video')->value('html_content'))
        ->toContain('<iframe')
        ->not->toContain('javascript:');
})->with('providers');
