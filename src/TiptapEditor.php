<?php

namespace FilamentTiptapEditor;

use Closure;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Filament\Forms\Components\Field;
use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Concerns\HasPlaceholder;
use Filament\Support\Concerns\HasExtraAlpineAttributes;
use Filament\Forms\Components\Concerns\CanBeLengthConstrained;
use Filament\Forms\Components\Concerns\HasExtraInputAttributes;
use Filament\Forms\Components\Contracts\CanBeLengthConstrained as CanBeLengthConstrainedContract;
use Illuminate\Support\Facades\Storage;

class TiptapEditor extends Field implements CanBeLengthConstrainedContract
{
    use CanBeLengthConstrained;
    use HasExtraInputAttributes;
    use HasExtraAlpineAttributes;

    protected string $view = 'filament-tiptap-editor::tiptap-editor';

    protected ?Closure $saveUploadedFileUsing = null;

    public string $profile = 'default';

    protected ?array $tools = [];

    protected ?string $disk = null;

    protected string | Closure | null $directory = null;

    protected ?array $acceptedFileTypes = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->profile = implode(',', config('filament-tiptap-editor.profiles.default'));

        $this->dehydrateStateUsing(function($state) {
            $this->purgeUnusedFiles($state);
            return $state;
        });
    }

    public function profile(?string $profile)
    {
        $this->profile = implode(',', config('filament-tiptap-editor.profiles.' . $profile));

        return $this;
    }

    public function tools(array $tools): static
    {
        $this->tools = $tools;

        return $this;
    }

    public function disk(?string $disk): static
    {
        $this->disk = $disk;

        return $this;
    }

    public function directory(string | Closure | null $directory): static
    {
        $this->directory = $directory;

        return $this;
    }

    public function acceptedFileTypes(?array $acceptedFileTypes): static
    {
        $this->acceptedFileTypes = $acceptedFileTypes;

        return $this;
    }

    public function getTools(): string
    {
        return !$this->tools ? $this->profile : implode(',', $this->tools);
    }

    public function getDisk(): string
    {
        return $this->disk ?? config('filament-tiptap-editor.disk');
    }

    public function getDirectory(): string
    {
        return $this->directory ? $this->evaluate($this->directory) : config('filament-tiptap-editor.directory');
    }

    public function getAcceptedFileTypes(): array
    {
        return $this->acceptedFileTypes ?? config('filament-tiptap-editor.accepted_file_types');
    }

    private function purgeUnusedFiles(string $state): void
    {
        preg_match_all('/(src|href)=[\'"](.*?)[\'"].*?>/i', $state, $matches);

        if ($matches[2]) {
            $delimiter = $this->getDisk() === 'public' ? '/storage/' : $this->getDisk();

            $used = array_map(function($file) use ($delimiter) {
                return Str::of($file)->after($delimiter)->toString();
            }, $matches[2]);

            $files = Storage::disk($this->getDisk())->files($this->getDirectory());

            foreach (array_diff($files, $used) as $unused) {
                Storage::disk($this->getDisk())->delete($unused);
            }
        } else {
            Storage::disk($this->getDisk())->deleteDirectory($this->getDirectory());
        }
    }
}
