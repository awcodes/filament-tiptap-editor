@props([
    'statePath' => null,
    'tools' => [],
])

@if (in_array('media', $tools))
<div
    class="flex gap-1 items-center"
    x-show="editor().isActive('image', updatedAt)"
    style="display: none;"
>
    <x-filament-tiptap-editor::tools.edit-media :state-path="$statePath" icon="edit" :active="false" />
</div>
@endif
