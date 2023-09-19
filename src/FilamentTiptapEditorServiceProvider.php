<?php

namespace FilamentTiptapEditor;

use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentTiptapEditorServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-tiptap-editor')
            ->hasConfigFile()
            ->hasAssets()
            ->hasTranslations()
            ->hasViews();
    }

    public function packageRegistered(): void
    {
        $this->app->singleton('tiptap-converter', function () {
            return new TiptapConverter();
        });

        FilamentAsset::register([
            AlpineComponent::make('tiptap', __DIR__ . '/../resources/dist/filament-tiptap-editor.js'),
            Css::make('tiptap', __DIR__ . '/../resources/dist/filament-tiptap-editor.css')->loadedOnRequest(),
        ], 'awcodes/tiptap-editor');
    }
}
