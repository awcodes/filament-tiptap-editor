import { Node, mergeAttributes } from "@tiptap/core";
import BulletList from "@tiptap/extension-bullet-list";

const CheckedList = BulletList.extend({
  name: "checkedList",

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
    return [{ tag: "ul", getAttrs: (element) => element.classList.contains("checked-list"), priority: 100 }];
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

export default CheckedList;
