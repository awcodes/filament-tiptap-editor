@props([
    'statePath' => null,
    'tools' => [],
])

<div x-ref="defaultFloatingMenu" class="flex gap-1 items-center" x-cloak>
    @if (in_array('media', $tools)) <x-filament-tiptap-editor::tools.media :state-path="$statePath"/> @endif
    @if (in_array('grid', $tools)) <x-filament-tiptap-editor::tools.grid :state-path="$statePath"/> @endif
    @if (in_array('grid-builder', $tools)) <x-filament-tiptap-editor::tools.grid-builder :state-path="$statePath"/> @endif
    @if (in_array('details', $tools)) <x-filament-tiptap-editor::tools.details :state-path="$statePath"/> @endif
    @if (in_array('table', $tools)) <x-filament-tiptap-editor::tools.table :state-path="$statePath"/> @endif
    @if (in_array('oembed', $tools)) <x-filament-tiptap-editor::tools.oembed :state-path="$statePath"/> @endif
    @if (in_array('code-block', $tools)) <x-filament-tiptap-editor::tools.code-block :state-path="$statePath"/> @endif
</div>
