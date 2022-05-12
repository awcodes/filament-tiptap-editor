@props([
    'fieldId' => null,
])
<div class="relative p-2"
    x-tooltip="'Color'">
    <label>
        <input type="color"
            x-on:input="editor().chain().focus().setColor($event.target.value).run()"
            x-bind:value="editor().getAttributes('textStyle').color || '#000000'"
            class="block w-4 h-4 border-0">
        <span class="sr-only">Color</span>
    </label>
</div>
