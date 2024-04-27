<?php

namespace FilamentTiptapEditor\Commands;

use Filament\Support\Commands\Concerns\CanManipulateFiles;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

use function Laravel\Prompts\text;

class MakeBlockCommand extends Command
{
    use CanManipulateFiles;

    protected $description = 'Create a new Tiptap Editor block';

    protected $signature = 'make:tiptap-block {name?} {--F|force}';

    public function handle(): int
    {
        $block = (string) str(
            $this->argument('name') ??
                text(
                    label: 'What is the block name?',
                    placeholder: 'CustomBlock',
                    required: true,
                ),
        )
            ->trim('/')
            ->trim('\\')
            ->trim(' ')
            ->replace('/', '\\');

        $blockClass = (string) str($block)->afterLast('\\');
        $blockNamespace = str($block)->contains('\\')
            ? (string) str($block)->beforeLast('\\')
            : '';

        $namespace = 'App\\TiptapBlocks';

        $path = app_path('TiptapBlocks/');

        $preview = str($block)
            ->prepend(
                (string) str("{$namespace}\\Previews\\")
                    ->replaceFirst('App\\', '')
            )
            ->replace('\\', '/')
            ->explode('/')
            ->map(fn ($segment) => Str::lower(Str::kebab($segment)))
            ->implode('.');

        $rendered = str($block)
            ->prepend(
                (string) str("{$namespace}\\Rendered\\")
                    ->replaceFirst('App\\', '')
            )
            ->replace('\\', '/')
            ->explode('/')
            ->map(fn ($segment) => Str::lower(Str::kebab($segment)))
            ->implode('.');

        $path = (string) str($block)
            ->prepend('/')
            ->prepend($path ?? '')
            ->replace('\\', '/')
            ->replace('//', '/')
            ->append('.php');

        $previewPath = resource_path(
            (string) str($preview)
                ->replace('.', '/')
                ->prepend('views/')
                ->append('.blade.php'),
        );

        $renderedViewPath = resource_path(
            (string) str($rendered)
                ->replace('.', '/')
                ->prepend('views/')
                ->append('.blade.php'),
        );

        $files = [
            $path,
            $previewPath,
            $renderedViewPath,
        ];

        if (! $this->option('force') && $this->checkForCollision($files)) {
            return static::INVALID;
        }

        $this->copyStubToApp('Block', $path, [
            'class' => $blockClass,
            'namespace' => str($namespace) . ($blockNamespace !== '' ? "\\{$blockNamespace}" : ''),
            'preview' => $preview,
            'rendered' => $rendered,
        ]);

        $this->copyStubToApp('Preview', $previewPath);

        $this->copyStubToApp('Rendered', $renderedViewPath);

        $this->components->info("Tiptap Editor Block [{$path}] created successfully.");

        return self::SUCCESS;
    }
}
