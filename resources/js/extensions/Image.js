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
        default: null,
        parseHTML: element => element.getAttribute('loading') === 'lazy' ? element.getAttribute('data-lazy') : null,
        renderHTML: (attributes) => {
          if (attributes.lazy) {
            return {
              "data-lazy": attributes.lazy,
              "loading": "lazy",
            };
          }
        }
      },
      srcset: {
        default: null,
      },
      sizes: {
        default: null,
      },
      onload: {
        default: null,
      },
    };
  },
});
