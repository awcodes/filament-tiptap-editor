<x-filament-tiptap-editor::dropdown-button
    label="{{ __('filament-tiptap-editor::editor.color.label') }}"
    active="color"
    icon="color"
    :list="false"
>
    <div
        x-data="{
            state: editor().getAttributes('textStyle').color || '#000000',

            init: function () {
                if (!(this.state === null || this.state === '')) {
                    this.setState(this.state)
                }
            },

            setState: function (value) {
                this.state = value
            }
        }"
        x-on:keydown.esc="isOpen() && $event.stopPropagation()"
        class="relative flex-1 p-1"
    >
        <hex-color-picker x-bind:color="state" x-on:color-changed="setState($event.detail.value)"></hex-color-picker>

        <div class="w-full flex gap-2 mt-2">
            <x-filament-support::button
                x-on:click="editor().chain().focus().setColor(state).run(); $dispatch('close-panel')"
                size="sm"
                class="flex-1"
            >
                {{ __('filament-tiptap-editor::editor.color.choose') }}
            </x-filament-support::button>

            <x-filament-support::button
                x-on:click="editor().chain().focus().unsetColor().run(); $dispatch('close-panel')"
                size="sm"
                class="flex-1"
                color="danger"
            >
                {{ __('filament-tiptap-editor::editor.color.remove') }}
            </x-filament-support::button>
        </div>
    </div>
</x-filament-tiptap-editor::dropdown-button>