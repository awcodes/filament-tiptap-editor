import { Extension } from '@tiptap/core'

export const CustomTextAlign = Extension.create({
    name: 'textAlign',

    addOptions() {
        return {
            types: [],
            alignments: ['start', 'center', 'end', 'justify'],
            defaultAlignment: 'start',
        }
    },

    addGlobalAttributes() {
        return [
            {
                types: this.options.types,
                attributes: {
                    textAlign: {
                        default: this.options.defaultAlignment,
                        parseHTML: element => element.style.textAlign || this.options.defaultAlignment,
                        renderHTML: attributes => {
                            if (attributes.style && attributes.style.includes('text-align')) {
                                return {}
                            }

                            if (attributes.textAlign === this.options.defaultAlignment) {
                                return {}
                            }

                            return { style: `text-align: ${attributes.textAlign}` }
                        },
                    },
                },
            },
        ]
    },

    addCommands() {
        return {
            setTextAlign: (alignment) => ({ commands }) => {
                if (!this.options.alignments.includes(alignment)) {
                    return false
                }

                return this.options.types.every(type => commands.updateAttributes(type, { textAlign: alignment }))
            },

            unsetTextAlign: () => ({ commands }) => {
                return this.options.types.every(type => commands.resetAttributes(type, 'textAlign'))
            },
        }
    },

    addKeyboardShortcuts() {
        return {
            'Mod-Shift-l': () => this.editor.commands.setTextAlign('start'),
            'Mod-Shift-e': () => this.editor.commands.setTextAlign('center'),
            'Mod-Shift-r': () => this.editor.commands.setTextAlign('end'),
            'Mod-Shift-j': () => this.editor.commands.setTextAlign('justify'),
        }
    },
})
