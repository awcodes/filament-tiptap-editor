<?php

namespace FilamentTiptapEditor\Traits;

use Filament\Forms\ComponentContainer;
use FilamentTiptapEditor\TiptapEditor;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait HasMediaActionSupport
{
    /**
     * Cleans the source URL by removing the unnecessary parts of the path and returning the media path relative to storage.
     *
     * @param TiptapEditor $component The editor component.
     * @param string|null $source The source URL.
     * @return string|null The cleaned source path.
     */
    protected function getCleanSource(TiptapEditor $component, ?string $source = null): ?string
    {
        $source = $source !== ''
            ? $component->getDirectory() . Str::of($source)->after($component->getDirectory())
            : null;

        return Str::afterLast($source, 'storage/');
    }

    /**
     * Cleans and formats the source path for saving based on configuration.
     *
     * @param array $data The form data, including the source URL.
     * @return string The cleaned source URL.
     */
    public function getCleanSourceOnSave(array $data): string
    {
        if (config('filament-tiptap-editor.use_relative_paths')) {
            $source = Str::of($data['src'])
                ->replace(config('app.url'), '')
                ->ltrim('/')
                ->prepend('/');
        } else {
            $source = str_starts_with($data['src'], 'http')
                ? $data['src']
                : Storage::disk(config('filament-tiptap-editor.disk'))->url($data['src']);
        }

        return $source;
    }

    /**
     * Mounts the form with the provided arguments, filling the form fields with cleaned source data.
     *
     * @param TiptapEditor $component The editor component.
     * @param ComponentContainer $form The form container.
     * @param array $arguments The arguments including media attributes (src, alt, title, etc.).
     * @return void
     */
    protected function getMountWith(TiptapEditor $component, ComponentContainer $form, array $arguments): void
    {
        $source = $this->getCleanSource($component, $arguments['src']);

        $form->fill([
            'src' => $source,
            'alt' => $arguments['alt'] ?? '',
            'title' => $arguments['title'] ?? '',
            'width' => $arguments['width'] ?? '',
            'height' => $arguments['height'] ?? '',
            'lazy' => $arguments['lazy'] ?? false,
        ]);
    }
}
