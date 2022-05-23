@props([
    'level' => '1',
])

<button type="button"
    x-on:click="editor().chain().focus().toggleHeading({ level: {{ $level }} }).run()"
    :class="{ 'active': isActive('heading', { level: {{ $level }} }, updatedAt) }"
    x-tooltip="'Heading {{ $level }}'">
    @switch($level)
        @case('2')
            <svg xmlns="http://www.w3.org/2000/svg"
                xmlns:xlink="http://www.w3.org/1999/xlink"
                aria-hidden="true"
                role="img"
                class="w-5 h-5 iconify iconify--ci"
                width="24"
                height="24"
                preserveAspectRatio="xMidYMid meet"
                viewBox="0 0 24 24">
                <path fill="currentColor"
                    d="M10 4v7H4V4H2v16h2v-7h6v7h2V4h-2Zm12 7.75C22 9.679 20.21 8 18 8s-4 1.679-4 3.75h2.133l.007-.144C16.218 10.707 17.02 10 18 10c1.03 0 1.867.784 1.867 1.75c0 .439-.173.841-.459 1.148L14 18.444V20h8v-2h-4.497l3.516-3.79l.158-.18A3.59 3.59 0 0 0 22 11.75Z">
                </path>
            </svg>
            <span class="sr-only">Heading {{ $level }}</span>
        @break

        @case('3')
            <svg xmlns="http://www.w3.org/2000/svg"
                xmlns:xlink="http://www.w3.org/1999/xlink"
                aria-hidden="true"
                role="img"
                class="w-5 h-5 iconify iconify--ci"
                width="24"
                height="24"
                preserveAspectRatio="xMidYMid meet"
                viewBox="0 0 24 24">
                <path fill="currentColor"
                    d="M10 4v7H4V4H2v16h2v-7h6v7h2V4h-2Zm11.729 6l.002-2H14.5v2h4.378l-3.176 3.283l1.407 1.515c.256-.118.546-.186.854-.186c1.04 0 1.884.768 1.884 1.714c0 .947-.844 1.715-1.884 1.715c-.917 0-1.681-.597-1.849-1.386L14 17.029C14.36 18.722 15.998 20 17.963 20C20.193 20 22 18.355 22 16.326c0-1.691-1.256-3.117-2.968-3.543L21.73 10Z">
                </path>
            </svg>
            <span class="sr-only">Heading {{ $level }}</span>
        @break

        @case('4')
            <svg xmlns="http://www.w3.org/2000/svg"
                xmlns:xlink="http://www.w3.org/1999/xlink"
                aria-hidden="true"
                role="img"
                class="w-5 h-5 iconify iconify--ci"
                width="24"
                height="24"
                preserveAspectRatio="xMidYMid meet"
                viewBox="0 0 24 24">
                <path fill="currentColor"
                    d="M10 4v7H4V4H2v16h2v-7h6v7h2V4h-2Zm11.729 6l.002-2H14.5v2h4.378l-3.176 3.283l1.407 1.515c.256-.118.546-.186.854-.186c1.04 0 1.884.768 1.884 1.714c0 .947-.844 1.715-1.884 1.715c-.917 0-1.681-.597-1.849-1.386L14 17.029C14.36 18.722 15.998 20 17.963 20C20.193 20 22 18.355 22 16.326c0-1.691-1.256-3.117-2.968-3.543L21.73 10Z">
                </path>
            </svg>
            <span class="sr-only">Heading {{ $level }}</span>
        @break

        @case('5')
            <svg xmlns="http://www.w3.org/2000/svg"
                xmlns:xlink="http://www.w3.org/1999/xlink"
                aria-hidden="true"
                role="img"
                class="w-5 h-5 iconify iconify--ci"
                width="24"
                height="24"
                preserveAspectRatio="xMidYMid meet"
                viewBox="0 0 24 24">
                <path fill="currentColor"
                    d="M10 4v7H4V4H2v16h2v-7h6v7h2V4h-2Zm11.745 5.92V8h-6.118l-1.142 6.09l1.846.868a2.087 2.087 0 0 1 1.59-.718c1.127 0 2.04.86 2.04 1.92s-.913 1.92-2.04 1.92c-.93 0-1.715-.587-1.96-1.39L14 17.219C14.488 18.825 16.059 20 17.922 20C20.175 20 22 18.282 22 16.16s-1.825-3.84-4.078-3.84c-.367 0-.721.045-1.058.13l.473-2.53h4.408Z">
                </path>
            </svg>
            <span class="sr-only">Heading {{ $level }}</span>
        @break

        @case('6')
            <svg xmlns="http://www.w3.org/2000/svg"
                xmlns:xlink="http://www.w3.org/1999/xlink"
                aria-hidden="true"
                role="img"
                class="w-5 h-5 iconify iconify--ci"
                width="24"
                height="24"
                preserveAspectRatio="xMidYMid meet"
                viewBox="0 0 24 24">
                <path fill="currentColor"
                    d="M10 4v7H4V4H2v16h2v-7h6v7h2V4h-2Zm7.999 8.32L20.597 8h-2.309l-3.742 6.222A3.71 3.71 0 0 0 14 16.16c0 2.122 1.79 3.84 4 3.84s4-1.718 4-3.84s-1.791-3.84-4.001-3.84ZM16 16.16c0-1.06.895-1.92 2-1.92s2 .86 2 1.92s-.895 1.92-2 1.92s-2-.86-2-1.92Z">
                </path>
            </svg>
            <span class="sr-only">Heading {{ $level }}</span>
        @break

        @default
            <svg xmlns="http://www.w3.org/2000/svg"
                xmlns:xlink="http://www.w3.org/1999/xlink"
                aria-hidden="true"
                role="img"
                class="w-5 h-5 iconify iconify--ci"
                width="24"
                height="24"
                preserveAspectRatio="xMidYMid meet"
                viewBox="0 0 24 24">
                <path fill="currentColor"
                    d="M10 4v7H4V4H2v16h2v-7h6v7h2V4h-2Zm10 16V8h-1.5l-2.5.67v2.07l2-.536V20h2Z"></path>
            </svg>
    @endswitch
</button>
