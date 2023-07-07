import Heading from '@tiptap/extension-heading'

export const CustomHeading = Heading.extend({

    addAttributes() {
        return {
            ...this.parent?.(),
            id: {
                default: null,
            },
        };
    },

})
