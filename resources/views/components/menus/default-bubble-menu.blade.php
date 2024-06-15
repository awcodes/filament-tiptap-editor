@props([
    'statePath' => null,
    'tools' => [],
    'editor' => null,
])

<div
    class="flex gap-1 items-center"
    x-show="! (
        editor().isActive('link', updatedAt) ||
        editor().isActive('image', updatedAt) ||
        editor().isActive('table', updatedAt) ||
        editor().view.state.selection.$from === editor().state.selection.$to
    )"
    style="display: none;"
>
    @foreach ($tools as $tool)
        @if (is_array($tool))
            <x-dynamic-component component="{{ $tool['button'] }}" :state-path="$statePath" :editor="$editor" />
        @elseif ($tool === 'blocks')
            @if ($blocks && $shouldSupportBlocks)
                <x-filament-tiptap-editor::tools.blocks :blocks="$blocks" :state-path="$statePath" :editor="$editor" />
            @endif
        @else
            <x-dynamic-component component="filament-tiptap-editor::tools.{{ $tool }}" :state-path="$statePath" :editor="$editor" />
        @endif
    @endforeach
</div>


