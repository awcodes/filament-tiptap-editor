<x-filament-tiptap-editor::button
    action="fullScreenMode = !fullScreenMode; if (fullScreenMode) editor().chain().focus()"
    x-tooltip="fullScreenMode ? '{{ __('filament-tiptap-editor::editor.fullscreen.exit') }}' : '{{ __('filament-tiptap-editor::editor.fullscreen.enter') }}'"
>
    <div x-show="!fullScreenMode">
        <x-tiptap-fullscreen-enter class="w-5 h-5" />
    </div>
    <div x-show="fullScreenMode" style="display: none;">
        <x-tiptap-fullscreen-exit class="w-5 h-5" />
    </div>
</x-filament-tiptap-editor::button>