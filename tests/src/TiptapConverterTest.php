<?php

it('renders a nested table of contents', function () {
    $toc = tiptap_converter()->asTOC('<h2>Getting started</h2><h3>Install</h3><h2>Usage</h2>');

    expect($toc)->toBe(
        '<ul><li><a href="#getting-started">Getting started</a></li>'
        . '<ul><li><a href="#install">Install</a></li></ul>'
        . '<li><a href="#usage">Usage</a></li></ul>'
    );
});

it('escapes heading text in the table of contents', function () {
    $toc = tiptap_converter()->asTOC('<h2>Tips &amp; &lt;img src=x onerror=alert(1)&gt;</h2>');

    expect($toc)
        ->toContain('>Tips &amp; &lt;img src=x onerror=alert(1)&gt;</a>')
        ->not->toContain('<img');
});

it('escapes heading ids in the table of contents', function () {
    $toc = tiptap_converter()->asTOC('<h2 id="&quot;&gt;&lt;img src=x onerror=alert(1)&gt;">Plain title</h2>');

    expect($toc)
        ->toContain('href="#&quot;&gt;&lt;img src=x onerror=alert(1)&gt;"')
        ->not->toContain('<img');
});

it('leaves the array table of contents unescaped for blade to escape', function () {
    $toc = tiptap_converter()->asTOC('<h2>Tips &amp; tricks</h2>', array: true);

    expect($toc)->toBe([
        ['id' => 'tips-tricks', 'text' => 'Tips & tricks', 'depth' => 2],
    ]);
});
