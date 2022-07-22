import { callOrReturn, getExtensionField, Node, mergeAttributes, findParentNode, findChildren } from "@tiptap/core";
import { TextSelection } from "prosemirror-state";
import { createGrid } from "./utils/createGrid";
import { GapCursor } from "prosemirror-gapcursor";

export const Grid = Node.create({
  name: "grid",

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
        parseHTML: (element) => element.getAttribute("type"),
      },
      cols: {
        default: 2,
        parseHTML: (element) => element.getAttribute("cols"),
      },
    };
  },

  parseHTML() {
    return [
      {
        tag: "div",
        getAttrs: (node) => node.classList.contains("filament-tiptap-grid") && null,
      },
    ];
  },

  renderHTML({ HTMLAttributes }) {
    return ["div", mergeAttributes(this.options.HTMLAttributes, HTMLAttributes), 0];
  },

  addCommands() {
    return {
      insertGrid:
        ({ cols = 3, type = "responsive" } = {}) =>
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
