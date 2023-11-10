@props([
    'blocks' => [],
    'statePath' => null
])

<x-filament-tiptap-editor::dropdown-button
    label="{{ __('filament-tiptap-editor::editor.blocks.insert') }}"
    icon="blocks"
    :active="true"
>
    @foreach($blocks as $key => $block)
        @php
            $action = $block->getFormSchema() ? 'insertBlock' : 'insertStaticBlock';
        @endphp
        <x-filament-tiptap-editor::dropdown-button-item
            action="$wire.mountFormComponentAction('{{ $statePath }}', '{{ $action }}', {
                type: '{{ $key }}'
            })"
        >
            {{ $block->getLabel() }}
        </x-filament-tiptap-editor::dropdown-button-item>
    @endforeach
</x-filament-tiptap-editor::dropdown-button>