@props([
    'blocks' => [],
    'statePath' => null
])

<x-filament-tiptap-editor::dropdown-button
    label="{{ trans('filament-tiptap-editor::editor.blocks.insert') }}"
    icon="blocks"
    :active="true"
>
    @foreach($blocks as $key => $block)
        <x-filament-tiptap-editor::dropdown-button-item
            action="$wire.mountFormComponentAction('{{ $statePath }}', 'insertBlock', {
                type: '{{ $key }}'
            })"
        >
            {{ $block->getLabel() }}
        </x-filament-tiptap-editor::dropdown-button-item>
    @endforeach
</x-filament-tiptap-editor::dropdown-button>