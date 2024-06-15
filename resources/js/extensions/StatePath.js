import {Extension} from '@tiptap/core'

export const StatePath = Extension.create({
    name: 'statePath',

    addOptions() {
        return {
            statePath: null,
        }
    },

    addStorage() {
        return {
            statePath: null
        }
    },

    onCreate() {
        this.storage.statePath = this.options.statePath
    },

    addCommands() {
        return {
            getStatePath: () => () => {
                return this.storage.statePath;
            },
        };
    }
})
