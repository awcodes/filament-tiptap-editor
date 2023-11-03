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

            let dataRender = '';

            for (const [key, value] of Object.entries(node.attrs.blockData)) {
                dataRender = dataRender + (`<p>${key}: ${value}</p>`);
            }

            window.addEventListener('update-block', (event) => {
                console.log(event);
                setTimeout(() => {
                    node.attrs.blockData = JSON.stringify(event.detail.data)
                    editor.commands.focus()
                }, 500)
            })

            dom.innerHTML = `
                <div x-data='{
                    preview() {
                        this.$dispatch("preview-block", {view: "${node.attrs.view}", data: ${JSON.stringify(node.attrs.blockData)}})
                    },
                    openSettings() {
                        this.$dispatch("render-bus", {view: "${node.attrs.view}", data: ${JSON.stringify(node.attrs.blockData)}})
                    }
                }' class="relative p-4" style="min-height: 3rem;">
                    <div class="absolute top-2 right-2 flex items-center gap-2">
                        <button type="button" x-on:click="preview">Preview</button>
                        <button type="button" x-on:click="openSettings">Settings</button>
                    </div>
                    <h3>${node.attrs.name ?? 'Custom Block'}</h3>
                    ${dataRender}
                </div>
            `;

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