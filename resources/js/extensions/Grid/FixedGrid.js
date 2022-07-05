import { callOrReturn, getExtensionField, Node, mergeAttributes, ParentConfig } from "@tiptap/core";
import { TextSelection } from "prosemirror-state";
import { createGrid } from "./utils/createGrid";

export const FixedGrid = Node.create({
  name: "fixedGrid",

  group: "block",

  defining: true,

  isolating: true,

  allowGapCursor: false,

  content: "gridColumn+",

  gridRole: "grid",

  addOptions() {
    return {
      HTMLAttributes: {
        class: "filament-tiptap-grid",
      },
    };
  },

  addAttributes() {
    return {
      type: {
        default: "responsive",
      },
      cols: {
        default: 2,
      },
    };
  },

  parseHTML() {
    return [
      {
        tag: "div",
        getAttrs: (node) => node.attrs.class.contains("filament-tiptap-grid"),
      },
    ];
  },

  renderHTML({ HTMLAttributes }) {
    return ["div", mergeAttributes(this.options.HTMLAttributes, HTMLAttributes), 0];
  },

  addCommands() {
    return {
      insertGrid:
        ({ cols = 3, type = "fixed" } = {}) =>
        ({ tr, dispatch, editor }) => {
          const node = createGrid(editor.schema, cols, type);

          if (dispatch) {
            const offset = tr.selection.anchor + 1;

            tr.replaceSelectionWith(node)
              .scrollIntoView()
              .setSelection(TextSelection.near(tr.doc.resolve(offset)));
          }

          return true;
        },
    };
  },

  addKeyboardShortcuts() {
    return {
      "Mod-Alt-G": () => this.editor.commands.insertGrid(),
    };
  },

  extendNodeSchema(extension) {
    const context = {
      name: extension.name,
      options: extension.options,
      storage: extension.storage,
    };

    return {
      gridRole: callOrReturn(getExtensionField(extension, "gridRole", context)),
    };
  },
});
