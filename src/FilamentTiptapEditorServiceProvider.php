<?php

namespace FilamentTiptapEditor;

use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\Str;
use Filament\Facades\Filament;
use Illuminate\Support\HtmlString;
use Spatie\LaravelPackageTools\Package;
use Illuminate\Contracts\Support\Htmlable;
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
    }

    public function packageBooted(): void
    {
        FilamentAsset::register([
            AlpineComponent::make('tiptap', __DIR__ . '/../resources/dist/filament-tiptap-editor.js'),
        ], 'awcodes/tiptap-editor');

        if ($theme = $this->getTiptapEditorStylesLink()) {
            Filament::getCurrentPanel()->renderHook(
                name: 'styles.end',
                hook: fn (): string => $theme,
            );
        }
    }

    public function getTiptapEditorStylesLink(): ?Htmlable
    {
        $themeFile = config('filament-tiptap-editor.theme_file');

        if ($themeFile) {
            $builder = config('filament-tiptap-editor.theme_builder');

            if ($builder == 'vite') {
                $theme = Vite::asset($themeFile, config('filament-tiptap-editor.theme_folder'));
            } else {
                $theme = mix($themeFile);
            }

            if (Str::of($theme)->contains('<link')) {
                return $theme instanceof Htmlable ? $theme : new HtmlString($theme);
            }

            $url = $theme ?? route('filament.asset', [
                'id' => get_asset_id($theme),
                'file' => $theme,
            ]);

            return new HtmlString("<link rel=\"stylesheet\" href=\"{$url}\" />");
        }

        return null;
    }
}
