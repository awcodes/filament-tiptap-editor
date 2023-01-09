@props([
    'action' => null,
])

<li {{ $attributes->except('action') }}>
    <button type="button"
        x-on:click="{{ $action }}; $refs.panel.close();"
        class="block w-full px-3 py-2 text-left whitespace-nowrap hover:bg-primary-500 focus:bg-primary-500">
        {{ $slot }}
    </button>
</li>
