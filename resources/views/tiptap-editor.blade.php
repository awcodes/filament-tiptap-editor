@php
    $tools = $getTools();
@endphp

<x-forms::field-wrapper :id="$getId()"
    :label="$getLabel()"
    :label-sr-only="$isLabelHidden()"
    :helper-text="$getHelperText()"
    :hint="$getHint()"
    :required="$isRequired()"
    :state-path="$getStatePath()">
    <div @class([
        'tiptap-editor border rounded-md relative bg-white shadow-sm',
        'dark:bg-gray-700' => config('filament.dark_mode'),
        'border-gray-200' => !$errors->has($getStatePath()),
        'dark:border-gray-600' => config('filament.dark_mode') && !$errors->has($getStatePath()),
        'border-danger-600 ring-danger-600' => $errors->has($getStatePath()),
    ])>
        @if ($isDisabled())
            <div class="relative z-0 tiptap-wrapper">
                <div
                    {{ $getExtraInputAttributeBag()->class([
                        'tiptap-content max-h-[40rem] h-auto overflow-scroll rounded-b-md bg-white ProseMirror',
                        'dark:bg-gray-700' => config('filament.dark_mode'),
                    ]) }}
                >
                    {!! $getState() !!}
                </div>
            </div>
        @else
        <div wire:ignore
            class="relative z-0 tiptap-wrapper bg-white dark:bg-gray-700 rounded-md"
            x-bind:class="{ 'tiptap-fullscreen': fullScreenMode, 'ring ring-primary-500': focused }"
            x-data="tiptap({
                state: $wire.entangle('{{ $getStatePath() }}').defer,
                tools: '{{ $tools }}'
            })"
            x-on:keydown.escape="fullScreenMode = false"
            x-trap.noscroll="fullScreenMode">
            <button type="button" x-on:click="editor().chain().focus()" class="z-20 rounded sr-only focus:not-sr-only focus:absolute focus:py-1 focus:px-3 focus:bg-white focus:text-gray-900">Skip toolbar</button>
            <div @class([
                'tiptap-toolbar border-b border-gray-200 bg-gray-50 divide-x divide-gray-300 rounded-t-md z-[1] relative flex flex-col md:flex-row',
                'dark:border-gray-900 dark:bg-gray-900 dark:divide-gray-700' => config('filament.dark_mode'),
            ])>
                <div class="flex flex-wrap items-center flex-1 gap-1 p-1 tiptap-toolbar-left">
                    @foreach(explode(',', $getTools()) as $tool)
                        @if ($tool === '|')
                            <div class="border-l border-gray-300 dark:border-gray-700 h-5"></div>
                        @else
                            <x-dynamic-component component="filament-tiptap-editor::tools.{{ $tool }}" fieldId="{{ $getStatePath() }}" />
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

            <div
                x-ref="element"
                {{ $getExtraInputAttributeBag()->class([
                    'tiptap-content max-h-[40rem] h-auto overflow-scroll rounded-b-md',
                ]) }}
            ></div>

            <textarea
                x-ref="textarea"
                tabindex="-1"
                class="sr-only"
                aria-hidden="true"
                name="{{ $getStatePath() }}"
                @if (!$isConcealed())
                    {!! filled($length = $getMaxLength()) ? "maxlength=\"{$length}\"" : null !!}
                    {!! filled($length = $getMinLength()) ? "minlength=\"{$length}\"" : null !!}
                    {!! $isRequired() ? 'required' : null !!}
                @endif
                {{ $applyStateBindingModifiers('wire:model') }}="{{ $getStatePath() }}"
            ></textarea>
        </div>
        @endif
    </div>

    @if (! $isDisabled())
        @if (str($tools)->contains('media'))
            @once
                @push('modals')
                    @if (config('filament-tiptap-editor.media_uploader_id') == 'filament-tiptap-editor-media-uploader-modal')
                        @livewire('filament-tiptap-editor-media-uploader-modal', [
                            'disk' => $getDisk(),
                            'directory' => $getDirectory(),
                            'acceptedFileTypes' => $getAcceptedFileTypes(),
                            'maxFileSize' => $getMaxFileSize(),
                        ])
                    @endif
                @endpush
            @endonce
        @endif

        @if (config('filament-tiptap-editor.link_modal_id') == 'filament-tiptap-editor-link-modal' && str($tools)->contains('link'))
            @once
                @push('modals')
                    @livewire('filament-tiptap-editor-link-modal')
                @endpush
            @endonce
        @endif

        @once
            @push('modals')
                @if (str($tools)->contains('source')) @livewire('filament-tiptap-editor-source-modal') @endif
                @if (str($tools)->contains('youtube')) @livewire('filament-tiptap-editor-youtube-modal') @endif
                @if (str($tools)->contains('vimeo')) @livewire('filament-tiptap-editor-vimeo-modal') @endif
            @endpush
        @endonce
    @endif
</x-forms::field-wrapper>
