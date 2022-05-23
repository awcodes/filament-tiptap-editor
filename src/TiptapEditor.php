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

class TiptapEditor extends Field implements CanBeLengthConstrainedContract
{
    use CanBeLengthConstrained;
    use HasExtraInputAttributes;
    use HasExtraAlpineAttributes;

    protected string $view = 'filament-tiptap-editor::tiptap-editor';

    protected ?Closure $saveUploadedFileUsing = null;

    public string $profile = 'default';

    protected function setUp(): void
    {
        parent::setUp();

        $this->profile = collect(config('filament-tiptap-editor.profiles.default'))->implode(',');
    }

    public function profile(?string $profile)
    {
        $this->profile = collect(config('filament-tiptap-editor.profiles.' . $profile))->implode(',');

        return $this;
    }

    public function saveUploadedFileUsing(?Closure $callback): static
    {
        $this->saveUploadedFileUsing = $callback;

        return $this;
    }

    public function saveUploadedFiles(): void
    {
        if (blank($this->getState())) {
            $this->state([]);

            return;
        }

        if (!is_array($this->getState())) {
            $this->state([$this->getState()]);
        }

        $state = array_map(function (TemporaryUploadedFile | string $file) {
            if (!$file instanceof TemporaryUploadedFile) {
                return $file;
            }

            $callback = $this->saveUploadedFileUsing;

            if (!$callback) {
                $file->delete();

                return $file;
            }

            $storedFile = $this->evaluate($callback, [
                'file' => $file,
            ]);

            $file->delete();

            return $storedFile;
        }, $this->getState());

        if ($this->canReorder && ($callback = $this->reorderUploadedFilesUsing)) {
            $state = $this->evaluate($callback, [
                'state' => $state,
            ]);
        }

        $this->state($state);
    }

    public function getButtons()
    {
        return $this->profile;
    }
}
