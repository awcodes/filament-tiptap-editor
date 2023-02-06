@php
    $tools = $getTools();
    $statePath = $getStatePath();
    $isDisabled = $isDisabled();
@endphp

<x-forms::field-wrapper :id="$getId()"
    :label="$getLabel()"
    :label-sr-only="$isLabelHidden()"
    :helper-text="$getHelperText()"
    :hint="$getHint()"
    :required="$isRequired()"
    :state-path="$statePath"
>
    <div @class([
        'tiptap-editor border rounded-md relative bg-white shadow-sm dark:bg-gray-700',
        'border-gray-200 dark:border-gray-600' => ! $errors->has($statePath),
        'border-danger-600 ring-danger-600' => $errors->has($statePath),
    ])>

        <div
            wire:ignore
            class="relative z-0 tiptap-wrapper bg-white dark:bg-gray-700 rounded-md"
            x-bind:class="{ 'tiptap-fullscreen': fullScreenMode, 'ring ring-primary-500': focused }"
            x-data="tiptap({
                state: $wire.entangle('{{ $statePath }}').defer,
                statePath: '{{ $statePath }}',
                tools: @js($tools),
                output: '{{ $getOutput() }}',
                disabled: {{ $isDisabled ? 'true' : 'false' }}
            })"
            x-on:keydown.escape="fullScreenMode = false"
            x-trap.noscroll="fullScreenMode"
        >

            @if (! $isDisabled)
            <button type="button" x-on:click="editor().chain().focus()" class="z-20 rounded sr-only focus:not-sr-only focus:absolute focus:py-1 focus:px-3 focus:bg-white focus:text-gray-900">Skip toolbar</button>

            <div class="tiptap-toolbar border-b border-gray-200 bg-gray-50 divide-x divide-gray-300 rounded-t-md z-[1] relative flex flex-col md:flex-row dark:border-gray-900 dark:bg-gray-900 dark:divide-gray-700">

                <div class="flex flex-wrap items-center flex-1 gap-1 p-1 tiptap-toolbar-left">
                    @foreach($tools as $tool)
                        @if ($tool === '|')
                            <div class="border-l border-gray-300 dark:border-gray-700 h-5"></div>
                        @elseif (is_array($tool))
                            <x-dynamic-component component="{{ $tool['view'] }}" :state-path="$statePath" />
                        @else
                            <x-dynamic-component component="filament-tiptap-editor::tools.{{ $tool }}" :state-path="$statePath" />
                        @endif
                    @endforeach
                </div>

                <div class="flex flex-wrap items-start self-stretch gap-1 p-1 pl-2 tiptap-toolbar-right">
                    <x-filament-tiptap-editor::tools.undo />
                    <x-filament-tiptap-editor::tools.redo />
                    <x-filament-tiptap-editor::tools.erase />
                    <x-filament-tiptap-editor::tools.fullscreen />
                </div>

            </div>
            @endif

            <div
                x-ref="element"
                {{ $getExtraInputAttributeBag()->class([
                    'tiptap-content max-h-[40rem] min-h-[56px] h-auto overflow-y-scroll overflow-x-hidden rounded-b-md',
                ]) }}
            ></div>

            <textarea
                x-ref="textarea"
                tabindex="-1"
                class="hidden"
                aria-hidden="true"
                name="{{ $statePath }}"
                @if (!$isConcealed())
                    {!! filled($length = $getMaxLength()) ? "maxlength=\"{$length}\"" : null !!}
                    {!! filled($length = $getMinLength()) ? "minlength=\"{$length}\"" : null !!}
                    {!! $isRequired() ? 'required' : null !!}
                @endif
                {{ $applyStateBindingModifiers('wire:model') }}="{{ $statePath }}"
            ></textarea>
        </div>
    </div>
</x-forms::field-wrapper>
