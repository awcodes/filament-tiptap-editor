@props([
    'statePath' => null,
    'tools' => [],
])

<div x-ref="defaultBubbleMenu" class="flex gap-1 items-center" x-cloak>
    @if (in_array('bold', $tools)) <x-filament-tiptap-editor::tools.bold /> @endif
    @if (in_array('italic', $tools)) <x-filament-tiptap-editor::tools.italic /> @endif
    @if (in_array('strike', $tools)) <x-filament-tiptap-editor::tools.strike /> @endif
    @if (in_array('underline', $tools)) <x-filament-tiptap-editor::tools.underline /> @endif
    @if (in_array('superscript', $tools)) <x-filament-tiptap-editor::tools.superscript /> @endif
    @if (in_array('subscript', $tools)) <x-filament-tiptap-editor::tools.subscript /> @endif
    @if (in_array('lead', $tools)) <x-filament-tiptap-editor::tools.lead /> @endif
    @if (in_array('small', $tools)) <x-filament-tiptap-editor::tools.small /> @endif
    @if (in_array('link', $tools)) <x-filament-tiptap-editor::tools.link :state-path="$statePath"/> @endif
</div>


