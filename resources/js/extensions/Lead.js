import { Node, mergeAttributes } from "@tiptap/core";

export const Lead = Node.create({
  name: "lead",
  group: "block",
  content: "block+",
  addOptions() {
    return {
      HTMLAttributes: {
        class: "lead",
      },
    };
  },
  parseHTML() {
    return [
      {
        tag: "div",
        getAttrs: (element) => element.classList.contains("lead"),
      },
    ];
  },
  renderHTML({ node, HTMLAttributes }) {
    return ["div", mergeAttributes(this.options.HTMLAttributes, HTMLAttributes), 0];
  },
  addCommands() {
    return {
      toggleLead:
        () =>
        ({ commands }) => {
          return commands.toggleWrap(this.name);
        },
    };
  },
});
