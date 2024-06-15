@props([
    'statePath' => null,
    'tools' => [],
])

@if (in_array('link', $tools))
<div
    class="flex gap-1 items-center"
    x-show="editor().isActive('link', updatedAt)"
    style="display: none;"
>
    <span x-text="editor().getAttributes('link', updatedAt).href" class="max-w-xs truncate overflow-hidden whitespace-nowrap"></span>
    <x-filament-tiptap-editor::tools.link :state-path="$statePath" icon="edit" :active="false" label="{{ trans('filament-tiptap-editor::editor.link.edit') }}"/>
    <x-filament-tiptap-editor::tools.remove-link />
</div>
@endif
