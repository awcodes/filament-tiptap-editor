export default (props, emptyMentionItemsMessage, mentionItemsPlaceholder, mentionItemsLoading, initialQuery) => {
    Alpine.data('suggestions', () => ({
        items: props.items,
        query: initialQuery,
        selectedIndex: 0,
        emptyMentionItemsMessage: emptyMentionItemsMessage,
        mentionItemsPlaceholder: mentionItemsPlaceholder,
        mentionItemsLoading: mentionItemsLoading,
        isLoading: !mentionItemsPlaceholder,
        init() {
            this.$watch('items', () => {
                this.isLoading = false;
            });
        },
        rootEvents: {
            ['@update-props.window'](e) {
                this.items = e.detail.items || [];
            },
            ['@update-mention-query.window'](e) {
                this.query = e.detail.query || '';
            },
            ['@mention-loading-start.window'](e) {
                this.isLoading = true;
            },
            ['@suggestion-keydown.window.stop'](e) {
                this.onKeyDown(e.detail);
            },
        },

        selectItem(index) {
            const item = this.items[index];

            if (item) {
                props.command(item);
            }
        },
        onKeyDown({ event }) {
            if (event.key === 'ArrowUp') {
                this.selectedIndex =
                    (this.selectedIndex + this.items.length - 1) % this.items.length;
                return true;
            }

            if (event.key === 'ArrowDown') {
                this.selectedIndex = (this.selectedIndex + 1) % this.items.length;
                return true;
            }

            if (event.key === 'Enter') {
                this.selectItem(this.selectedIndex);
                return true;
            }

            return false;
        },
    }));
    return `
<div class="mention-dropdown" x-data=suggestions x-bind="rootEvents">
    <!-- Loading spinner -->
    <template x-if="isLoading && ! (mentionItemsPlaceholder && !query.length)">
      <div>
        <div class="px-2 py-1" x-show="!mentionItemsLoading">
          <svg class="animate-spin size-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
        </div>
        <p x-text="mentionItemsLoading"></p>
      </div>
    </template>

    <!-- Results list -->
     <template x-for="(item, index) in items" :key="index">
        <button
            x-on:click="selectItem(index)"
            x-show="!isLoading"
            :class="{ 'bg-primary-500': index === selectedIndex }"
            class="w-full text-left rounded px-2 py-1 hover:bg-white/20 flex items-center">
            <img 
                x-show="item['image']"
                :src="item['image'] || ''"
                :class="{ 'rounded-full': item['roundedImage'] }"
                class="size-5 object-cover mr-2"
            />
            <span x-text="item['label']"></span>
        </button>
    </template>

    <!-- No results found -->
    <template x-if="!isLoading && ! items.length && (mentionItemsPlaceholder ? query.length > 0 : true)" >
      <p x-text="emptyMentionItemsMessage"></p>
    </template>
    
    <!--  Placeholder -->
    <template x-if="mentionItemsPlaceholder && ! query.length">
      <p x-text="mentionItemsPlaceholder"></p>
    </template>
</div>`;
};
