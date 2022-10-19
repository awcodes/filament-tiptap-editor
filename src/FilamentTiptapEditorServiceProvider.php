<?php

namespace FilamentTiptapEditor;

use Livewire\Livewire;
use Illuminate\Support\Str;
use Filament\Facades\Filament;
use Illuminate\Foundation\Vite;
use Illuminate\Support\HtmlString;
use Filament\PluginServiceProvider;
use Spatie\LaravelPackageTools\Package;
use Illuminate\Contracts\Support\Htmlable;

class FilamentTiptapEditorServiceProvider extends PluginServiceProvider
{
    protected array $styles = [
        'filament-tiptap-editor-styles' => __DIR__ . '/../resources/dist/filament-tiptap-editor.css',
    ];

    protected array $beforeCoreScripts = [
        'filament-tiptap-editor-scripts' => __DIR__ . '/../resources/dist/filament-tiptap-editor.js',
    ];

    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-tiptap-editor')
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

        Livewire::component('filament-tiptap-editor-media-uploader-modal', Components\MediaUploaderModal::class);
        Livewire::component('filament-tiptap-editor-link-modal', Components\LinkModal::class);
        Livewire::component('filament-tiptap-editor-source-modal', Components\SourceModal::class);
        Livewire::component('filament-tiptap-editor-youtube-modal', Components\YoutubeModal::class);
        Livewire::component('filament-tiptap-editor-vimeo-modal', Components\VimeoModal::class);
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
