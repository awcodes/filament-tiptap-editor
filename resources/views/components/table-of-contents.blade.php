@props([
    'headings' => [],
    'depth' => 0,
])
<ul class="filament-tiptap-contents-list" data-list-depth="{{$depth}}">
    @foreach ($headings as $heading)
        <li class="filament-tiptap-contents-item">
            <a class="filament-tiptap-contents-url" href="#{{ $heading['id'] }}">{{ $heading['text'] }}</a>
            @if (array_key_exists('subs', $heading))
                <x-filament-tiptap-editor::table-of-contents :headings="$heading['subs']" :depth="$heading['depth']" />
            @endif
        </li>
    @endforeach
</ul>
