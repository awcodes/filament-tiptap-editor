import { callOrReturn, getExtensionField, Node, mergeAttributes, findParentNode, findChildren } from "@tiptap/core";
import { TextSelection } from "@tiptap/pm/state";
import { createGridBuilder } from "./utils/createGridBuilder";

export const GridBuilder = Node.create({
  name: "gridBuilder",

  group: "block",

  defining: true,

  isolating: true,

  allowGapCursor: false,

  content: "gridBuilderColumn+",

  gridBuilderRole: "gridBuilder",

  addOptions() {
    return {
      HTMLAttributes: {
        class: "filament-tiptap-grid-builder",
      },
    };
  },

  addAttributes() {
    return {
      'data-type': {
        default: "responsive",
        parseHTML: (element) => element.getAttribute("data-type"),
      },
      'data-cols': {
        default: 2,
        parseHTML: (element) => element.getAttribute("data-cols"),
      },
      'data-stack-at': {
        default: 'md',
        parseHTML: (element) => element.getAttribute("data-stack-at"),
      },
      'style': {
        default: null,
        parseHTML: (element) => element.getAttribute("style"),
        renderHTML: (attributes) => {
          return {
            style: `grid-template-columns: repeat(${attributes['data-cols']}, 1fr);`
          }
        }
      }
    };
  },

  parseHTML() {
    return [
      {
        tag: "div",
        getAttrs: (node) => node.classList.contains("filament-tiptap-grid-builder") && null,
      },
    ];
  },

  renderHTML({ HTMLAttributes }) {
    return ["div", mergeAttributes(this.options.HTMLAttributes, HTMLAttributes), 0];
  },

  addCommands() {
    return {
      insertGridBuilder:
        ({ cols = 3, type = "responsive", stackAt, asymmetricLeft = null, asymmetricRight = null } = {}) =>
        ({ tr, dispatch, editor }) => {
          const node = createGridBuilder(editor.schema, cols, type, stackAt,asymmetricLeft, asymmetricRight);

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
      "Mod-Alt-G": () => this.editor.commands.insertGridBuilder(),
    };
  },

  extendNodeSchema(extension) {
    const context = {
      name: extension.name,
      options: extension.options,
      storage: extension.storage,
    };

    return {
      gridBuilderRole: callOrReturn(getExtensionField(extension, "gridBuilderRole", context)),
    };
  },
});
