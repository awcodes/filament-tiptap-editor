import { Node, mergeAttributes } from "@tiptap/core";

export const Hurdle = Node.create({
  name: "hurdle",
  group: "block",
  content: "block+",
  addOptions() {
    return {
      HTMLAttributes: {
        class: "filament-tiptap-hurdle",
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
      toggleHurdle:
        () =>
        ({ commands }) => {
          return commands.toggleWrap(this.name);
        },
    };
  },
});
