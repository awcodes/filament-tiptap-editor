@props([
    'fieldId' => null,
])
<button type="button"
    x-on:click="openModal()"
    x-on:insert-source.window="$event.detail.fieldId === '{{ $fieldId }}' ? insertSource($event.detail.source) : null"
    @class([
        'rounded block p-1 hover:bg-gray-200 focus:bg-gray-200',
        'dark:hover:bg-gray-800 dark:focus:bg-gray-800' => config(
            'filament.dark_mode'
        ),
    ])
    x-tooltip="'Source Code'"
    x-data="{
        openModal() {
                $dispatch('open-modal', {
                    id: 'filament-tiptap-editor-source-modal',
                    fieldId: '{{ $fieldId }}',
                    source: this.editor().getHTML()
                });
            },
            insertSource(source) {
                this.editor().commands.setContent(source);
            }
    }">
    <svg xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        aria-hidden="true"
        role="img"
        class="w-5 h-5 iconify iconify--ic"
        width="24"
        height="24"
        preserveAspectRatio="xMidYMid meet"
        viewBox="0 0 24 24">
        <path fill="currentColor"
            d="M14 2H6a2 2 0 0 0-2 2v16c0 1.11.89 2 2 2h12c1.11 0 2-.89 2-2V8l-6-6m4 18H6V4h7v5h5v11m-8.46-4.35l2.09 2.09L10.35 19L7 15.65l3.35-3.35l1.28 1.26l-2.09 2.09m7.46 0L13.65 19l-1.27-1.26l2.09-2.09l-2.09-2.09l1.27-1.26L17 15.65Z">
        </path>
    </svg>
    <span class="sr-only">Source Code</span>
</button>
