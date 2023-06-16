import { Extension } from '@tiptap/core'

import { FloatingMenuPlugin } from '../plugins/FloatingMenuPlugin'

export const FloatingMenu = Extension.create({
    name: 'floatingMenu',

    addOptions() {
        return {
            element: null,
            tippyOptions: {},
            pluginKey: 'floatingMenu',
            shouldShow: null,
        }
    },

    addProseMirrorPlugins() {
        if (!this.options.element) {
            return []
        }

        return [
            FloatingMenuPlugin({
                pluginKey: this.options.pluginKey,
                editor: this.editor,
                element: this.options.element,
                tippyOptions: this.options.tippyOptions,
                shouldShow: this.options.shouldShow,
            }),
        ]
    },
})