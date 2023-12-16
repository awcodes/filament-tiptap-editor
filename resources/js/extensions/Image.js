import Image from "@tiptap/extension-image";

export const CustomImage = Image.extend({
  addAttributes() {
    return {
      src: {
        default: null,
      },
      alt: {
        default: null,
      },
      title: {
        default: null,
      },
      width: {
        default: null,
      },
      height: {
        default: null,
      },
      lazy: {
        default: false,
        getAttrs: (value) => (value ? { "data-lazy": value } : null),
        renderHTML: (attributes) => {
          if (attributes.lazy) {
            return {
              "data-lazy": attributes.lazy,
            };
          }
        }
      }
    };
  },
});
