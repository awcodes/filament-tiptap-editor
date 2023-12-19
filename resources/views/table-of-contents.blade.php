<ul>
    @foreach ($headings as $heading)
        @if (array_key_exists($loop->index + 1, $headings) && $headings[$loop->index + 1]['level'] === $heading['level'])
            <li>
                <a href="#{{ $heading['id'] }}">{{ $heading['text'] }}</a>
            </li>
        @else
        @endif
        <li>
            @if ($heading[$loop->index + 1] && $heading[$loop->index + 1]['level'] > $heading['level'])
                <ul>
            @endif
                    <a href="#{{ $heading['id'] }}">{{ $heading['text'] }}</a>
            @if ($heading[$loop->index + 1] && $heading[$loop->index + 1]['level'] > $heading['level'])
                <ul>
            @endif
        </li>
    @endforeach
</ul>