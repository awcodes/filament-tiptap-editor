import Link from "@tiptap/extension-link";

export const CustomLink = Link.extend({
  addOptions() {
    return {
      openOnClick: true,
      linkOnPaste: true,
      autolink: true,
      HTMLAttributes: {},
    };
  },

  addAttributes() {
    return {
      href: {
        default: null,
      },
      target: {
        default: this.options.HTMLAttributes.target,
      },
      hreflang: {
        default: null,
      },
      rel: {
        default: null,
      },
    };
  },
});
