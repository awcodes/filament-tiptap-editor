@props([
    'blocks' => []
])

<x-filament-tiptap-editor::dropdown-button
        label="{{ __('filament-tiptap-editor::editor.blocks.insert') }}"
        icon="blocks"
>
    @foreach($blocks as $key => $block)
        <x-filament-tiptap-editor::dropdown-button-item
            action="$dispatch('render-bus', {
                type: '{{ $key }}',
                label: '{{ $block->getLabel() }}',
                data: [],
                context: 'insert',
                width: '{{ $block->getModalWidth() }}',
                slideOver: {{ $block->isSlideOver() ? 'true' : 'false' }},
            })"
        >
            {{ $block->getLabel() }}
        </x-filament-tiptap-editor::dropdown-button-item>
    @endforeach
</x-filament-tiptap-editor::dropdown-button>