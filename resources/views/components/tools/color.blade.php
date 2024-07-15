<x-filament-tiptap-editor::dropdown-button
    label="{{ trans('filament-tiptap-editor::editor.color.label') }}"
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

                this.$watch('state', (value) => {
                    if (! value.startsWith('#')) {
                        this.state = `#${value}`
                    }
                })
            },

            setState: function (value) {
                this.state = value
            }
        }"
        x-on:keydown.esc="isOpen() && $event.stopPropagation()"
        class="relative flex-1 p-1"
    >
        <tiptap-hex-color-picker x-bind:color="state" x-on:color-changed="setState($event.detail.value)"></tiptap-hex-color-picker>

        <label
            class="fi-input-wrp mt-2 flex rounded-lg shadow-sm ring-1 transition duration-75 bg-white dark:bg-white/5 [&:not(:has(.fi-ac-action:focus))]:focus-within:ring-2 ring-gray-950/10 dark:ring-white/20 [&:not(:has(.fi-ac-action:focus))]:focus-within:ring-primary-600 dark:[&:not(:has(.fi-ac-action:focus))]:focus-within:ring-primary-500 fi-fo-text-input overflow-hidden"
        >
            <input
                x-model="state"
                class="fi-input block w-full border-none py-1.5 text-base text-gray-950 transition duration-75 placeholder:text-gray-400 focus:ring-0 disabled:text-gray-500 disabled:[-webkit-text-fill-color:theme(colors.gray.500)] disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.400)] dark:text-white dark:placeholder:text-gray-500 dark:disabled:text-gray-400 dark:disabled:[-webkit-text-fill-color:theme(colors.gray.400)] dark:disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.500)] sm:text-sm sm:leading-6 bg-white/0 ps-3 pe-3"
            />
            <span class="sr-only">{{ trans('filament-tiptap-editor::editor.color.input_label') }}</span>
        </label>

        <div class="w-full flex gap-2 mt-2">
            <x-filament::button
                x-on:click="editor().chain().focus().setColor(state).run(); $dispatch('close-panel')"
                size="sm"
                class="flex-1"
            >
                {{ trans('filament-tiptap-editor::editor.color.choose') }}
            </x-filament::button>

            <x-filament::button
                x-on:click="editor().chain().focus().unsetColor().run(); $dispatch('close-panel')"
                size="sm"
                class="flex-1"
                color="danger"
            >
                {{ trans('filament-tiptap-editor::editor.color.remove') }}
            </x-filament::button>
        </div>
    </div>
</x-filament-tiptap-editor::dropdown-button>