<?php

namespace FilamentTiptapEditor\Tests;

use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;

if (! function_exists('\FilamentTiptapEditor\Tests\livewire')) {
    function livewire(string $component, array $props = []): Testable
    {
        return Livewire::test($component, $props);
    }
}