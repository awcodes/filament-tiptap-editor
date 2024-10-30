import { mergeAttributes, Node } from "@tiptap/core";

export const Video = Node.create({
    name: "video",

    selectable: true,

    draggable: true,

    atom: true,

    inline() {
        return this.options.inline;
    },

    group() {
        return this.options.inline ? "inline" : "block";
    },

    addOptions() {
        return {
            inline: false,
            HTMLAttributes: {
                autoplay: null,
                controls: null,
                loop: null,
            },
            width: 640,
            height: 480,
        };
    },

    addAttributes() {
        return {
            style: {
                default: null,
                parseHTML: (element) => element.getAttribute("style"),
            },
            responsive: {
                default: true,
                parseHTML: (element) => element.classList.contains("responsive") ?? false,
            },
            src: {
                default: null,
            },
            width: {
                default: this.options.width,
                parseHTML: (element) => element.getAttribute("width"),
            },
            height: {
                default: this.options.height,
                parseHTML: (element) => element.getAttribute("height"),
            },
            autoplay: {
                default: null,
                parseHTML: (element) => element.getAttribute("autoplay"),
            },
            controls: {
                default: null,
                parseHTML: (element) => element.getAttribute("controls"),
            },
            loop: {
                default: null,
                parseHTML: (element) => element.getAttribute("loop"),
            },
            'data-aspect-width': {
                default: null,
                parseHTML: (element) => element.getAttribute("data-aspect-width"),
            },
            'data-aspect-height': {
                default: null,
                parseHTML: (element) => element.getAttribute("data-aspect-height"),
            },
        };
    },

    parseHTML() {
        return [
            {
                tag: "div[data-native-video] video",
            },
        ];
    },

    addCommands() {
        return {
            setVideo:
                (options) =>
                    ({ commands }) => {
                        return commands.insertContent({
                            type: this.name,
                            attrs: options,
                        });
                    },
        };
    },

    renderHTML({ HTMLAttributes }) {
        return [
            "div",
            { "data-native-video": "", class: HTMLAttributes.responsive ? "responsive" : null },
            [
                "video",
                {
                    src: HTMLAttributes.src,
                    width: HTMLAttributes.width,
                    height: HTMLAttributes.height,
                    autoplay: HTMLAttributes.autoplay ? 'true' : null,
                    controls: HTMLAttributes.controls ? 'true' : null,
                    loop: HTMLAttributes.loop ? 'true' : null,
                    style: HTMLAttributes.responsive ? `aspect-ratio: ${HTMLAttributes['data-aspect-width']} / ${HTMLAttributes['data-aspect-height']}; width: 100%; height: auto;` : null,
                    'data-aspect-width': HTMLAttributes.responsive ? HTMLAttributes['data-aspect-width'] : null,
                    'data-aspect-height': HTMLAttributes.responsive ? HTMLAttributes['data-aspect-height'] : null,
                },
            ],
        ];
    },
});
