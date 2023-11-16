<?php

namespace FilamentTiptapEditor\Concerns;

use FilamentTiptapEditor\Enums\TiptapOutput;
use FilamentTiptapEditor\Facades\TiptapConverter;

trait CanStoreOutput
{
    protected ?TiptapOutput $output = null;

    public function output(TiptapOutput $output): static
    {
        $this->output = $output;

        return $this;
    }

    public function getOutput(): TiptapOutput
    {
        return $this->output ?? config('filament-tiptap-editor.output');
    }

    public function getHTML(): string
    {
        return TiptapConverter::asHTML($this->getState());
    }

    public function getText(): string
    {
        return TiptapConverter::asText($this->getState());
    }

    public function getJSON(bool $decoded = false): string | array
    {
        return TiptapConverter::asJSON($this->getState(), decoded: $decoded);
    }

    public function expectsHTML(): bool
    {
        return $this->getOutput() === TiptapOutput::Html;
    }

    public function expectsJSON(): bool
    {
        return $this->getOutput() === TiptapOutput::Json;
    }

    public function expectsText(): bool
    {
        return $this->getOutput() === TiptapOutput::Text;
    }
}
