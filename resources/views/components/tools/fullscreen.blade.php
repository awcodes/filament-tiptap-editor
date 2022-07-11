<button type="button"
    x-on:click="fullScreenMode = !fullScreenMode"
    x-bind:class="{ 'bg-gray-300 text-gray-900 dark:bg-gray-600 dark:text-gray-300': fullScreenMode }"
    @class([
        'rounded block p-1 hover:bg-gray-200 focus:bg-gray-200',
        'dark:hover:bg-gray-800 dark:focus:bg-gray-800' => config(
            'filament.dark_mode'
        ),
    ])
    x-tooltip="fullScreenMode ? '{{ __('filament-tiptap-editor::editor.fullscreen.exit') }}' : '{{ __('filament-tiptap-editor::editor.fullscreen.enter') }}'">
    <div x-show="!fullScreenMode">
        <x-filament-tiptap-editor::icon
            icon="fullscreen-enter"
            title="{{ __('filament-tiptap-editor::editor.fullscreen.enter') }}" />
    </div>
    <div x-show="fullScreenMode" style="display: none;">
        <x-filament-tiptap-editor::icon
            icon="fullscreen-exit"
            title="{{ __('filament-tiptap-editor::editor.fullscreen.exit') }}" />
    </div>
</button>
