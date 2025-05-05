import Suggestion from '@tiptap/suggestion'
import tippy from 'tippy.js'
import { Mention } from '@tiptap/extension-mention'
import getContent from './get-content.js'

let _query = ''
let debounceTimeout;

export const CustomMention = Mention.extend({

  addOptions() {
    return {
      ...this.parent?.(),
      HTMLAttributes: {
        class: 'mention',
      },
    }
  },

  addAttributes() {
    return {
      ...this.parent?.(),
      href: {
        default: null,
        parseHTML: element => element.getAttribute('data-href'),
        renderHTML: attributes => {
          if (!attributes.href) {
            return {}
          }

          return {
            'data-href': attributes.href,
          }
        },
      },
      type: {
        default: null,
        parseHTML: element => element.getAttribute('data-type'),
        renderHTML: attributes => {
          if (!attributes.type) {
            return {}
          }

          return {
            'data-type': attributes.type,
          }
        },
      },
      target: {
        default: null,
        parseHTML: element => element.getAttribute('data-target'),
        renderHTML: attributes => {
          if (!attributes.target) {
            return {}
          }

          return {
            'data-target': attributes.target,
          }
        },
      },
      data: {
        default: [],
        parseHTML: element => element.getAttribute('data-mention-data'),
        renderHTML: attributes => {
          if (!attributes.data) {
            return {}
          }

          return {
            'data-data': attributes.data,
          }
        },
      },
    }
  },

  addProseMirrorPlugins() {
    return [
      Suggestion({
        editor: this.editor,
        char: this.options.mentionTrigger ?? '@',
        items: async ({ query }) => {
          _query = query

          window.dispatchEvent(new CustomEvent('update-mention-query', { detail: { query: query } }))

          if(this.options.mentionItemsPlaceholder && !query) {
            return [];
          }

          if (this.options.getMentionItemsUsingEnabled) {
            window.dispatchEvent(new CustomEvent('mention-loading-start'));
            clearTimeout(debounceTimeout);
            return new Promise((resolve) => {
              debounceTimeout = setTimeout(async () => {
                const results = await this.options.getSearchResultsUsing(_query);
                resolve(results);
              }, this.options.mentionDebounce);
            });
          }

          let result = [];

          switch (this.options.mentionSearchStrategy) {
            case 'starts_with':
              result = this.options.mentionItems
                .filter((item) => item['label'].toLowerCase().startsWith(query.toLowerCase()));
              break;

            case 'tokenized':
              let tokens = query.toLowerCase().split(/\s+/);
              result = this.options.mentionItems.filter((item) =>
                tokens.every(token => item['label'].toLowerCase().includes(token))
              );
              break;
          }

          if (this.options.maxMentionItems) {
            result = result.slice(0, this.options.maxMentionItems)
          }

          return result
        },
        command: ({ editor, range, props }) => {
          let currentPosition = editor.state.selection.$anchor.pos;
          let deleteFrom = currentPosition - _query.length - 1;

          editor
            .chain()
            .focus()
            .deleteRange({ from: deleteFrom, to: currentPosition })
            .insertContentAt(deleteFrom, [
              {
                type: 'mention',
                attrs: props,
              },
              {
                type: 'text',
                text: ' ',
              },
            ])
            .run()

          window.getSelection()?.collapseToEnd()

          _query = '';
        },
        render: () => {
          let component
          let popup

          return {
            onBeforeStart: (props) => {
              component = getContent(
                props,
                this.options.emptyMentionItemsMessage,
                this.options.mentionItemsPlaceholder,
                this.options.mentionItemsLoading,
                _query
              )
              if (!props.clientRect) {
                return
              }

              popup = tippy('body', {
                getReferenceClientRect: props.clientRect,
                appendTo: () => document.body,
                content: () => component,
                allowHTML: true,
                showOnCreate: true,
                interactive: true,
                trigger: 'manual',
                placement: 'bottom-start',
              })
            },

            onStart: (props) => {
                window.dispatchEvent(new CustomEvent('update-props', { detail: props }));
            },

            onUpdate(props) {
              window.dispatchEvent(new CustomEvent('update-props', { detail: props }));
              if (!props.clientRect) {
                return
              }
            },

            onKeyDown(props) {
              window.dispatchEvent(new CustomEvent('suggestion-keydown', { detail: props }))
              if (['ArrowUp', 'ArrowDown', 'Enter'].includes(props.event.key)) {
                return true
              }
              return false
            },

            onExit() {
              popup[0].destroy()
            },
          }
        },
      }),
    ]
  },
})
