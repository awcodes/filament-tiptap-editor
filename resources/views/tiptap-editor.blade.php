@php
    $tools = $getTools();
    $floatingMenuTools = $getFloatingMenuTools();
    $statePath = $getStatePath();
    $isDisabled = $isDisabled();
    $blocks = $getBlocks();
    $shouldSupportBlocks = $shouldSupportBlocks();
@endphp

@if (config('filament-tiptap-editor.extensions_script') || config('filament-tiptap-editor.extensions_styles'))
    @vite([
        config('filament-tiptap-editor.extensions_script', null),
        config('filament-tiptap-editor.extensions_styles', null)
    ])
@endif

<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div class="flex gap-3">
        <div class="flex-1">
            <div
                @class([
                    'tiptap-editor rounded-md relative text-gray-950 bg-white shadow-sm ring-1 dark:bg-white/5 dark:text-white',
                    'ring-gray-950/10 dark:ring-white/20' => ! $errors->has($statePath),
                    'ring-danger-600 dark:ring-danger-600' => $errors->has($statePath),
                ])
                @if (! $shouldDisableStylesheet())
                    x-data="{}"
                    x-load-css="[@js(\Filament\Support\Facades\FilamentAsset::getStyleHref('tiptap', 'awcodes/tiptap-editor'))]"
                @endif
            >
                <div
                    wire:ignore
                    x-ignore
                    ax-load
                    ax-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('tiptap', 'awcodes/tiptap-editor') }}"
                    class="relative z-0 tiptap-wrapper rounded-md bg-white dark:bg-gray-900"
                    x-bind:class="{ 'tiptap-fullscreen': fullScreenMode, 'ring ring-primary-500': focused }"
                    x-data="tiptap({
                        state: $wire.{{ $applyStateBindingModifiers("entangle('{$statePath}')", isOptimisticallyLive: false) }},
                        statePath: '{{ $statePath }}',
                        tools: @js($tools),
                        disabled: @js($isDisabled),
                        locale: '{{ app()->getLocale() }}',
                        floatingMenuTools: @js($floatingMenuTools),
                        placeholder: @js($getPlaceholder()),
                    })"
                    x-on:click.away="focused = false"
                    x-on:keydown.escape="fullScreenMode = false"
                    x-on:insert-content.window="insertContent($event)"
                    x-on:update-editor-content.window="updateEditorContent($event)"
                    x-on:refresh-tiptap-editors.window="refreshEditorContent()"
                    x-on:dragged-block.stop="$wire.mountFormComponentAction('{{ $statePath }}', 'insertBlock', {
                        type: $event.detail.type,
                        coordinates: $event.detail.coordinates,
                    })"
                    x-on:insert-block.window="insertBlock($event)"
                    x-on:update-block.window="updateBlock($event)"
                    x-on:open-block-settings.window="openBlockSettings($event)"
                    x-on:delete-block.window="deleteBlock()"
                    x-trap.noscroll="fullScreenMode"
                >

                    @if (! $isDisabled && $tools)
                        <button type="button" x-on:click="editor().chain().focus()" class="z-20 rounded sr-only focus:not-sr-only focus:absolute focus:py-1 focus:px-3 focus:bg-white focus:text-gray-900">{{ __('filament-tiptap-editor::editor.skip_toolbar') }}</button>

                        <div class="tiptap-toolbar text-gray-800 border-b border-gray-950/10 bg-gray-50 divide-x divide-gray-950/10 rounded-t-md z-[1] relative flex flex-col md:flex-row dark:text-gray-300 dark:border-white/20 dark:bg-gray-950 dark:divide-white/20">

                            <div class="flex flex-wrap items-center flex-1 gap-1 p-1 tiptap-toolbar-left">
                                <x-dynamic-component component="filament-tiptap-editor::tools.paragraph" :state-path="$statePath" />
                                @foreach ($tools as $tool)
                                    @if ($tool === '|')
                                        <div class="border-l border-gray-950/10 dark:border-white/20 h-5"></div>
                                    @elseif (is_array($tool))
                                        <x-dynamic-component component="{{ $tool['button'] }}" :state-path="$statePath" />
                                    @elseif ($tool === 'blocks')
                                        @if ($blocks && $shouldSupportBlocks)
                                            <x-filament-tiptap-editor::tools.blocks :blocks="$blocks" :state-path="$statePath" />
                                        @endif
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
                        <x-filament-tiptap-editor::menus.default-floating-menu :state-path="$statePath" :tools="$floatingMenuTools" :blocks="$blocks" :should-support-blocks="$shouldSupportBlocks"/>
                    @endif

                    <div class="flex h-full">
                        <div @class([
                            'tiptap-prosemirror-wrapper mx-auto w-full max-h-[40rem] min-h-[56px] h-auto overflow-y-scroll overflow-x-hidden rounded-b-md',
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
                                {{ $getExtraInputAttributeBag()->class([
                                    'tiptap-content min-h-full'
                                ]) }}
                            ></div>
                        </div>

                        @if (! $isDisabled && $shouldSupportBlocks)
                            <div
                                x-data="{
                                    isCollapsed: false,
                                }"
                                class="hidden shrink-0 space-y-2 max-w-sm md:flex flex-col h-full"
                                x-bind:class="{
                                    'bg-gray-50 dark:bg-gray-950/20': ! isCollapsed,
                                    'h-full': ! isCollapsed && fullScreenMode,
                                    'px-2': ! fullScreenMode,
                                    'px-3': fullScreenMode
                                }"
                            >
                                <div class="flex items-center mt-2">
                                    <p class="text-xs font-bold" x-show="! isCollapsed">Blocks</p>

                                    <button x-on:click="isCollapsed = false" x-show="isCollapsed" x-cloak type="button" class="ml-auto">
                                        <x-heroicon-m-bars-3 class="w-5 h-5" />
                                    </button>

                                    <button x-on:click="isCollapsed = true" x-show="! isCollapsed" type="button" class="ml-auto">
                                        <x-heroicon-m-x-mark class="w-5 h-5" />
                                    </button>
                                </div>

                                <div x-show="! isCollapsed" class="overflow-y-auto space-y-1 h-full pb-2">
                                    @foreach ($blocks as $block)
                                        <div
                                            draggable="true"
                                            x-on:dragstart="$event?.dataTransfer?.setData('blockType', @js($block->getIdentifier()))"
                                            class="cursor-move grid-col-1 flex items-center gap-2 rounded border text-xs px-3 py-2 bg-white dark:bg-gray-800 dark:border-gray-700"
                                        >
                                            @if ($block->getIcon())
                                                <x-filament::icon
                                                    :icon="$block->getIcon()"
                                                    class="h-5 w-5"
                                                />
                                            @endif

                                            {{ $block->getLabel() }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-dynamic-component>
