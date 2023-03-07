<?php

namespace FilamentTiptapEditor;

use Composer\InstalledVersions;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\Str;
use Filament\Facades\Filament;
use Illuminate\Support\HtmlString;
use Filament\PluginServiceProvider;
use Spatie\LaravelPackageTools\Package;
use Illuminate\Contracts\Support\Htmlable;

class FilamentTiptapEditorServiceProvider extends PluginServiceProvider
{
    public static string $name = 'filament-tiptap-editor';

    public static string $version = 'dev';

    public function configurePackage(Package $package): void
    {
        static::$version = InstalledVersions::getVersion('awcodes/filament-tiptap-editor');

        $package
            ->name(static::$name)
            ->hasConfigFile()
            ->hasAssets()
            ->hasTranslations()
            ->hasViews();
    }

    public function boot()
    {
        parent::boot();

        if ($theme = $this->getTiptapEditorStylesLink()) {
            Filament::registerRenderHook(
                'styles.end',
                fn (): string => $theme,
            );
        }
    }

    protected function getStyles(): array
    {
        return [
            'plugin-tiptap-editor-' . static::$version => __DIR__ . '/../resources/dist/filament-tiptap-editor.css',
        ];
    }

    /**
     * @return array
     */
    public function getBeforeCoreScripts(): array
    {
        return [
            'plugin-tiptap-editor-' . static::$version => __DIR__ . '/../resources/dist/filament-tiptap-editor.js',
        ];
    }

    public function getTiptapEditorStylesLink(): ?Htmlable
    {
        $themeFile = config('filament-tiptap-editor.theme_file');

        if ($themeFile) {
            $builder = config('filament-tiptap-editor.theme_builder');

            if ($builder == 'vite') {
                $theme = app(Vite::class)($themeFile, config('filament-tiptap-editor.theme_folder'));
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
