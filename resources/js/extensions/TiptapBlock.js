import {mergeAttributes, Extension, Node} from "@tiptap/core"
import { Plugin } from 'prosemirror-state'
import { DOMParser } from 'prosemirror-model'

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
                rendered: false
            },
            statePath: {
                default: null,
                rendered: false
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
                rendered: false,
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
        return ({node, extension, getPos, editor}) => {
            const dom = document.createElement('div')
            dom.contentEditable = 'false'
            dom.classList.add('tiptap-block-wrapper')

            dom.innerHTML = `
                <div
                    x-data='{
                        showOptionsButton: ${node.attrs.data?.length === 0 ? 'false' : 'true'},
                        openSettings() {
                            this.$dispatch("open-block-settings", {
                                type: "${node.attrs.type}",
                                statePath: "${node.attrs.statePath}",
                                data: JSON.parse(\`${JSON.stringify(node.attrs.data)}\`),
                            })
                        },
                        deleteBlock() {
                            this.$dispatch("delete-block")
                        }
                    }'
                    class="tiptap-block"
                    style="min-height: 3rem;"
                >
                    <div class="tiptap-block-heading">
                        <h3 class="tiptap-block-title">${node.attrs.label}</h3>
                        <div class="tiptap-block-actions">
                            <button x-show="showOptionsButton" type="button" x-on:click="openSettings">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </button>
                            <button type="button" x-on:click="deleteBlock()">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
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

                if (attributes.coordinates) {
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

                if (!range) {
                    return false
                }

                const currentChain = chain()

                currentChain.insertContentAt({ from: range.start, to: range.end }, { type: this.name, attrs: attributes })

                return currentChain.focus(range.end + 1)
            },
            removeBlock: () => ({ commands }) => {
                return commands.deleteSelection()
            }
        }
    },
})

function wrapHtmlInTemplate(value) {
    const element = document.createElement('span')
    element.innerHTML = `${value.trim()}`
    return element
}

export const DragAndDropBlockExtension = Extension.create({
    name: 'dragAndDropBlock',
    addProseMirrorPlugins() {
        return [
            new Plugin({
                props: {
                    handleDrop(view, event) {
                        if (!event) return false

                        event.preventDefault()

                        const coordinates = view.posAtCoords({
                            left: event.clientX,
                            top: event.clientY,
                        })

                        event.target.dispatchEvent(new CustomEvent('dragged-block', {
                            detail: {
                                type: event.dataTransfer.getData('blockType'),
                                coordinates,
                            },
                            bubbles: true,
                        }))

                        return false
                    },
                },
            }),
        ]
    },
})
