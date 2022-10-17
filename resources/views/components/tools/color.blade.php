@props([
    'fieldId' => null,
])
<div
    class="relative p-1.5 cursor-pointer rounded hover:bg-gray-200 hover:dark:bg-gray-800"
    x-tooltip="'{{ __('filament-tiptap-editor::editor.color') }}'">
    <label>
        <input type="color"
            x-on:input="editor().chain().focus().setColor($event.target.value).run()"
            x-bind:value="editor().getAttributes('textStyle').color || '#000000'"
            class="block w-4 h-4 border-0 cursor-pointer">
        <span class="sr-only">Color</span>
    </label>
</div>
