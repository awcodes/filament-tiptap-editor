import { Extension } from "@tiptap/core";

export const DragAndDropExtension = Extension.create({
    name: 'dragAndDrop',
    addProseMirrorPlugins() {
        return [
            new Plugin({
                props: {
                    handleDrop(view, event) {
                        if (!event) return false

                        event.preventDefault()

                        const coordinates = view.posAtCoords({
                            left: event.clientX,
                            top: event.clientY,
                        })

                        if (event.dataTransfer.getData('block')) {
                            event.target.dispatchEvent(new CustomEvent('dragged-block', {
                                detail: {
                                    type: event.dataTransfer.getData('block'),
                                    coordinates,
                                },
                                bubbles: true,
                            }))

                            return false
                        }

                        if (event.dataTransfer.getData('mergeTag')) {
                            const nodeAfter = view.state.selection.$to.nodeAfter;
                            const overrideSpace = nodeAfter?.text?.startsWith(' ');

                            const range = { from: coordinates.pos, to: coordinates.pos };

                            if (overrideSpace) {
                                range.to += 1;
                            }

                            view.state.tr
                                .chain()
                                .focus()
                                .insertContentAt(range, [
                                    {
                                        type: this.name,
                                        attrs: { id: 'name', 'label': 'name' }
                                    },
                                    {
                                        type: 'text',
                                        text: ' '
                                    },
                                ])
                                .run();

                            window.getSelection()?.collapseToEnd();

                            return false;
                        }

                        return false;
                    },
                },
            }),
        ]
    },
})
