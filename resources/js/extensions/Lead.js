import { Node, mergeAttributes, wrappingInputRule } from "@tiptap/core";

const Lead = Node.create({
    name: "lead",
    group: "block",
    content: "inline*",
    addAttributes() {
        return {
            "data-lead": { default: null },
        };
    },
    parseHTML() {
        return [
            {
                tag: "p",
                getAttrs: (element) => element.getAttribute("data-lead"),
            },
        ];
    },
    renderHTML({ HTMLAttributes }) {
        return ["p", HTMLAttributes, 0];
    },
    addCommands() {
        return {
            toggleLead:
                () =>
                ({ commands, state }) => {
                    const { selection, tr } = state;
                    const attrs = tr.curSelection.$anchor.parent.attrs;
                    if ("data-lead" in attrs) {
                        return commands.setNode(this.name, {
                            "data-lead": null,
                        });
                    }

                    return commands.setNode(this.name, {
                        "data-lead": true,
                    });
                },
        };
    },
});

export default Lead;
