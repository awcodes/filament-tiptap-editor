<?php

namespace FilamentTiptapEditor\Concerns;

use FilamentTiptapEditor\Enums\TiptapOutput;

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
        return \FilamentTiptapEditor\Facades\TiptapConverter::asHTML($this->getState());
    }

    public function getText(): string
    {
        return \FilamentTiptapEditor\Facades\TiptapConverter::asText($this->getState());
    }

    public function getJSON(): string
    {
        return \FilamentTiptapEditor\Facades\TiptapConverter::asJSON($this->getState());
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