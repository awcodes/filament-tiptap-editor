import CodeBlockLowlight from "@tiptap/extension-code-block-lowlight";

export const CustomCodeBlockLowlight = CodeBlockLowlight.extend({
  addKeyboardShortcuts() {
    return {
      ...this.parent?.(),
      ArrowDown: () => {
        const state = this.editor.state;
        const { from, to } = state.selection;

        if (from > 1 && from === to) {
          let inCodeBlock = false;
          state.doc.nodesBetween(from - 1, to - 1, (node) => {
            if (node.type.name === "codeBlock") inCodeBlock = true;
          });

          let nothingOnRight = true;
          state.doc.nodesBetween(from + 1, to + 1, (node) => {
            if (node) nothingOnRight = false;
          });

          if (inCodeBlock && nothingOnRight) {
            return this.editor.commands.setHardBreak();
          }
        }

        return false;
      },
    };
  },
});
