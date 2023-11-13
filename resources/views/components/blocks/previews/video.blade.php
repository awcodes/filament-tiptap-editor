<div
    @class([
        'responsive' => $responsive
    ])
>
    <iframe
        src="{{ $url }}"
        width="{{ $width }}"
        height="{{ $height }}"
        style="aspect-ratio:{{ $width }}/{{ $height }}; width: 100%; height: auto;"
    />
</div>