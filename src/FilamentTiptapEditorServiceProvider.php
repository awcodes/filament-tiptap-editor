<?php

namespace FilamentTiptapEditor;

use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use FilamentTiptapEditor\Commands\MakeBlockCommand;
use Illuminate\Support\Facades\Vite;
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
            ->hasCommands([
                MakeBlockCommand::class,
            ])
            ->hasViews();
    }

    public function packageRegistered(): void
    {
        $this->app->singleton('tiptap-converter', function () {
            return new TiptapConverter;
        });

        $assets = [
            AlpineComponent::make('tiptap', __DIR__ . '/../resources/dist/filament-tiptap-editor.js'),
            Css::make('tiptap', __DIR__ . '/../resources/dist/filament-tiptap-editor.css')->loadedOnRequest(),
        ];

        if (config('filament-tiptap-editor.extensions_script')) {
            $assets[] = Js::make('tiptap-custom-extension-scripts', Vite::asset(config('filament-tiptap-editor.extensions_script')));
        }

        if (config('filament-tiptap-editor.extensions_styles')) {
            $assets[] = Css::make('tiptap-custom-extension-styles', Vite::asset(config('filament-tiptap-editor.extensions_styles')));
        }

        FilamentAsset::register($assets, 'awcodes/tiptap-editor');
    }
}
