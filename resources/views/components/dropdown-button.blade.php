@props([
    'active' => null,
    'label' => null,
    'icon' => null,
])
<div x-data="{
    open: false,
    toggle() {
        if (this.open) {
            return this.close()
        }

        this.$refs.button.focus()

        this.open = true
    },
    close(focusAfter) {
        if (!this.open) return

        this.open = false

        focusAfter && focusAfter.focus()
    },
}"
    x-on:keydown.escape.prevent.stop="close($refs.button)"
    x-on:focusin.window="! $refs.panel.contains($event.target) && close()"
     x-on:close-panel="close($refs.button)"
    x-id="['dropdown-button']"
    class="relative">
    <button type="button"
        x-ref="button"
        x-on:click="toggle()"
        :aria-expanded="open"
        :aria-controls="$id('dropdown-button')"
        @if ($active) :class="{ 'bg-gray-300 text-gray-900 dark:bg-gray-600 dark:text-gray-300': isActive({{ $active }}, updatedAt) }" @endif
        x-tooltip="'{{ $label }}'"
        {{ $attributes }}
        @class([
            'rounded block p-1 hover:bg-gray-200 focus:bg-gray-200',
            'dark:hover:bg-gray-800 dark:focus:bg-gray-800' => config(
                'filament.dark_mode'
            ),
        ])>
        <x-filament-tiptap-editor::icon icon="{{ $icon }}" title="{{ $label }}" />
    </button>

    <div x-ref="panel"
        x-show="open"
        x-trap="open"
        x-transition.origin.top.left
        x-on:click.outside="close($refs.button)"
        :id="$id('dropdown-button')"
        style="display: none;"
        class="absolute z-30 overflow-y-scroll text-white bg-gray-900 rounded-md shadow-md max-h-48 top-full"
        style="display: none;">
        <ul class="text-sm divide-y divide-gray-700 min-w-[144px]">
            {{ $slot }}
        </ul>
    </div>
</div>
