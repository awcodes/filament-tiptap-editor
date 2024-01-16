import {Extension} from '@tiptap/core'

export const StyleExtension = Extension.create({
    name: 'styleExtension',

    addGlobalAttributes() {
        return [
            {
                types: [
                    'heading',
                    'paragraph',
                    'link',
                    'image',
                    'listItem',
                    'bulletList',
                    'orderedList',
                    'table',
                    'tableHeader',
                    'tableRow',
                    'tableCell',
                    'textStyle',
                ],
                attributes: {
                    style: {
                        default: null,
                        parseHTML: element => element.getAttribute('style') ?? null,
                        renderHTML: attributes => {
                            if (!attributes.style) {
                                return null;
                            }
                            return {
                                style: attributes.style
                            }
                        },
                    },
                },
            },
        ]
    }
})