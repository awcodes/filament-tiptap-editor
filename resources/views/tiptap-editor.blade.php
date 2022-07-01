@php
    $buttons = $getButtons();
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
        'dark:border-gray-600' =>
            config('filament.dark_mode') && !$errors->has($getStatePath()),
        'border-danger-600 ring-danger-600' => $errors->has($getStatePath()),
    ])>
        <div wire:ignore
            class="relative z-0 tiptap-wrapper"
            x-bind:class="{ 'tiptap-fullscreen': fullScreenMode }"
            x-data="tiptap({ state: $wire.entangle('{{ $getStatePath() }}').defer, buttons: '{{ $buttons }}' })"
            x-on:keydown.escape="fullScreenMode = false">
            <button type="button" x-on:click="editor().chain().focus()" class="z-20 rounded sr-only focus:not-sr-only focus:absolute focus:py-1 focus:px-3 focus:bg-white focus:text-gray-900">Skip toolbar</button>
            <div @class([
                'tiptap-toolbar border-b border-gray-200 bg-gray-100 divide-x divide-gray-300 rounded-t-md z-10 relative flex flex-col md:flex-row',
                'dark:border-gray-900 dark:bg-gray-900 dark:divide-gray-700' => config(
                    'filament.dark_mode'
                ),
            ])>
                <div class="flex flex-wrap items-start flex-1 gap-1 p-1 tiptap-toolbar-left">
                    <x-filament-tiptap-editor::buttons.bold />
                    <x-filament-tiptap-editor::buttons.italic />
                    <x-filament-tiptap-editor::buttons.strike />
                    <x-filament-tiptap-editor::buttons.underline />
                    <x-filament-tiptap-editor::buttons.heading />
                    <x-filament-tiptap-editor::buttons.lead />
                    <x-filament-tiptap-editor::buttons.small />
                    <x-filament-tiptap-editor::buttons.color />
                    <x-filament-tiptap-editor::buttons.list />
                    <x-filament-tiptap-editor::buttons.align-left />
                    <x-filament-tiptap-editor::buttons.align-center />
                    <x-filament-tiptap-editor::buttons.align-right />
                    <x-filament-tiptap-editor::buttons.align-justify />
                    <x-filament-tiptap-editor::buttons.blockquote />
                    <x-filament-tiptap-editor::buttons.hr />
                    <x-filament-tiptap-editor::buttons.link fieldId="{{ $getStatePath() }}" />
                    <x-filament-tiptap-editor::buttons.superscript />
                    <x-filament-tiptap-editor::buttons.subscript />
                    <x-filament-tiptap-editor::buttons.table />
                    <x-filament-tiptap-editor::buttons.grid />
                    <x-filament-tiptap-editor::buttons.media fieldId="{{ $getStatePath() }}" />
                    <x-filament-tiptap-editor::buttons.youtube fieldId="{{ $getStatePath() }}" />
                    <x-filament-tiptap-editor::buttons.vimeo fieldId="{{ $getStatePath() }}" />
                    <x-filament-tiptap-editor::buttons.code />
                    <x-filament-tiptap-editor::buttons.code-block />
                    <x-filament-tiptap-editor::buttons.source fieldId="{{ $getStatePath() }}" />
                    <x-filament-tiptap-editor::buttons.remove-color />
                </div>
                <div class="flex flex-wrap items-start self-stretch gap-1 p-1 pl-2 tiptap-toolbar-right">
                    <x-filament-tiptap-editor::buttons.undo />
                    <x-filament-tiptap-editor::buttons.redo />
                    <x-filament-tiptap-editor::buttons.erase />
                    <x-filament-tiptap-editor::buttons.fullscreen />
                </div>
            </div>

            <div x-ref="element"
            {{ $getExtraInputAttributeBag()->class([
                'tiptap-content max-h-[40rem] h-auto overflow-scroll rounded-b-md bg-white',
                'dark:bg-gray-700' => config('filament.dark_mode'),
            ]) }}></div>

            <textarea x-ref="textarea"
                class="sr-only"
                tabindex="-1"
                @if (!$isConcealed()) {!! filled($length = $getMaxLength()) ? "maxlength=\"{$length}\"" : null !!}
                {!! filled($length = $getMinLength()) ? "minlength=\"{$length}\"" : null !!} {!! $isRequired() ? 'required' : null !!} @endif
                {{ $applyStateBindingModifiers('wire:model') }}="{{ $getStatePath() }}"
                name="{{ $getStatePath() }}"></textarea>
        </div>
    </div>

    @if (config('filament-tiptap-editor.media_uploader_id') == 'filament-tiptap-editor-media-uploader-modal' && str($buttons)->contains('media'))
        @once
            @push('modals')
                @livewire('filament-tiptap-editor-media-uploader-modal')
            @endpush
        @endonce
    @endif

    @if (config('filament-tiptap-editor.link_modal_id') == 'filament-tiptap-editor-link-modal' && str($buttons)->contains('link'))
        @once
            @push('modals')
                @livewire('filament-tiptap-editor-link-modal')
            @endpush
        @endonce
    @endif

    @once
        @push('modals')
            @if (str($buttons)->contains('source')) @livewire('filament-tiptap-editor-source-modal') @endif
            @if (str($buttons)->contains('youtube')) @livewire('filament-tiptap-editor-youtube-modal') @endif
            @if (str($buttons)->contains('vimeo')) @livewire('filament-tiptap-editor-vimeo-modal') @endif
        @endpush
    @endonce
</x-forms::field-wrapper>
