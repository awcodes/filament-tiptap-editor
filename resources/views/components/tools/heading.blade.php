<x-filament-tiptap-editor::dropdown-button
    label="{{ __('filament-tiptap-editor::editor.heading.label') }}"
    active="'heading'"
    icon="heading"
>
    <x-filament-tiptap-editor::dropdown-button-item
        action="editor().chain().focus().toggleHeading({ level: 1 }).run()"
    >
        {{ __('filament-tiptap-editor::editor.heading.h1') }}
    </x-filament-tiptap-editor::dropdown-button-item>

    <x-filament-tiptap-editor::dropdown-button-item
        action="editor().chain().focus().toggleHeading({ level: 2 }).run()"
    >
        {{ __('filament-tiptap-editor::editor.heading.h2') }}
    </x-filament-tiptap-editor::dropdown-button-item>

    <x-filament-tiptap-editor::dropdown-button-item
        action="editor().chain().focus().toggleHeading({ level: 3 }).run()"
    >
        {{ __('filament-tiptap-editor::editor.heading.h3') }}
    </x-filament-tiptap-editor::dropdown-button-item>

    <x-filament-tiptap-editor::dropdown-button-item
        action="editor().chain().focus().toggleHeading({ level: 4 }).run()"
    >
        {{ __('filament-tiptap-editor::editor.heading.h4') }}
    </x-filament-tiptap-editor::dropdown-button-item>

    <x-filament-tiptap-editor::dropdown-button-item
        action="editor().chain().focus().toggleHeading({ level: 5 }).run()"
    >
        {{ __('filament-tiptap-editor::editor.heading.h5') }}
    </x-filament-tiptap-editor::dropdown-button-item>

    <x-filament-tiptap-editor::dropdown-button-item
        action="editor().chain().focus().toggleHeading({ level: 6 }).run()"
    >
        {{ __('filament-tiptap-editor::editor.heading.h6') }}
    </x-filament-tiptap-editor::dropdown-button-item>

</x-filament-tiptap-editor::dropdown-button>
