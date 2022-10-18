import {Node, mergeAttributes} from "@tiptap/core";

export const Hurdle = Node.create({
    name: "hurdle",
    group: "block",
    content: "block+",
    addOptions() {
        return {
            colors: ['gray_light', 'gray', 'gray_dark', 'primary', 'secondary', 'tertiary', 'accent'],
            HTMLAttributes: {
                class: "filament-tiptap-hurdle",
            },
        };
    },
    addAttributes() {
        return {
            color: {
                default: 'gray',
                parseHTML: element => element.getAttribute('data-color'),
                renderHTML: attributes => {
                    return {
                        'data-color': attributes.color,
                    }
                }
            },
        }
    },
    parseHTML() {
        return [
            {
                tag: "div",
                getAttrs: (element) => element.classList.contains("filament-tiptap-hurdle"),
            },
        ];
    },
    renderHTML({node, HTMLAttributes}) {
        return ["div", mergeAttributes(this.options.HTMLAttributes, HTMLAttributes), 0];
    },
    addCommands() {
        return {
            setHurdle: attributes => ({commands}) => {
                if (!this.options.colors.includes(attributes.color)) {
                    return false
                }
                return commands.toggleWrap(this.name, attributes);
            },
        };
    },
});
