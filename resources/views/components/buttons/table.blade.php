<div x-data="{
    open: false,
    toggle() {
        if (this.open) {
            return this.close()
        }

        this.$refs.button.focus()

        this.open = true
    },
    close(focusAfter) {
        if (!this.open) return

        this.open = false

        focusAfter && focusAfter.focus()
    }
}"
    x-on:keydown.escape.prevent.stop="close($refs.button)"
    x-on:focusin.window="! $refs.panel.contains($event.target) && close()"
    x-id="['dropdown-button']"
    class="relative flex flex-col items-center">
    <button type="button"
        x-ref="button"
        x-on:click="toggle()"
        :aria-expanded="open"
        :aria-controls="$id('dropdown-button')"
        class="p-2"
        :class="{ 'active' : open }"
        x-tooltip="'Table'">
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
                d="M5 4h14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2m0 4v4h6V8H5m8 0v4h6V8h-6m-8 6v4h6v-4H5m8 0v4h6v-4h-6Z">
            </path>
        </svg>
        <span class="sr-only">Table</span>
    </button>

    <div x-ref="panel"
        x-show="open"
        x-transition.origin.top.left
        x-on:click.outside="close($refs.button)"
        :id="$id('dropdown-button')"
        style="display: none;"
        class="absolute z-30 h-48 overflow-y-scroll text-white bg-gray-900 rounded-md shadow-md top-full"
        style="display: none;">
        <ul class="text-sm divide-y divide-gray-700 min-w-[144px]">
            <li>
                <button type="button"
                    x-on:click="tables.insertTable()"
                    class="block w-full px-3 py-2 text-left whitespace-nowrap hover:bg-primary-500 focus:bg-primary-500 rounded-t-md">Insert
                    Table</button>
            </li>
            <li>
                <button type="button"
                    x-on:click="tables.addColumnBefore()"
                    class="block w-full px-3 py-2 text-left whitespace-nowrap hover:bg-primary-500 focus:bg-primary-500">Add
                    Column Before</button>
            </li>
            <li>
                <button type="button"
                    x-on:click="tables.addColumnAfter()"
                    class="block w-full px-3 py-2 text-left whitespace-nowrap hover:bg-primary-500 focus:bg-primary-500">Add
                    Column After</button>
            </li>
            <li>
                <button type="button"
                    x-on:click="tables.deleteColumn()"
                    class="block w-full px-3 py-2 text-left whitespace-nowrap hover:bg-primary-500 focus:bg-primary-500">Delete
                    Column</button>
            </li>
            <li>
                <button type="button"
                    x-on:click="tables.addRowBefore()"
                    class="block w-full px-3 py-2 text-left whitespace-nowrap hover:bg-primary-500 focus:bg-primary-500">Add
                    Row Before</button>
            </li>
            <li>
                <button type="button"
                    x-on:click="tables.addRowAfter()"
                    class="block w-full px-3 py-2 text-left whitespace-nowrap hover:bg-primary-500 focus:bg-primary-500">Add
                    Row After</button>
            </li>
            <li>
                <button type="button"
                    x-on:click="tables.deleteRow()"
                    class="block w-full px-3 py-2 text-left whitespace-nowrap hover:bg-primary-500 focus:bg-primary-500">Delete
                    Row</button>
            </li>
            <li>
                <button type="button"
                    x-on:click="tables.deleteTable()"
                    class="block w-full px-3 py-2 text-left whitespace-nowrap hover:bg-primary-500 focus:bg-primary-500">Delete
                    Table</button>
            </li>
            <li>
                <button type="button"
                    x-on:click="tables.mergeCells()"
                    class="block w-full px-3 py-2 text-left whitespace-nowrap hover:bg-primary-500 focus:bg-primary-500">Merge
                    Cells</button>
            </li>
            <li>
                <button type="button"
                    x-on:click="tables.splitCell()"
                    class="block w-full px-3 py-2 text-left whitespace-nowrap hover:bg-primary-500 focus:bg-primary-500">Split
                    Cell</button>
            </li>
            <li>
                <button type="button"
                    x-on:click="tables.toggleHeaderColumn()"
                    class="block w-full px-3 py-2 text-left whitespace-nowrap hover:bg-primary-500 focus:bg-primary-500">Toggle
                    Header Column</button>
            </li>
            <li>
                <button type="button"
                    x-on:click="tables.toggleHeaderRow()"
                    class="block w-full px-3 py-2 text-left whitespace-nowrap hover:bg-primary-500 focus:bg-primary-500">Toggle
                    Header Row</button>
            </li>
            <li>
                <button type="button"
                    x-on:click="tables.toggleHeaderCell()"
                    class="block w-full px-3 py-2 text-left whitespace-nowrap hover:bg-primary-500 focus:bg-primary-500">Toggle
                    Header Cell</button>
            </li>
            <li>
                <button type="button"
                    x-on:click="tables.mergeOrSplit()"
                    class="block w-full px-3 py-2 text-left whitespace-nowrap hover:bg-primary-500 focus:bg-primary-500">Merge
                    Or Split</button>
            </li>
            <li>
                <button type="button"
                    x-on:click="tables.setCellAttribute()"
                    class="block w-full px-3 py-2 text-left whitespace-nowrap hover:bg-primary-500 focus:bg-primary-500">Set
                    Cell Attribute</button>
            </li>
            <li>
                <button type="button"
                    x-on:click="tables.fixTables()"
                    class="block w-full px-3 py-2 text-left whitespace-nowrap hover:bg-primary-500 focus:bg-primary-500">Fix
                    Tables</button>
            </li>
            <li>
                <button type="button"
                    x-on:click="tables.goToNextCell()"
                    class="block w-full px-3 py-2 text-left whitespace-nowrap hover:bg-primary-500 focus:bg-primary-500">Go
                    To Next Cell</button>
            </li>
            <li>
                <button type="button"
                    x-on:click="tables.goToPreviousCell()"
                    class="block w-full px-3 py-2 text-left whitespace-nowrap hover:bg-primary-500 focus:bg-primary-500 rounded-b-md">Go
                    To Previous Cell</button>
            </li>
        </ul>
    </div>
</div>
