<ul>
    @foreach ($headings as $heading)

        <li>

            <a href="#{{ $heading['id'] }}">{{ $heading['text'] }}</a>

            @if (array_key_exists('subs', $heading))
                <ul>
                    <x-contents-table :headings="$heading['subs']" />
                </ul>
            @endif

        </li>

    @endforeach
</ul>
