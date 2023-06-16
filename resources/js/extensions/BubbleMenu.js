import { Extension } from "@tiptap/core"

import { BubbleMenuPlugin } from "../plugins/BubbleMenuPlugin"

export const BubbleMenu = Extension.create({
    name: "bubbleMenu",

    addOptions() {
        return {
            element: null,
            tippyOptions: {},
            pluginKey: "bubbleMenu",
            updateDelay: undefined,
            shouldShow: null
        }
    },

    addProseMirrorPlugins() {

        if (!this.options.element) {
            return []
        }

        return [
            BubbleMenuPlugin({
                pluginKey: this.options.pluginKey,
                editor: this.editor,
                element: this.options.element,
                tippyOptions: this.options.tippyOptions,
                updateDelay: this.options.updateDelay,
                shouldShow: this.options.shouldShow
            })
        ]
    }
})