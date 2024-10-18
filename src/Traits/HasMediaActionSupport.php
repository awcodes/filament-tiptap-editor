<?php

namespace FilamentTiptapEditor\Traits;

use Filament\Forms\ComponentContainer;
use FilamentTiptapEditor\TiptapEditor;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait HasMediaActionSupport
{


    protected function getCleanSource(TiptapEditor $component,?string $source=null):?string
    {
        $source =  $source !== ''
            ? $component->getDirectory() . Str::of($source)
                ->after($component->getDirectory())
            : null;
        return Str::afterLast($source,'storage/');
    }



    public function getCleanSourceOnSave(array $data)
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
