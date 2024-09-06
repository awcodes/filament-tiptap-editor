<ul>
    @foreach ($headings as $heading)
        <li>
            <a href="#{{ $heading['id'] }}">{{ $heading['text'] }}</a>
            @if (array_key_exists('subs', $heading))
                <x-filament-tiptap-editor::table-of-contents :headings="$heading['subs']" />
            @endif
        </li>
    @endforeach
</ul>
