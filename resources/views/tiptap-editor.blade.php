@php
    $tools = $getTools();
    $floatingMenuTools = $getFloatingMenuTools();
    $statePath = $getStatePath();
    $isDisabled = $isDisabled();
@endphp

<x-dynamic-component
    :component="$getFieldWrapperView()"
    :id="$getId()"
    :label="$getLabel()"
    :label-sr-only="$isLabelHidden()"
    :helper-text="$getHelperText()"
    :hint="$getHint()"
    :hint-action="$getHintAction()"
    :hint-color="$getHintColor()"
    :hint-icon="$getHintIcon()"
    :required="$isRequired()"
    :state-path="$statePath"
>
    <div @class([
        'tiptap-editor border rounded-md relative bg-white shadow-sm dark:bg-gray-700 text-gray-700 dark:text-gray-200',
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
                disabled: {{ $isDisabled ? 'true' : 'false' }},
                locale: '{{ app()->getLocale() }}',
                floatingMenuTools: @js($floatingMenuTools),
            })"
            x-on:keydown.escape="fullScreenMode = false"
            x-on:insert-media.window="$event.detail.statePath === '{{ $statePath }}' ? insertMedia($event.detail.media) : null"
            x-on:insert-video.window="$event.detail.statePath === '{{ $statePath }}' ? insertVideo($event.detail.video) : null"
            x-on:insert-link.window="$event.detail.statePath === '{{ $statePath }}' ? insertLink($event.detail) : null"
            x-on:unset-link.window="$event.detail.statePath === '{{ $statePath }}' ? unsetLink() : null"
            x-on:insert-source.window="$event.detail.statePath === '{{ $statePath }}' ? insertSource($event.detail.source) : null"
            x-on:insert-grid-builder.window="$event.detail.statePath === '{{ $statePath }}' ? insertGridBuilder($event.detail.data) : null"
            x-on:update-editor-content.window="$event.detail.statePath === '{{ $statePath }}' ? updateEditorContent($event.detail.content) : null"
            x-on:refresh-tiptap-editors.window="refreshEditorContent()"
            x-trap.noscroll="fullScreenMode"
        >

            @if (! $isDisabled)
                <button type="button" x-on:click="editor().chain().focus()" class="z-20 rounded sr-only focus:not-sr-only focus:absolute focus:py-1 focus:px-3 focus:bg-white focus:text-gray-900">{{ __('filament-tiptap-editor::editor.skip_toolbar') }}</button>

                <div class="tiptap-toolbar border-b border-gray-200 bg-gray-50 divide-x divide-gray-300 rounded-t-md z-[1] relative flex flex-col md:flex-row dark:border-gray-900 dark:bg-gray-900 dark:divide-gray-700">

                    <div class="flex flex-wrap items-center flex-1 gap-1 p-1 tiptap-toolbar-left">
                        <x-dynamic-component component="filament-tiptap-editor::tools.paragraph" :state-path="$statePath" />
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

            @if (in_array('table', $tools) && ! $isBubbleMenusDisabled())
                <x-filament-tiptap-editor::menus.table-bubble-menu :state-path="$statePath" :tools="$tools"/>
            @endif

            @if (in_array('link', $tools) && ! $isBubbleMenusDisabled())
                <x-filament-tiptap-editor::menus.link-bubble-menu :state-path="$statePath" :tools="$tools"/>
            @endif

            @if (! $isBubbleMenusDisabled())
                <x-filament-tiptap-editor::menus.default-bubble-menu :state-path="$statePath" :tools="$tools"/>
            @endif

            @if (! $isFloatingMenusDisabled() && filled($floatingMenuTools))
                <x-filament-tiptap-editor::menus.default-floating-menu :state-path="$statePath" :tools="$floatingMenuTools"/>
            @endif

            <div @class([
                'tiptap-prosemirror-wrapper mx-auto w-full h-full max-h-[40rem] min-h-[56px] h-auto overflow-y-scroll overflow-x-hidden rounded-b-md',
                match ($getMaxContentWidth()) {
                    'sm' => 'prosemirror-w-sm',
                    'md' => 'prosemirror-w-md',
                    'lg' => 'prosemirror-w-lg',
                    'xl' => 'prosemirror-w-xl',
                    '2xl' => 'prosemirror-w-2xl',
                    '3xl' => 'prosemirror-w-3xl',
                    '4xl' => 'prosemirror-w-4xl',
                    '6xl' => 'prosemirror-w-6xl',
                    '7xl' => 'prosemirror-w-7xl',
                    'full' => 'prosemirror-w-none',
                    default => 'prosemirror-w-5xl',
                }
            ])>
                <div
                    x-ref="element"
                    {{ $getExtraInputAttributeBag()->class(['tiptap-content']) }}
                ></div>
            </div>
        </div>
    </div>
</x-dynamic-component>
