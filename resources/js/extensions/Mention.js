import { mergeAttributes, Node } from '@tiptap/core'
import { PluginKey } from '@tiptap/pm/state'
import Suggestion from '@tiptap/suggestion'
import tippy from 'tippy.js'

export const MentionPluginKey = new PluginKey('mention')

export const Mention = Node.create({
    name: 'mention',
    
    group: 'inline',
    
    inline: true,
    
    selectable: false,
    
    atom: true,
    
    addAttributes() {
        return {
            id: {
                default: null,
                parseHTML: element => element.getAttribute('data-id'),
                renderHTML: attributes => {
                    if (!attributes.id) {
                        return {}
                    }
                    
                    return {
                        'data-id': attributes.id
                    }
                }
            },
        }
    },
    
    parseHTML() {
        return [
            {
                tag: `span[data-type='${this.name}']`
            }
        ]
    },
    
    renderHTML({ node, HTMLAttributes }) {
        return [
            'span',
            mergeAttributes(
              { 'data-type': this.name },
              HTMLAttributes
            ),
            `@${node.attrs.id}`,
        ]
    },
    
    renderText({ node }) {
        return `@${node.attrs.id}`
    },
    
    addKeyboardShortcuts() {
        return {
            Backspace: () =>
              this.editor.commands.command(({ tr, state }) => {
                  let isMention = false
                  const { selection } = state
                  const { empty, anchor } = selection
                  
                  if (!empty) {
                      return false
                  }
                  
                  state.doc.nodesBetween(anchor - 1, anchor, (node, pos) => {
                      if (node.type.name === this.name) {
                          isMention = true
                          tr.insertText(
                            '@',
                            pos,
                            pos + node.nodeSize
                          )
                          
                          return false
                      }
                  })
                  
                  return isMention
              })
        }
    },
    
    addCommands() {
        return {
            insertMention: (attributes) => ({ chain, state }) => {
                const currentChain = chain()
                
                if (! [null, undefined].includes(attributes.coordinates?.pos)) {
                    currentChain.insertContentAt(
                      { from: attributes.coordinates.pos, to: attributes.coordinates.pos },
                      [
                          { type: this.name, attrs: { id: attributes.tag } },
                          { type: 'text', text: ' ' },
                      ],
                    )
                    
                    return currentChain
                }
            },
        }
    },
    
    addProseMirrorPlugins() {
        return [
            Suggestion({
                editor: this.editor,
                char: '@',
                items: ({ query }) => this.options.mentions.filter(item => item.toLowerCase().startsWith(query.toLowerCase())).slice(0, 5),
                pluginKey: MentionPluginKey,
                command: ({ editor, range, props }) => {
                    const nodeAfter = editor.view.state.selection.$to.nodeAfter
                    const overrideSpace = nodeAfter?.text?.startsWith(' ')
                    
                    if (overrideSpace) {
                        range.to += 1
                    }
                    
                    editor
                      .chain()
                      .focus()
                      .insertContentAt(range, [
                          {
                              type: this.name,
                              attrs: props
                          },
                          {
                              type: 'text',
                              text: ' '
                          },
                      ])
                      .run()
                    
                    window.getSelection()?.collapseToEnd()
                },
                allow: ({ state, range }) => {
                    const $from = state.doc.resolve(range.from)
                    const type = state.schema.nodes[this.name]
                    return !!$from.parent.type.contentMatch.matchType(type)
                },
                render: () => {
                    let component
                    let popup
                    
                    return {
                        onStart: (props) => {
                            if (!props.clientRect) {
                                return
                            }
                            
                            const html = `
                                <div
                                    x-data="{

                                        items: ['${props.items.join('\', \'')}'],

                                        selectedIndex: 0,

                                        init: function () {
                                            this.$el.parentElement.addEventListener(
                                                'mentions-key-down',
                                                (event) => this.onKeyDown(event.detail),
                                            );

                                            this.$el.parentElement.addEventListener(
                                                'mentions-update-items',
                                                (event) => (items = event.detail),
                                            );
                                        },

                                        onKeyDown: function (event) {
                                            if (event.key === 'ArrowUp') {
                                                event.preventDefault();
                                                this.selectedIndex = ((this.selectedIndex + this.items.length) - 1) % this.items.length;

                                                return true;
                                            };

                                            if (event.key === 'ArrowDown') {
                                                event.preventDefault();
                                                this.selectedIndex = (this.selectedIndex + 1) % this.items.length;

                                                return true;
                                            };

                                            if (event.key === 'Enter') {
                                                event.preventDefault();
                                                this.selectItem(this.selectedIndex);

                                                return true;
                                            };

                                            return false;
                                        },

                                        selectItem: function (index) {
                                            const item = this.items[index];

                                            if (! item) {
                                                return;
                                            };

                                            $el.parentElement.dispatchEvent(new CustomEvent('mentions-select', { detail: { item } }));
                                        },

                                    }"
                                    class="tippy-content-p-0"
                                >
                                    <template x-for="(item, index) in items" :key="index">
                                        <button
                                            x-text="item"
                                            x-on:click="selectItem(index)"
                                            :class="{ 'bg-primary-500': index === selectedIndex }"
                                            class="block w-full text-left rounded px-2 py-1"
                                        ></button>
                                    </template>
                                </div>
                            `
                            
                            component = document.createElement('div');
                            component.innerHTML = html;
                            component.addEventListener('mentions-select', (event) => {
                                props.command({ id: event.detail.item });
                            });
                            
                            popup = tippy('body', {
                                getReferenceClientRect: props.clientRect,
                                appendTo: () => document.body,
                                content: component,
                                allowHTML: true,
                                showOnCreate: true,
                                interactive: true,
                                trigger: 'manual',
                                placement: 'bottom-start',
                            });
                        },
                        
                        onUpdate(props) {
                            if (!props.items.length) {
                                popup[0].hide();
                                
                                return;
                            }
                            
                            popup[0].show();
                            
                            component.dispatchEvent(new CustomEvent('mentions-update-items', { detail: props.items }));
                        },
                        
                        onKeyDown(props) {
                            component.dispatchEvent(new CustomEvent('mentions-key-down', { detail: props.event }));
                        },
                        
                        onExit() {
                            popup[0].destroy();
                        },
                    }
                },
            })
        ]
    }
})
