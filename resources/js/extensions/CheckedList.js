import { Node, mergeAttributes } from "@tiptap/core";

export const CheckedList = Node.create({
  name: "checkedList",

  priority: 50,

  addOptions() {
    return {
      itemTypeName: "listItem",
      HTMLAttributes: {
        class: "checked-list",
      },
    };
  },

  group: "block list",

  content() {
    return `${this.options.itemTypeName}+`;
  },

  parseHTML() {
    return [{ tag: "ul", getAttrs: (element) => element.classList.contains("checked-list"), priority: 1000 }];
  },

  renderHTML({ HTMLAttributes }) {
    return ["ul", mergeAttributes(this.options.HTMLAttributes, HTMLAttributes), 0];
  },

  addCommands() {
    return {
      toggleCheckedList:
        () =>
        ({ commands }) => {
          return commands.toggleList(this.name, this.options.itemTypeName);
        },
    };
  },
});
