<?php

namespace FilamentTiptapEditor;

use Filament\Facades\Filament;
use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use FilamentTiptapEditor\Livewire\Bus;
use FilamentTiptapEditor\Livewire\TiptapBlock;
use Illuminate\Support\Facades\Blade;
use Livewire\Livewire;
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

    public function packageBooted(): void
    {
        Livewire::component('tiptap-bus', Bus::class);

        Filament::getCurrentPanel()->renderHook(
            name: 'panels::body.end',
            hook: fn (): string => Blade::render('@livewire("tiptap-bus")'),
        );
    }
}
