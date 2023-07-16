<x-filament-tiptap-editor::button
    action="fullScreenMode = !fullScreenMode; if (fullScreenMode) editor().chain().focus()"
    x-tooltip="fullScreenMode ? '{{ __('filament-tiptap-editor::editor.fullscreen.exit') }}' : '{{ __('filament-tiptap-editor::editor.fullscreen.enter') }}'"
>
    <div x-show="!fullScreenMode">
        <x-filament-tiptap-editor::icon icon="fullscreen-enter" />
    </div>
    <div x-show="fullScreenMode" style="display: none;">
        <x-filament-tiptap-editor::icon icon="fullscreen-exit" />
    </div>
</x-filament-tiptap-editor::button>