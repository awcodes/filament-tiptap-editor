import { Node, mergeAttributes } from "@tiptap/core";

export const GridColumn = Node.create({
  name: "gridColumn",
  content: "block+",
  gridRole: "column",
  isolating: true,
  addOptions() {
    return {
      HTMLAttributes: {
        class: "filament-tiptap-grid__column",
      },
    };
  },
  parseHTML() {
    return [
      {
        tag: "div",
        getAttrs: (node) => node.classList.contains("filament-tiptap-grid__column") && null,
      },
    ];
  },
  renderHTML({ HTMLAttributes }) {
    return ["div", mergeAttributes(this.options.HTMLAttributes, HTMLAttributes), 0];
  },
});
