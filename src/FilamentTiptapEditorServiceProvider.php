<?php

namespace FilamentTiptapEditor;

use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\AssetManager;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\Str;
use Illuminate\Support\HtmlString;
use Spatie\LaravelPackageTools\Package;
use Illuminate\Contracts\Support\Htmlable;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentTiptapEditorServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-tiptap-editor';

    public static string $viewNamespace = 'filament-tiptap-editor';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasConfigFile()
            ->hasAssets()
            ->hasTranslations()
            ->hasViews();
    }

    public function packageRegistered(): void
    {
        $this->app->resolving(AssetManager::class, function () {
            FilamentAsset::register([
                AlpineComponent::make('tiptap', __DIR__ . '/../resources/dist/filament-tiptap-editor.js'),
            ], static::$name);

            if ($this->app->runningInConsole()) {
                FilamentAsset::register([
                    Css::make('plugin-tiptap-editor-styles', __DIR__ . '/../resources/dist/filament-tiptap-editor.css'),
                ], static::$name);
            }
        });
    }

    public function boot(): void
    {
        parent::boot();

        if ($url = config('filament-tiptap-editor.theme_file')) {
            filament()->getCurrentContext()->renderHook(
                'styles.end',
                fn (): string => new HtmlString("<link rel=\"stylesheet\" href=\"{$url}\" />"),
            );
        }
    }
}
