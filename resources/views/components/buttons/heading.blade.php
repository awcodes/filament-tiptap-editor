<div x-show="buttons.includes('h1') || buttons.includes('h2') || buttons.includes('h3') || buttons.includes('h4') || buttons.includes('h5') || buttons.includes('h6')"
    style="display: none;">
    <x-filament-tiptap-editor::dropdown-button label="{{ __('filament-tiptap-editor::editor.heading.label') }}"
        active="'heading'"
        icon="heading">
        <x-filament-tiptap-editor::dropdown-button-item
            action="editor().chain().focus().toggleHeading({ level: 1 }).run()"
            x-show="buttons.includes('h1')"
            style="display: none;">
            {{ __('filament-tiptap-editor::editor.heading.h1') }}
        </x-filament-tiptap-editor::dropdown-button-item>
        <x-filament-tiptap-editor::dropdown-button-item
            action="editor().chain().focus().toggleHeading({ level: 2 }).run()"
            x-show="buttons.includes('h2')"
            style="display: none;">
            {{ __('filament-tiptap-editor::editor.heading.h2') }}
        </x-filament-tiptap-editor::dropdown-button-item>
        <x-filament-tiptap-editor::dropdown-button-item
            action="editor().chain().focus().toggleHeading({ level: 3 }).run()"
            x-show="buttons.includes('h3')"
            style="display: none;">
            {{ __('filament-tiptap-editor::editor.heading.h3') }}
        </x-filament-tiptap-editor::dropdown-button-item>
        <x-filament-tiptap-editor::dropdown-button-item
            action="editor().chain().focus().toggleHeading({ level: 4 }).run()"
            x-show="buttons.includes('h4')"
            style="display: none;">
            {{ __('filament-tiptap-editor::editor.heading.h4') }}
        </x-filament-tiptap-editor::dropdown-button-item>
        <x-filament-tiptap-editor::dropdown-button-item
            action="editor().chain().focus().toggleHeading({ level: 5 }).run()"
            x-show="buttons.includes('h5')"
            style="display: none;">
            {{ __('filament-tiptap-editor::editor.heading.h5') }}
        </x-filament-tiptap-editor::dropdown-button-item>
        <x-filament-tiptap-editor::dropdown-button-item
            action="editor().chain().focus().toggleHeading({ level: 6 }).run()"
            x-show="buttons.includes('h6')"
            style="display: none;">
            {{ __('filament-tiptap-editor::editor.heading.h6') }}
        </x-filament-tiptap-editor::dropdown-button-item>
    </x-filament-tiptap-editor::dropdown-button>
</div>
