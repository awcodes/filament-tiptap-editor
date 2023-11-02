import {mergeAttributes, Node} from "@tiptap/core"

export const TiptapBlock = Node.create({
    name: 'tiptapBlock',
    content: 'inline+',
    group: 'block',
    atom: true,
    draggable: true,
    selectable: true,
    defining: true,
    allowGapCursor: true,
    inline: false,
    addAttributes() {
        return {
            view: {
                default: null,
                parseHTML: element => element.getAttribute('data-block-view'),
                renderHTML: attributes => {
                    return {
                        'data-block-view': attributes.view ?? null
                    }
                }
            },
            blockData: {
                default: null,
                parseHTML: element => element.getAttribute('data-block-data'),
                renderHTML: attributes => {
                    return {
                        'data-block-data': attributes.blockData ?? ''
                    }
                }
            }
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
        return ['tiptap-block', mergeAttributes(HTMLAttributes), 0]
    },
    addNodeView() {
        return ({node, extension, getPos, editor}) => {
            const { view } = editor
            const dom = document.createElement('div')
            dom.setAttribute('data-block-view', node.attrs.view)
            dom.setAttribute('data-block-data', node.attrs.blockData)
            dom.setAttribute('wire:ignore.self', 'true')
            dom.classList.add('relative')

            dom.innerHTML = `
                <div class
            `;

            // dom.addEventListener('update-block', (event) => {
            //     setTimeout(() => {
            //         console.log(event.detail.data)
            //         editor.commands.updateAttributes('tiptapBlock', {blockData: JSON.stringify(event.detail.data)})
            //         editor.commands.focus()
            //     }, 500)
            // })

            return {
                dom,
            }
        }
    },
    addCommands() {
        return {
            insertBlock: (attributes) => ({ commands }) => {
                return commands.setNode(this.name, attributes)
            },
            removeBlock: () => ({ commands }) => {
                return commands.deleteNode(this.name)
            }
        }
    },
})