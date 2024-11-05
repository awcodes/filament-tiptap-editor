import { Extension } from "@tiptap/core";

const TAB_CHAR = "\u00A0\u00A0\u00A0\u00A0";

export const CustomIndent = Extension.create({
  name: "customIndent",

  addCommands() {
    return {
      doIndent:
        () => ({ editor, chain }) => {
          const { selection } = editor.state;
          const { $from } = selection;

          // Check if we're at the start of a list item
          if (editor.isActive("listItem") && $from.parentOffset === 0) {
            // Attempt to sink the list item
            const sinkResult = chain().sinkListItem("listItem").run();

            // If sinking was successful, return true
            if (sinkResult) {
              return true;
            }
            // If sinking failed, we'll fall through to inserting a tab
          }

          // Insert a tab character
          chain()
          .command(({ tr }) => {
            tr.insertText(TAB_CHAR);
            return true;
          })
          .run();

          // Prevent default behavior (losing focus)
          return true;
        },
      undoIndent:
        () => ({editor, chain}) => {
          const { selection, doc } = editor.state;
          const { $from } = selection;
          const pos = $from.pos;

          // Check if we're at the start of a list item
          if (editor.isActive("listItem") && $from.parentOffset === 0) {
            // If so, lift the list item
            return chain().liftListItem("listItem").run();
          }

          // Check if the previous character is a tab
          if (doc.textBetween(pos - 4, pos) === TAB_CHAR) {
            // If so, delete it
            chain()
              .command(({ tr }) => {
                tr.delete(pos - 4, pos);
                return true;
              })
              .run();
            return true;
          }

          // Prevent default behavior (losing focus)
          return true;
        }
    }
  },

  addKeyboardShortcuts() {
    return {
      "Tab": ({ editor, chain }) => {
        const { selection } = editor.state;
        const { $from } = selection;

        // Check if we're at the start of a list item
        if (editor.isActive("listItem") && $from.parentOffset === 0) {
          // Attempt to sink the list item
          const sinkResult = chain().sinkListItem("listItem").run();

          // If sinking was successful, return true
          if (sinkResult) {
            return true;
          }
          // If sinking failed, we'll fall through to inserting a tab
        }

        // Insert a tab character
        chain()
          .command(({ tr }) => {
            tr.insertText(TAB_CHAR);
            return true;
          })
          .run();

        // Prevent default behavior (losing focus)
        return true;
      },
      "Shift-Tab": ({editor, chain}) => {
        const { selection, doc } = editor.state;
        const { $from } = selection;
        const pos = $from.pos;

        // Check if we're at the start of a list item
        if (editor.isActive("listItem") && $from.parentOffset === 0) {
          // If so, lift the list item
          return chain().liftListItem("listItem").run();
        }

        // Check if the previous character is a tab
        if (doc.textBetween(pos - 4, pos) === TAB_CHAR) {
          // If so, delete it
          chain()
            .command(({ tr }) => {
              tr.delete(pos - 4, pos);
              return true;
            })
            .run();
          return true;
        }

        // Prevent default behavior (losing focus)
        return true;
      },
    };
  },
});