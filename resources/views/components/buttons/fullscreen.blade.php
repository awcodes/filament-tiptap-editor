<button type="button"
    x-on:click="fullScreenMode = !fullScreenMode"
    x-bind:class="{ 'bg-gray-300 text-gray-900 dark:bg-gray-600 dark:text-gray-300': fullScreenMode }"
    @class([
        'rounded block p-1 hover:bg-gray-200 focus:bg-gray-200',
        'dark:hover:bg-gray-800 dark:focus:bg-gray-800' => config(
            'filament.dark_mode'
        ),
    ])
    x-tooltip="fullScreenMode ? 'Exit Fullscreen' : 'Enter Fullscreen'">
    <div x-show="!fullScreenMode">
        <x-filament-tiptap-editor::icon icon="fullscreen-enter" />
        <span class="sr-only">Enter Fullscreen</span>
    </div>
    <div x-show="fullScreenMode"
        style="display: none;">
        <x-filament-tiptap-editor::icon icon="fullscreen-exit" />
        <span class="sr-only">Exit Fullscreen</span>
    </div>
</button>
