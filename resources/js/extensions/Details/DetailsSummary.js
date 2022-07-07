import { Node, mergeAttributes } from "@tiptap/core";

export const DetailsSummary = Node.create({
  name: "detailsSummary",
  content: "text*",
  defining: true,
  selectable: false,
  isolating: true,
  addOptions() {
    return {
      HTMLAttributes: {},
    };
  },
  parseHTML() {
    return [
      {
        tag: "summary",
      },
    ];
  },
  renderHTML({ HTMLAttributes }) {
    return ["summary", mergeAttributes(this.options.HTMLAttributes, HTMLAttributes), 0];
  },
});
