<?php

use Filament\Forms\Components\FileUpload;
use FilamentTiptapEditor\Actions\MediaAction;
use FilamentTiptapEditor\Tests\Resources\PageResource\Pages\CreatePage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\FileUploadConfiguration;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Livewire;

function makeTemporaryUpload(string $originalName, string $contents): TemporaryUploadedFile
{
    $extension = pathinfo($originalName, PATHINFO_EXTENSION);
    $meta = str_replace('/', '_', base64_encode($originalName));
    $filename = Str::random(30) . '-meta' . $meta . '-.' . $extension;

    Storage::disk(FileUploadConfiguration::disk())
        ->put(FileUploadConfiguration::path($filename, false), $contents);

    return new TemporaryUploadedFile($filename, FileUploadConfiguration::disk());
}

function pngBytes(): string
{
    return base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==');
}

function jpegBytes(): string
{
    $image = imagecreatetruecolor(1, 1);
    ob_start();
    imagejpeg($image);

    return ob_get_clean();
}

function pdfBytes(): string
{
    return "%PDF-1.4\n1 0 obj<</Type/Catalog/Pages 2 0 R>>endobj\n2 0 obj<</Type/Pages/Kids[]/Count 0>>endobj\ntrailer<</Root 1 0 R>>\n%%EOF\n";
}

function svgBytes(): string
{
    return '<?xml version="1.0"?><svg xmlns="http://www.w3.org/2000/svg" width="1" height="1"></svg>';
}

beforeEach(function () {
    Storage::fake(FileUploadConfiguration::disk());
    Storage::fake('public');
});

it('stores uploads under the extension of their detected content', function (string $name, Closure $contents, string $expected) {
    $file = makeTemporaryUpload($name, $contents());

    expect(MediaAction::getStoredFileExtension($file))->toBe($expected);
})->with([
    'png named .html' => ['page.html', fn () => pngBytes(), 'png'],
    'png named .js' => ['script.js', fn () => pngBytes(), 'png'],
    'png named .svg' => ['image.svg', fn () => pngBytes(), 'png'],
    'png without extension' => ['no-extension', fn () => pngBytes(), 'png'],
    'pdf named .html' => ['document.html', fn () => pdfBytes(), 'pdf'],
    'svg named .html' => ['drawing.html', fn () => svgBytes(), 'svg'],
    'jpeg named .png' => ['scan.png', fn () => jpegBytes(), 'jpg'],
]);

it('keeps the client extension when it matches the content', function (string $name, Closure $contents, string $expected) {
    $file = makeTemporaryUpload($name, $contents());

    expect(MediaAction::getStoredFileExtension($file))->toBe($expected);
})->with([
    'png' => ['photo.png', fn () => pngBytes(), 'png'],
    'jpeg' => ['photo.jpeg', fn () => jpegBytes(), 'jpeg'],
    'uppercase jpg' => ['photo.JPG', fn () => jpegBytes(), 'JPG'],
    'svg' => ['logo.svg', fn () => svgBytes(), 'svg'],
    'pdf' => ['document.pdf', fn () => pdfBytes(), 'pdf'],
]);

it('uses the detected extension in the default save handler', function (string $name, string $action) {
    $livewire = Livewire::test(CreatePage::class)
        ->mountFormComponentAction('html_content', $action, [
            'src' => '', 'alt' => '', 'title' => '', 'width' => '', 'height' => '', 'lazy' => null,
        ]);

    $form = (fn () => $this->getMountedFormComponentActionForm())->call($livewire->instance());

    /** @var FileUpload $upload */
    $upload = collect($form->getFlatComponents())->first(fn ($component) => $component instanceof FileUpload);

    $upload->state([(string) Str::uuid() => makeTemporaryUpload($name, pngBytes())]);
    $upload->saveUploadedFiles();

    $url = collect($upload->getState())->first();

    expect($url)->toEndWith('.png');

    Storage::disk('public')->assertExists('images/' . basename($url));
})->with(['page.html', 'script.js', 'photo.png'])->with(['filament_tiptap_media', 'filament_tiptap_edit_media']);
