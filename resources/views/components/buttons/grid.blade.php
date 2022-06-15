<div x-show="buttons.includes('grid')"
    style="display: none;">
    <x-filament-tiptap-editor::dropdown-button label="Grid"
        active="'grid'"
        icon="grid">
        <x-filament-tiptap-editor::dropdown-button-item action="editor().chain().focus().insertGrid({ cols: 2 }).run()">
            {{ __('2 Columns') }}
        </x-filament-tiptap-editor::dropdown-button-item>
        <x-filament-tiptap-editor::dropdown-button-item action="editor().chain().focus().insertGrid({ cols: 3 }).run()">
            {{ __('3 Columns') }}
        </x-filament-tiptap-editor::dropdown-button-item>
        <x-filament-tiptap-editor::dropdown-button-item action="editor().chain().focus().insertGrid({ cols: 4 }).run()">
            {{ __('4 Columns') }}
        </x-filament-tiptap-editor::dropdown-button-item>
        <x-filament-tiptap-editor::dropdown-button-item action="editor().chain().focus().insertGrid({ cols: 5 }).run()">
            {{ __('5 Columns') }}
        </x-filament-tiptap-editor::dropdown-button-item>
        <x-filament-tiptap-editor::dropdown-button-item
            action="editor().chain().focus().insertGrid({ cols: 2, type: 'fixed' }).run()">
            {{ __('Fixed 2 Columns') }}
        </x-filament-tiptap-editor::dropdown-button-item>
        <x-filament-tiptap-editor::dropdown-button-item
            action="editor().chain().focus().insertGrid({ cols: 3, type: 'fixed' }).run()">
            {{ __('Fixed 3 Columns') }}
        </x-filament-tiptap-editor::dropdown-button-item>
        <x-filament-tiptap-editor::dropdown-button-item
            action="editor().chain().focus().insertGrid({ cols: 4, type: 'fixed' }).run()">
            {{ __('Fixed 4 Columns') }}
        </x-filament-tiptap-editor::dropdown-button-item>
        <x-filament-tiptap-editor::dropdown-button-item
            action="editor().chain().focus().insertGrid({ cols: 5, type: 'fixed' }).run()">
            {{ __('Fixed 5 Columns') }}
        </x-filament-tiptap-editor::dropdown-button-item>
        <x-filament-tiptap-editor::dropdown-button-item
            action="editor().chain().focus().insertGrid({ cols: 2, type: 'asymetric-left-thirds' }).run()">
            {{ __('Asymmetric Left - Thirds') }}
        </x-filament-tiptap-editor::dropdown-button-item>
        <x-filament-tiptap-editor::dropdown-button-item
            action="editor().chain().focus().insertGrid({ cols: 2, type: 'asymetric-right-thirds' }).run()">
            {{ __('Asymmetric Right - Thirds') }}
        </x-filament-tiptap-editor::dropdown-button-item>
        <x-filament-tiptap-editor::dropdown-button-item
            action="editor().chain().focus().insertGrid({ cols: 2, type: 'asymetric-left-fourths' }).run()">
            {{ __('Asymmetric Left - Fourths') }}
        </x-filament-tiptap-editor::dropdown-button-item>
        <x-filament-tiptap-editor::dropdown-button-item
            action="editor().chain().focus().insertGrid({ cols: 2, type: 'asymetric-right-fourths' }).run()">
            {{ __('Asymmetric Right - Fourths') }}
        </x-filament-tiptap-editor::dropdown-button-item>
    </x-filament-tiptap-editor::dropdown-button>
</div>
