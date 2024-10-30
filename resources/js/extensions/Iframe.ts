/* eslint-disable @typescript-eslint/no-explicit-any */
import { Node, mergeAttributes } from "@tiptap/core";

export interface IframeOptions {
    allowFullscreen: boolean;
    scrolling: "yes" | "no" | "auto";
    HTMLAttributes: {
        [key: string]: any;
    };
}

declare module "@tiptap/core" {
    interface Commands<ReturnType> {
        iframe: {
            /**
             * Add an iframe
             */
            setIframe: (options: { src: string }) => ReturnType;
        };
    }
}

export const Iframe = Node.create<IframeOptions>({
    name: "iframe",
    group: "block",
    inline: false,
    atom: true,
    draggable: true,

    addOptions() {
        return {
            allowFullscreen: true,
            scrolling: "no",
            HTMLAttributes: {
                frameborder: 0,
            },
        };
    },

    addAttributes() {
        return {
            src: {
                default: null,
            },
            frameborder: {
                default: 0,
            },
            scrolling: {
                default: this.options.scrolling,
                parseHTML: () => this.options.scrolling,
            },
            allowfullscreen: {
                default: this.options.allowFullscreen,
                parseHTML: () => this.options.allowFullscreen,
            },
            width: {
                renderHTML: (attributes) => {
                    return attributes.width
                        ? {
                              width:
                                  attributes.width === "100%"
                                      ? "100%"
                                      : parseInt(attributes.width),
                          }
                        : {};
                },
                parseHTML: (element) => element.getAttribute("width"),
            },
            height: {
                renderHTML: (attributes) => {
                    return attributes.height
                        ? {
                              height: parseInt(attributes.height),
                          }
                        : {};
                },
                parseHTML: (element) => element.getAttribute("height"),
            },
            style: {
                renderHTML: (attributes) => {
                    return attributes.style
                        ? {
                              style: attributes.style,
                          }
                        : {};
                },
                parseHTML: (element) => element.getAttribute("style"),
            },
            HTMLAttributes: {
                default: null,
                renderHTML: (attributes) => {
                    return attributes.HTMLAttributes || {};
                },
            },
        };
    },

    parseHTML() {
        return [
            {
                tag: "iframe",
            },
        ];
    },

    renderHTML({ HTMLAttributes }) {
        return [
            "iframe",
            mergeAttributes(this.options.HTMLAttributes, HTMLAttributes),
        ];
    },

    addCommands() {
        return {
            setIframe: (options: { src: string,  }) => {
                return ({ commands }) => {
                    return commands.insertContent({
                        type: this.name,
                        attrs: options,
                    });
                };
            },
        };
    },
});
