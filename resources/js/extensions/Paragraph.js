import Paragraph from "@tiptap/extension-paragraph";

export const CustomParagraph = Paragraph.extend({
  addAttributes() {
    return {
      class: {
        default: null,
      },
    };
  },
});
