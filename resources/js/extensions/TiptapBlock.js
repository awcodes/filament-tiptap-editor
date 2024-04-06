import {mergeAttributes, Node} from "@tiptap/core"

export const TiptapBlock = Node.create({
    name: 'tiptapBlock',
    group: 'block',
    atom: true,
    defining: true,
    draggable: true,
    selectable: true,
    isolating: true,
    allowGapCursor: true,
    inline: false,
    addAttributes() {
        return {
            preview: {
                default: null,
                parseHTML: element => {
                    return element.getAttribute('data-preview')
                },
                renderHTML: attributes => {
                    if (! attributes.preview) {
                        return null
                    }

                    return {
                        'data-preview': attributes.preview
                    }
                }
            },
            statePath: {
                default: null,
                parseHTML: element => {
                    return element.getAttribute('data-state-path')
                },
                renderHTML: attributes => {
                    if (! attributes.statePath) {
                        return null
                    }

                    return {
                        'data-state-path': attributes.statePath
                    }
                }
            },
            type: {
                default: null,
                parseHTML: element => {
                    return element.getAttribute('data-type')
                },
                renderHTML: attributes => {
                    if (! attributes.type) {
                        return null
                    }

                    return {
                        'data-type': attributes.type
                    }
                }
            },
            label: {
                default: null,
                parseHTML: element => {
                    return element.getAttribute('data-label')
                },
                renderHTML: attributes => {
                    if (! attributes.label) {
                        return null
                    }

                    return {
                        'data-label': attributes.label
                    }
                }
            },
            data: {
                default: null,
                parseHTML: element => {
                    return element.getAttribute('data-data')
                },
                renderHTML: attributes => {
                    if (! attributes.data) {
                        return null
                    }

                    return {
                        'data-data': JSON.stringify(attributes.data)
                    }
                }
            },
        }
    },
    parseHTML() {
        return [
            {
                tag: 'tiptap-block',
            }
        ]
    },
    renderHTML({ HTMLAttributes }) {
        return ['tiptap-block', mergeAttributes(HTMLAttributes)]
    },
    addNodeView() {
        return ({node}) => {
            const dom = document.createElement('div')
            dom.contentEditable = 'false'
            dom.classList.add('tiptap-block-wrapper')

            let data = typeof node.attrs.data === 'object'
                ? JSON.stringify(node.attrs.data)
                : node.attrs.data

            dom.innerHTML = `
                <div
                    x-data="{
                        showOptionsButton: ${data === '[]' ? 'false' : 'true'},
                        openSettings() {
                            this.$dispatch('open-block-settings', {
                                type: \`${node.attrs.type}\`,
                                statePath: \`${node.attrs.statePath}\`,
                                data: ${data},
                            })
                        },
                        deleteBlock() {
                            this.$dispatch('delete-block')
                        }
                    }"
                    class="tiptap-block"
                    style="min-height: 3rem;"
                >
                    <div class="tiptap-block-heading">
                        <h3 class="tiptap-block-title">${node.attrs.label}</h3>
                        <div x-show="! disabled" class="tiptap-block-actions">
                            <button x-show="showOptionsButton" type="button" x-on:click="openSettings">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M7.84 1.804A1 1 0 018.82 1h2.36a1 1 0 01.98.804l.331 1.652a6.993 6.993 0 011.929 1.115l1.598-.54a1 1 0 011.186.447l1.18 2.044a1 1 0 01-.205 1.251l-1.267 1.113a7.047 7.047 0 010 2.228l1.267 1.113a1 1 0 01.206 1.25l-1.18 2.045a1 1 0 01-1.187.447l-1.598-.54a6.993 6.993 0 01-1.929 1.115l-.33 1.652a1 1 0 01-.98.804H8.82a1 1 0 01-.98-.804l-.331-1.652a6.993 6.993 0 01-1.929-1.115l-1.598.54a1 1 0 01-1.186-.447l-1.18-2.044a1 1 0 01.205-1.251l1.267-1.114a7.05 7.05 0 010-2.227L1.821 7.773a1 1 0 01-.206-1.25l1.18-2.045a1 1 0 011.187-.447l1.598.54A6.993 6.993 0 017.51 3.456l.33-1.652zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <button type="button" x-on:click="deleteBlock()">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="preview">
                        ${node.attrs.preview}
                    </div>
                </div>
            `;

            return {
                dom,
            }
        }
    },
    addCommands() {
        return {
            insertBlock: (attributes) => ({ chain, state }) => {
                const currentChain = chain()

                if (! [null, undefined].includes(attributes.coordinates?.pos)) {
                    currentChain.insertContentAt({ from: attributes.coordinates.pos, to: attributes.coordinates.pos }, { type: this.name, attrs: attributes })

                    return currentChain.setTextSelection(attributes.coordinates.pos)
                }

                const { selection } = state
                const { $from, $to } = selection

                const range = $from.blockRange($to)

                if (!range) {
                    if ($to.parentOffset === 0) {
                        currentChain
                            .insertContentAt(Math.max($to.pos - 1, 0), { type: 'paragraph' })
                            .insertContentAt({ from: $from.pos, to: $to.pos }, { type: this.name, attrs: attributes })
                    } else {
                        currentChain
                            .setNode({ type: 'paragraph' })
                            .insertContentAt({ from: $from.pos, to: $to.pos }, { type: this.name, attrs: attributes })
                    }

                    return currentChain.setTextSelection($to.pos + 1)
                } else {
                    if ($to.parentOffset === 0) {
                        currentChain.insertContentAt(Math.max($to.pos - 1, 0), { type: this.name, attrs: attributes })
                    } else {
                        currentChain.insertContentAt({ from: range.start, to: range.end }, { type: this.name, attrs: attributes })
                    }

                    return currentChain.setTextSelection(range.end)
                }
            },
            updateBlock: (attributes) => ({ chain, state }) => {
                const { selection } = state
                const { $from, $to } = selection
                const range = $from.blockRange($to)
                const currentChain = chain()

                if (!range) {
                    currentChain.insertContentAt({ from: $from.pos, to: $from.pos + 1 }, { type: this.name, attrs: attributes })
                    return false
                }

                currentChain.insertContentAt({ from: range.start, to: range.end }, { type: this.name, attrs: attributes })

                return currentChain.focus(range.end + 1)
            },
            removeBlock: () => ({ commands }) => {
                return commands.deleteSelection()
            }
        }
    },
})
